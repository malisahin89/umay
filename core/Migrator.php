<?php

declare(strict_types=1);

namespace Core;

use Core\Facades\Log;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;

/**
 * Migrator: Class that manages Migration and Seed operations
 * Migrator: Migration ve Seed işlemlerini yöneten sınıf
 *
 * Usage / Kullanım (console only / yalnızca konsol):
 *   php umay migrate | db:seed | migrate:fresh | migrate:rollback
 */
class Migrator
{
    private static string $migrationsPath;

    private static string $seedersPath;

    private static function init(): void
    {
        self::$migrationsPath = BASE_PATH.'/database/migrations';
        self::$seedersPath = BASE_PATH.'/database/seeders';
    }

    // ─── Public API (Called from Route and Console) ──────────────────────────
    // ─── Public API (Route ve Console'dan çağrılır) ──────────────────────

    /**
     * Run only pending migrations
     * Sadece bekleyen migration'ları çalıştır
     */
    public static function runMigrationsOnly(): array
    {
        self::init();
        self::ensureMigrationsTable();
        $results = self::runPendingMigrations();
        Log::info("Migrator: {$results['count']} migration çalıştırıldı.");

        return $results;
    }

    /**
     * Run only seeders
     * Sadece seeder'ları çalıştır
     */
    public static function runSeedersOnly(): void
    {
        self::init();
        self::executeSeeders();
        Log::info("Migrator: Seeder'lar çalıştırıldı.");
    }

    /**
     * Fresh: Drop all tables + migrate + seed
     * Fresh: Tüm tabloları sil + migrate + seed
     */
    public static function runFresh(): array
    {
        self::init();
        self::dropAllTables();
        self::ensureMigrationsTable();
        $results = self::runPendingMigrations();
        Log::info("Migrator: Fresh — {$results['count']} migration çalıştırıldı.");
        self::executeSeeders();
        Log::info("Migrator: Fresh — Seeder'lar çalıştırıldı.");

        return $results;
    }

    /**
     * Run a single migration (by filename)
     * Tekil migration çalıştır (dosya adıyla)
     *
     * @param  bool  $force  If true, runs down() then up() (resets table) // true ise önce down() sonra up() çalıştırır (tablo sıfırlanır)
     */
    public static function runSingleMigration(string $filename, bool $force = false): void
    {
        self::init();
        self::ensureMigrationsTable();

        $file = self::$migrationsPath.'/'.$filename.'.php';
        if (! file_exists($file)) {
            throw new \RuntimeException("Migration file not found // dosyası bulunamadı: $filename");
        }

        // Check if it already ran
        // Zaten çalışmış mı kontrol et
        $existing = DB::table('migrations')->where('migration', $filename)->first();
        $alreadyRan = (bool) $existing;

        if ($alreadyRan && ! $force) {
            throw new \RuntimeException("This migration has already been run // Bu migration zaten çalıştırılmış: $filename");
        }

        $migration = require $file;
        if (! $migration instanceof Migration) {
            throw new \RuntimeException("Invalid migration file // Geçersiz migration dosyası: $filename");
        }

        // If force, run down() first (table DROP)
        // Force ise önce down() çalıştır (tablo DROP)
        if ($force && $alreadyRan) {
            self::disableForeignKeys();
            $migration->down();
            self::enableForeignKeys();
            Log::info("Migration rollback yapıldı: $filename");
        }

        $migration->up();

        // Add record if it's a new migration
        // Yeni migration ise kayıt ekle
        if (! $alreadyRan) {
            $maxBatch = DB::table('migrations')->max('batch') ?? 0;
            $batch = (int) $maxBatch + 1;

            DB::table('migrations')->insert([
                'migration' => $filename,
                'batch' => $batch,
            ]);
        }

        Log::info("Tekil migration çalıştırıldı: $filename".($force ? ' (force: down+up)' : ''));
    }

    /**
     * Run a single seeder (by class name)
     * Tekil seeder çalıştır (class adıyla)
     */
    public static function runSingleSeeder(string $className): void
    {
        self::init();

        // Load all seeder files (for dependencies)
        // Tüm seeder dosyalarını yükle (bağımlılıklar için)
        $files = glob(self::$seedersPath.'/*.php');
        foreach ($files as $file) {
            require_once $file;
        }

        $fqcn = "Database\\Seeders\\$className";
        if (! class_exists($fqcn)) {
            throw new \RuntimeException("Seeder class not found // sınıfı bulunamadı: $fqcn");
        }

        $seeder = new $fqcn;
        $seeder->run();

        Log::info("Tekil seeder çalıştırıldı: $className");
    }

    // ─── Console Kernel compatibility ─────────────────────────────────────────
    // ─── Console Kernel uyumu ────────────────────────────────────────────

