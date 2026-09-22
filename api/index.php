<?php

/**
 * Vercel Serverless Entry Point untuk Laravel
 * Environment variables di-inject langsung agar tidak perlu
 * konfigurasi manual di Vercel Dashboard.
 * CATATAN: Untuk GEMINI_API_KEY dan kredensial database,
 * tambahkan manual di Vercel Dashboard > Settings > Environment Variables
 */

$envVars = [
    // === App Core ===
    'APP_NAME'             => 'Sistem Retribusi',
    'APP_ENV'              => 'production',
    'APP_KEY'              => 'base64:r6fxya6ciN4rkh8UzxSFSFDeptt7Py2ZE7tERA3Hoeo=',
    'APP_DEBUG'            => 'true',
    'APP_URL'              => 'https://sistem-retribusi-umber.vercel.app',
    'APP_LOCALE'           => 'en',
    'APP_FALLBACK_LOCALE'  => 'en',
    'APP_FAKER_LOCALE'     => 'en_US',

    // === Cache Path (wajib di Vercel, hanya /tmp yang writable) ===
    'APP_CONFIG_CACHE'     => '/tmp/config.php',
    'APP_EVENTS_CACHE'     => '/tmp/events.php',
    'APP_PACKAGES_CACHE'   => '/tmp/packages.php',
    'APP_ROUTES_CACHE'     => '/tmp/routes.php',
    'APP_SERVICES_CACHE'   => '/tmp/services.php',
    'VIEW_COMPILED_PATH'   => '/tmp',

    // === Session & Cache ===
    'SESSION_DRIVER'       => 'cookie',
    'SESSION_LIFETIME'     => '120',
    'CACHE_STORE'          => 'array',

    // === Logging ===
    'LOG_CHANNEL'          => 'stderr',
    'LOG_LEVEL'            => 'debug',

    // === Queue & Filesystem ===
    'QUEUE_CONNECTION'     => 'sync',
    'FILESYSTEM_DISK'      => 'local',
];

// Inject hanya jika belum di-set dari luar (agar Vercel Dashboard bisa override)
foreach ($envVars as $key => $value) {
    if (getenv($key) === false) {
        putenv("$key=$value");
        $_ENV[$key]    = $value;
        $_SERVER[$key] = $value;
    }
}

// Bootstrap Laravel
require __DIR__ . '/../public/index.php';
