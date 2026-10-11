<?php

declare(strict_types=1);

namespace Core;

use Illuminate\Database\Capsule\Manager as DB;

/**
 * Abstract Seeder sınıfı
 * Tüm seeder dosyaları bu sınıfı extend eder.
 */
abstract class Seeder
{
    /**
     * Seed verilerini ekle
     */
    abstract public function run(): void;

    /**
     * Başka bir seeder'ı çalıştır (tekil veya dizi)
     */
    protected function call(string|array $seederClass): void
    {
        foreach ((array) $seederClass as $class) {
            $seeder = new $class;
            $seeder->run();
        }
    }

    /**
     * Tabloya veri ekle
     */
    protected function insert(string $table, array $data): void
    {
        DB::table($table)->insert($data);
    }

    /**
     * Tabloyu temizle ve veri ekle (sürücü-farkında foreign key yönetimi)
     * Truncate and insert (driver-aware foreign key handling)
     *
     * MySQL'e özgü SET FOREIGN_KEY_CHECKS sqlite üzerinde hata verirdi; Migrator
     * ile aynı sürücü-farkında deseni kullanır.
     * MySQL-only SET FOREIGN_KEY_CHECKS would error on sqlite; mirrors the same
     * driver-aware pattern used by the Migrator.
     */
    protected function truncateAndInsert(string $table, array $rows): void
    {
        $connection = DB::connection();
        $isSqlite = $connection->getDriverName() === 'sqlite';

        $connection->statement($isSqlite ? 'PRAGMA foreign_keys = OFF' : 'SET FOREIGN_KEY_CHECKS = 0');
        DB::table($table)->truncate();
        $connection->statement($isSqlite ? 'PRAGMA foreign_keys = ON' : 'SET FOREIGN_KEY_CHECKS = 1');

        foreach ($rows as $row) {
            DB::table($table)->insert($row);
        }
    }

    /**
     * Tablodaki kayıt sayısını döndür
     */
    protected function count(string $table): int
    {
        return DB::table($table)->count();
    }
}
