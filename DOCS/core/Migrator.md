# Dosya Raporu: core/Migrator.php

## Amaç
Veritabanı migrasyon ve seeding yöneticisi.

## Genel Bakış
Migrasyon dosyalarının ve seeder'ların yürütülmesini koordine eder. Veritabanındaki bir `migrations` tablosunu kullanarak hangi migrasyonların zaten çalıştırıldığını takip eder. Öncelikli olarak konsol veya özel bir rota üzerinden kullanım için tasarlanmıştır.

## Dosya Konumu
`core/Migrator.php`

## Ad Alanı
`Core`

## İçe Aktarmalar
- `Core\Facades\Log`
- `Database\Seeders\DatabaseSeeder`
- `Illuminate\Database\Capsule\Manager as DB`
- `Illuminate\Database\Schema\Blueprint`

## Sınıflar
- `class Migrator`

## Metotlar
- `runMigrationsOnly(): array`: Yalnızca bekleyen migrasyonları yürütür.
- `runSeedersOnly(): void`: `DatabaseSeeder`'dan başlayarak tüm seeder'ları yürütür.
- `runFresh(): array`: Tüm tabloları siler, tüm migrasyonları çalıştırır ve seeder'ları yürütür.
- `runSingleMigration(string $filename, bool $force = false): void`: Belirli bir migrasyon dosyasını yürütür.
- `runSingleSeeder(string $className): void`: Belirli bir seeder sınıfını yürütür.
- `run(): void`: Geriye dönük uyumluluk için eski metot.

## Dahili İş Akışı
- `ensureMigrationsTable()`: Eğer yoksa `migrations` tablosunu oluşturur.
- `runPendingMigrations()`: `database/migrations/*.php` dosyalarını tarar, sıralar ve `migrations` tablosunda bulunmayanları bir toplu işlem (batch) numarası atayarak çalıştırır.
- `dropAllTables()`: Veritabanındaki tüm tabloları kaldırmak için Eloquent Şema oluşturucuyu kullanır.
- `disableForeignKeys()` / `enableForeignKeys()`: Sürücüye bağlı olarak (MySQL vs SQLite) yabancı anahtar kontrollerini açıp kapatır.
- `executeSeeders()`: Tüm seeder dosyalarını yükler ve `DatabaseSeeder::run()` metodunu çalıştırır.

## Bağımlılıklar
- `Core\Migration` (Kullanır)
- `Core\Facades\Log` (Kullanır)
- `Database\Seeders\DatabaseSeeder` (Kullanır)
- `Illuminate\Database\Capsule\Manager` (Kullanır)

## Kaynak Referansları
- `core/Migrator.php:1-327`
