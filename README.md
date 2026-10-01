# 🏛️ Sistem Retribusi BAPENDA
Aplikasi web untuk monitoring dan analisis realisasi retribusi daerah menggunakan **Gemini AI OCR** — upload PDF laporan APBD, AI akan otomatis mengekstrak data rekening 4.1.02 (Retribusi Daerah).

---

## ⚡ Setup Cepat (Laptop Baru / Clone Pertama Kali)

### 1. Clone Repositori
```bash
git clone https://github.com/ramaSandika/sistem-retribusi.git
cd sistem-retribusi
```

### 2. Install Dependensi
```bash
composer install
```

### 3. Buat File `.env`
```bash
copy .env.example .env
php artisan key:generate
```

### 4. 🔑 Isi API Key Gemini AI *(WAJIB)*
Buka file `.env`, cari baris:
```
GEMINI_API_KEY=ISI_API_KEY_GEMINI_ANDA_DI_SINI
```
Ganti dengan API Key dari: **https://aistudio.google.com/apikey**
> Klik "Create API Key" → Copy (format: `AIzaSy...`)

### 5. Konfigurasi Database
Edit `.env` sesuaikan:
```
DB_DATABASE=web_retribusi
DB_USERNAME=root
DB_PASSWORD=     ← isi jika ada password MySQL
```

Buat database & jalankan migrasi:
```bash
php artisan migrate --seed
```

### 6. Jalankan Aplikasi
Buka **2 terminal berbeda**:

**Terminal 1 — Web Server:**
```bash
php artisan serve
```

**Terminal 2 — Queue Worker (untuk proses OCR AI):**
```bash
php artisan queue:work --tries=2 --timeout=300
```

Buka browser: **http://localhost:8000**

---

## 🔄 Update dari Git (Sudah Pernah Setup)
```bash
git pull origin main
php artisan migrate
php artisan config:clear
php artisan cache:clear
```
> ⚠️ **Jangan lupa restart** kedua terminal (server + queue worker) setelah pull!

---

## 👤 Akun Default
| Email | Password | Role |
|---|---|---|
| admin@gmail.com | password | Admin |
| user@gmail.com | password | User |

---

## 📋 Fitur Utama
- 📄 **Upload PDF** laporan APBD
- 🤖 **AI OCR** ekstrak otomatis rekening 4.1.02 Retribusi Daerah
- ✏️ **Edit & Validasi** data hasil ekstraksi sebelum disimpan
- 📊 **Dashboard** grafik capaian retribusi
- 📥 **Export Excel** data realisasi
- ⏱️ **Real-time Progress** saat AI memproses dokumen

---

## 🛠️ Tech Stack
- **Backend**: Laravel 11 (PHP)
- **Database**: MySQL
- **AI**: Google Gemini 2.0 Flash (Multimodal PDF)
- **Frontend**: Bootstrap 5 + Bootstrap Icons
- **Queue**: Laravel Queue (Database driver)