    /**
     * Old run() method called from Console Kernel (backward compatibility)
     * Console Kernel'dan çağrılan eski run() metodu (geriye uyumluluk)
     */
    public static function run(): void
    {
        // No longer runs automatically, can only be called from console
        // Artık otomatik çalışmıyor, sadece console'dan çağrılabilir
        self::runMigrationsOnly();
    }

    // ─── Internal Methods ────────────────────────────────────────────────

    /**
     * Drop all tables (for FRESH_DB)
     * Tüm tabloları sil (FRESH_DB için)
     */
    private static function dropAllTables(): void
    {
        // Schema builder is driver-aware (mysql/sqlite/pgsql) and handles foreign
        // key constraints internally — no MySQL-only SHOW TABLES / FOREIGN_KEY_CHECKS.
        // Schema builder sürücü-farkındadır (mysql/sqlite/pgsql) ve foreign key
        // kısıtlarını kendi içinde yönetir — MySQL'e özgü SHOW TABLES / FOREIGN_KEY_CHECKS yok.
        DB::connection()->getSchemaBuilder()->dropAllTables();

        Log::info('Migrator: Tüm tablolar silindi.');
    }

    /**
     * Disable foreign key enforcement (driver-aware).
     * Foreign key zorlamasını kapat (sürücü-farkında).
     */
    private static function disableForeignKeys(): void
    {
        $connection = DB::connection();
        $connection->statement(
            $connection->getDriverName() === 'sqlite'
                ? 'PRAGMA foreign_keys = OFF'
                : 'SET FOREIGN_KEY_CHECKS = 0'
        );
    }

    /**
     * Re-enable foreign key enforcement (driver-aware).
     * Foreign key zorlamasını yeniden aç (sürücü-farkında).
     */
    private static function enableForeignKeys(): void
    {
        $connection = DB::connection();
        $connection->statement(
            $connection->getDriverName() === 'sqlite'
                ? 'PRAGMA foreign_keys = ON'
                : 'SET FOREIGN_KEY_CHECKS = 1'
        );
    }

    /**
     * Create migrations table (if not exists)
     * migrations tablosunu oluştur (yoksa)
     */
    private static function ensureMigrationsTable(): void
    {
        $schema = DB::connection()->getSchemaBuilder();
        if ($schema->hasTable('migrations')) {
            return;
        }

        // Portable schema definition (works on mysql + sqlite alike).
        // Taşınabilir şema tanımı (mysql + sqlite üzerinde aynı şekilde çalışır).
        $schema->create('migrations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('migration');
            $table->integer('batch');
            $table->timestamp('executed_at')->useCurrent();
        });
    }

    /**
     * Run pending migrations
     * Bekleyen migration'ları çalıştır
     */
    private static function runPendingMigrations(): array
    {
        $results = ['count' => 0, 'files' => []];

        if (! is_dir(self::$migrationsPath)) {
            return $results;
        }

        // Get already run migrations
        // Zaten çalıştırılmış migration'ları al
        $ran = DB::table('migrations')->pluck('migration')->toArray();

        // Scan migration files (ordered)
        // Migration dosyalarını tara (sıralı)
        $files = glob(self::$migrationsPath.'/*.php');
        sort($files);

        // New batch number
        // Yeni batch numarası
        $maxBatch = (int) (DB::table('migrations')->max('batch') ?? 0);
        $batch = $maxBatch + 1;

        foreach ($files as $file) {
            $filename = basename($file, '.php');

            // Skip if already run
            // Zaten çalıştırılmışsa atla
            if (in_array($filename, $ran)) {
                continue;
            }

            try {
                $migration = require $file;

                if ($migration instanceof Migration) {
                    $migration->up();

                    // Add record
                    // Kayıt ekle
                    DB::table('migrations')->insert([
                        'migration' => $filename,
                        'batch' => $batch,
                    ]);

                    $results['count']++;
                    $results['files'][] = $filename;
                    Log::info("Migration çalıştırıldı: $filename");
                }
            } catch (\Exception $e) {
                Log::error("Migration hatası: $filename", [
                    'error' => $e->getMessage(),
                ]);
                throw $e; // Throw error upwards, partial migration is dangerous // Hatayı yukarı fırlat, yarım kalan migration tehlikeli
            }
        }

        return $results;
    }

    /**
     * Run seeders
     * Seeder'ları çalıştır
     */
    private static function executeSeeders(): void
    {
        $databaseSeederFile = self::$seedersPath.'/DatabaseSeeder.php';

        if (! file_exists($databaseSeederFile)) {
            Log::warning("DatabaseSeeder.php bulunamadı: $databaseSeederFile");

            return;
        }

        require_once $databaseSeederFile;

        // Load all files in Seeders directory (for DatabaseSeeder's call() method to use)
        // Seeders dizinindeki tüm dosyaları yükle (DatabaseSeeder'ın call() ile kullanması için)
        $files = glob(self::$seedersPath.'/*.php');
        foreach ($files as $file) {
            require_once $file;
        }

        $seeder = new DatabaseSeeder;
        $seeder->run();
    }
}
