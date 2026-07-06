<?php

declare(strict_types=1);

use Core\Database;

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH.'/vendor/autoload.php';

// Test .env — load .env.testing for test environment if it exists, otherwise .env
// Test .env — test ortamı için .env.testing varsa onu yükle, yoksa .env
$dotenvFile = file_exists(BASE_PATH.'/.env.testing') ? '.env.testing' : '.env';
$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH, $dotenvFile);
$dotenv->safeLoad();

// Test DB: SQLite in-memory or real test DB
// Test DB: SQLite in-memory veya gerçek test DB
$dbDriver = $_ENV['DB_DRIVER'] ?? 'mysql';

if ($dbDriver === 'sqlite') {
    Database::init([
        'driver' => 'sqlite',
        'database' => ':memory:',
        'prefix' => '',
    ]);
} else {
    require_once BASE_PATH.'/config/database.php';
}

require_once BASE_PATH.'/core/helpers.php';
