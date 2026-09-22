FROM php:8.2-apache

# Install dependensi sistem dan ekstensi database yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd \
    && a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Salin seluruh project
COPY . /var/www/html

# Pastikan direktori storage dan bootstrap/cache ada
RUN mkdir -p /var/www/html/storage/framework/views \
             /var/www/html/storage/framework/sessions \
             /var/www/html/storage/framework/cache \
             /var/www/html/storage/logs \
             /var/www/html/bootstrap/cache

# Install dependensi PHP tanpa menjalankan artisan script saat build
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Konfigurasi Apache DocumentRoot ke folder public Laravel
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Berikan izin penuh ke storage dan bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Port default Apache
EXPOSE 80

# Start Apache langsung dan jalankan migrasi & seeder di background agar server instan aktif
CMD php artisan package:discover --ansi && php artisan config:clear && (php artisan migrate --force && php artisan db:seed --force &) && apache2-foreground
