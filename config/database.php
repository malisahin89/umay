<?php

declare(strict_types=1);

use Core\Database;
use Dotenv\Dotenv;

// Load .env file (via vlucas/phpdotenv)
// .env dosyasını yükle (vlucas/phpdotenv ile)
// safeLoad: .env yoksa hata fırlatma — config'deki ?? fallback'ler devreye girer
// safeLoad: don't throw if .env is missing — the ?? fallbacks below take over
$dotenv = Dotenv::createImmutable(__DIR__.'/..');
$dotenv->safeLoad();

// Prepare database configuration
// Database konfigürasyonunu hazırla
$config = [
    'driver' => 'mysql',
    'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
    'port' => $_ENV['DB_PORT'] ?? '3306',
    'database' => $_ENV['DB_DATABASE'] ?? 'umay',
    'username' => $_ENV['DB_USERNAME'] ?? 'root',
    'password' => $_ENV['DB_PASSWORD'] ?? '',
    'charset' => $_ENV['DB_CHARSET'] ?? 'utf8mb4',
    'collation' => $_ENV['DB_COLLATION'] ?? 'utf8mb4_unicode_ci',
    'prefix' => '',
    'strict' => true,
    'engine' => null,
];

// Initialize Eloquent via Core\Database class
// Core\Database sınıfı ile Eloquent'i başlat
Database::init($config);

// Return the config array so config('database.*') works as documented.
// Database::init() above is idempotent, so re-loading this file (e.g. via the
// config() helper's require) is safe.
//
// config('database.*') dokümante edildiği gibi çalışsın diye config dizisini döndür.
// Yukarıdaki Database::init() idempotent olduğundan dosyanın tekrar yüklenmesi
// (örn. config() helper'ının require'ı) güvenlidir.
return $config;
