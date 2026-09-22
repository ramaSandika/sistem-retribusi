<?php

/**
 * Clean & Production-grade Vercel Serverless Entrypoint for Laravel 12
 */

// 1. Tentukan folder /tmp sebagai tempat storage karena hanya /tmp yang writable di Vercel
$storagePath = '/tmp/storage';
$dirs = [
    $storagePath . '/app/public',
    $storagePath . '/framework/views',
    $storagePath . '/framework/sessions',
    $storagePath . '/framework/cache/data',
    $storagePath . '/logs',
];
foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// 2. Set Environment Variables
$envVars = [
    'APP_NAME'             => 'Sistem Retribusi',
    'APP_ENV'              => 'production',
    'APP_KEY'              => 'base64:r6fxya6ciN4rkh8UzxSFSFDeptt7Py2ZE7tERA3Hoeo=',
    'APP_DEBUG'            => 'true',
    'APP_URL'              => 'https://' . ($_SERVER['HTTP_HOST'] ?? 'sistem-retribusi-umber.vercel.app'),
    'ASSET_URL'            => 'https://' . ($_SERVER['HTTP_HOST'] ?? 'sistem-retribusi-umber.vercel.app'),
    'APP_LOCALE'           => 'en',
    'APP_FALLBACK_LOCALE'  => 'en',
    'APP_FAKER_LOCALE'     => 'en_US',
    'VIEW_COMPILED_PATH'   => $storagePath . '/framework/views',
    'SESSION_DRIVER'       => 'cookie',
    'SESSION_LIFETIME'     => '120',
    'CACHE_STORE'          => 'array',
    'LOG_CHANNEL'          => 'stderr',
    'LOG_LEVEL'            => 'debug',
    'QUEUE_CONNECTION'     => 'sync',
    'FILESYSTEM_DISK'      => 'local',
    'DB_CONNECTION'        => 'mysql',
    'DB_HOST'              => 'db-retribusi-ramap0346-ef86.h.aivencloud.com',
    'DB_PORT'              => '25577',
    'DB_DATABASE'          => 'defaultdb',
    'DB_USERNAME'          => 'avnadmin',
];

foreach ($envVars as $key => $value) {
    if (getenv($key) === false) {
        putenv("$key=$value");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

// 3. Paksa HTTPS & port di superglobals
$_SERVER['HTTPS'] = 'on';
$_SERVER['SERVER_PORT'] = 443;
$_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';
$_SERVER['HTTP_X_FORWARDED_PORT'] = 443;
$_SERVER['HTTP_X_FORWARDED_SSL'] = 'on';

define('LARAVEL_START', microtime(true));

// 4. Autoload Composer
require __DIR__ . '/../vendor/autoload.php';

// 5. Bootstrap Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Kunci penting: override storagePath ke /tmp/storage
$app->useStoragePath($storagePath);

// 6. Handle Request
$app->handleRequest(Illuminate\Http\Request::capture());
