<?php

declare(strict_types=1);

namespace Core;

use Illuminate\Database\Capsule\Manager as DB;

/**
 * Abstract Migration sınıfı
 * Tüm migration dosyaları bu sınıfı extend eder.
 */
abstract class Migration
{
    /**
     * Migration'ı çalıştır (tabloyu oluştur/değiştir)
     */
    abstract public function up(): void;

    /**
     * Migration'ı geri al (tabloyu sil/eski haline getir)
     */
    abstract public function down(): void;

    /**
     * Raw SQL çalıştır (CREATE TABLE, DROP TABLE, ALTER TABLE vb.)
     * Execute raw SQL (CREATE TABLE, DROP TABLE, ALTER TABLE etc.)
     */
    protected function execute(string $sql): void
    {
        DB::connection()->statement($sql);
    }

    /**
     * Prepared statement ile SQL çalıştır
     * Execute SQL with prepared statement
     */
    protected function query(string $sql, array $params = []): array
    {
        return DB::connection()->select($sql, $params);
    }

    /**
     * Tablo var mı kontrol et (sürücü-farkında — mysql/sqlite/pgsql)
     * Check if table exists (driver-aware — mysql/sqlite/pgsql)
     *
     * Schema builder yerine MySQL'e özgü information_schema/DATABASE() kullanmak,
     * sqlite (testler) ve pgsql üzerinde patlardı.
     * Using MySQL-only information_schema/DATABASE() instead of the Schema builder
     * would break on sqlite (tests) and pgsql.
     */
    protected function tableExists(string $table): bool
    {
        return DB::schema()->hasTable($table);
    }

    /**
     * Kolon var mı kontrol et (sürücü-farkında — mysql/sqlite/pgsql)
     * Check if column exists (driver-aware — mysql/sqlite/pgsql)
     */
    protected function columnExists(string $table, string $column): bool
    {
        return DB::schema()->hasColumn($table, $column);
    }
}
