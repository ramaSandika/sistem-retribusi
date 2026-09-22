<?php

/**
 * Vercel Serverless Entry Point untuk Laravel
 */

$envVars = [
    'APP_NAME'             => 'Sistem Retribusi',
    'APP_ENV'              => 'production',
    'APP_KEY'              => 'base64:r6fxya6ciN4rkh8UzxSFSFDeptt7Py2ZE7tERA3Hoeo=',
    'APP_DEBUG'            => 'true',
    'APP_URL'              => 'https://sistem-retribusi-umber.vercel.app',
    'ASSET_URL'            => 'https://sistem-retribusi-umber.vercel.app',
    'APP_LOCALE'           => 'en',
    'APP_FALLBACK_LOCALE'  => 'en',
    'APP_FAKER_LOCALE'     => 'en_US',
    'VIEW_COMPILED_PATH'   => '/tmp',
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
        $_ENV[$key]    = $value;
        $_SERVER[$key] = $value;
    }
}

// Paksa HTTPS di level PHP superglobals
$_SERVER['HTTPS'] = 'on';
$_SERVER['SERVER_PORT'] = 443;
$_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';
$_SERVER['HTTP_X_FORWARDED_PORT'] = 443;
$_SERVER['HTTP_X_FORWARDED_SSL'] = 'on';

require __DIR__ . '/../public/index.php';
