<?php

declare(strict_types=1);

namespace Core;

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Connection;
use Illuminate\Events\Dispatcher;
use PDO;

/**
 * Database — Eloquent Capsule Manager wrapper.
 * Database — Eloquent Capsule Manager sarmalayıcısı.
 *
 * Tüm veritabanı işlemleri Eloquent ORM üzerinden yürütülür.
 * All database operations are executed through Eloquent ORM.
 *
 * Usage / Kullanım:
 *   DB::table('users')->where('id', 1)->first();    // Eloquent Query Builder
 *   DB::select('SELECT * FROM users WHERE id = ?', [1]); // Raw select
 *   DB::statement('SET FOREIGN_KEY_CHECKS=0');        // Raw statement
 *   DB::transaction(fn() => ...);                     // Transaction
 */
class Database
{
    private static ?Capsule $instance = null;

    /**
     * Initialize Eloquent Capsule with the given config.
     * Verilen config ile Eloquent Capsule'ü başlat.
     */
    public static function init(array $config): Capsule
    {
        if (self::$instance === null) {
            self::$instance = new Capsule;

            // Connection — sqlite (test / :memory:) veya mysql (varsayılan)
            // Connection — sqlite (test / :memory:) or mysql (default)
            if (($config['driver'] ?? 'mysql') === 'sqlite') {
                self::$instance->addConnection([
                    'driver' => 'sqlite',
                    'database' => $config['database'] ?? ':memory:',
                    'prefix' => $config['prefix'] ?? '',
                    'foreign_key_constraints' => true,
                ]);
            } else {
                self::$instance->addConnection([
                    'driver' => 'mysql',
                    'host' => $config['host'],
                    'port' => $config['port'],
                    'database' => $config['database'],
                    'username' => $config['username'],
                    'password' => $config['password'],
                    'charset' => $config['charset'],
                    'collation' => $config['collation'],
                    'options' => [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                        PDO::ATTR_EMULATE_PREPARES => false,
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$config['charset']} COLLATE {$config['collation']}",
                    ],
                ]);
            }

            // Real Event Dispatcher — enables Model Events (creating, saved, deleted) and Observers in all environments
            // Gerçek Event Dispatcher — Model Event'leri (creating, saved, deleted) ve Observer'ları tüm ortamlarda etkinleştirir
            self::$instance->setEventDispatcher(new Dispatcher(new Container));

            self::$instance->setAsGlobal();
            self::$instance->bootEloquent();

            // DebugBar query logger (only in debug mode)
            // DebugBar sorgu kaydedici (sadece debug modunda)
            if (filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
                self::$instance->getConnection()->listen(function ($query) {
                    if (! defined('UMAY_PROFILING') || ! UMAY_PROFILING) {
                        return;
                    }
                    $info = DebugBar::findCaller();
                    DebugBar::addQuery([
                        'sql' => $query->sql,
                        'bindings' => $query->bindings,
                        'time' => $query->time,
                        'caller' => $info['caller'],
                        'model' => $info['model'],
                    ]);
                });
            }
        }

        return self::$instance;
    }

    /**
     * Get the Eloquent Connection object.
     * Eloquent Connection nesnesini döndürür.
     *
     * NOT: Artık ham PDO değil, Eloquent Connection nesnesi döner.
     * NOTE: Returns Eloquent Connection, not raw PDO.
     */
    public static function getConnection(string $name = 'default'): Connection
    {
        if (self::$instance === null) {
            throw new \RuntimeException('Database not initialized. Call Database::init() first.');
        }

        return self::$instance->getConnection($name);
    }

    /**
     * Run a raw SQL statement (CREATE TABLE, SET, etc.)
     * Ham SQL ifadesi çalıştır (CREATE TABLE, SET, vb.)
     */
    public static function statement(string $sql, array $bindings = []): bool
    {
        return self::getConnection()->statement($sql, $bindings);
    }

    /**
     * Run a select query and return results.
     * Select sorgusu çalıştır ve sonuçları döndür.
     */
    public static function select(string $sql, array $bindings = []): array
    {
        return self::getConnection()->select($sql, $bindings);
    }

    /**
     * Run a select query and return the first result.
     * Select sorgusu çalıştır ve ilk sonucu döndür.
     */
    public static function selectOne(string $sql, array $bindings = []): ?object
    {
        $results = self::select($sql, $bindings);

        return $results[0] ?? null;
    }

    /**
     * Run an insert query.
     * Insert sorgusu çalıştır.
     */
    public static function insert(string $sql, array $bindings = []): bool
    {
        return self::getConnection()->insert($sql, $bindings);
    }

    /**
     * Run an update query and return affected rows.
     * Update sorgusu çalıştır ve etkilenen satır sayısını döndür.
     */
    public static function update(string $sql, array $bindings = []): int
    {
        return self::getConnection()->update($sql, $bindings);
    }

    /**
     * Run a delete query and return affected rows.
     * Delete sorgusu çalıştır ve etkilenen satır sayısını döndür.
     */
    public static function delete(string $sql, array $bindings = []): int
    {
        return self::getConnection()->delete($sql, $bindings);
    }

    /**
     * Run a callback within a database transaction.
     * Callback'i veritabanı transaction'ı içinde çalıştır.
     */
    public static function transaction(callable $callback): mixed
    {
        return self::getConnection()->transaction($callback);
    }

    /**
     * Begin a database transaction.
     * Veritabanı transaction'ı başlat.
     */
    public static function beginTransaction(): void
    {
        self::getConnection()->beginTransaction();
    }

    /**
     * Commit the active transaction.
     * Aktif transaction'ı onayla.
     */
    public static function commit(): void
    {
        self::getConnection()->commit();
    }

    /**
     * Rollback the active transaction.
     * Aktif transaction'ı geri al.
     */
    public static function rollBack(): void
    {
        self::getConnection()->rollBack();
    }

    // ── Connection lifecycle (Eloquent Capsule manages this) ──────────────
    // ── Bağlantı yaşam döngüsü (Eloquent Capsule yönetir) ───────────────

    public static function closeConnection(string $name = 'default'): void
    {
        if (self::$instance) {
            self::$instance->getDatabaseManager()->disconnect($name);
        }
    }

    public static function closeAllConnections(): void
    {
        if (self::$instance) {
            self::$instance->getDatabaseManager()->purge();
        }
    }

    public static function getActiveConnectionCount(): int
    {
        return self::$instance ? 1 : 0;
    }
}
