# Dosya Raporu: core/Migration.php

## Amaç
Veritabanı migrasyonları için temel sınıf.

## Genel Bakış
Veritabanı şema değişikliklerini tanımlamak için yapılandırılmış bir yol sağlar. Her migrasyon dosyası bu sınıfı genişletir ve `up()` (değişiklikleri uygulamak için) ve `down()` (bunları geri almak için) metotlarını uygular.

## Dosya Konumu
`core/Migration.php`

## Ad Alanı
`Core`

## İçe Aktarmalar
- `Illuminate\Database\Capsule\Manager as DB`

## Sınıflar
- `abstract class Migration`

## Metotlar
- `up(): void`: Migrasyon mantığını uygulamak için soyut metot.
- `down(): void`: Geri alma mantığını uygulamak için soyut metot.
- `execute(string $sql): void`: Eloquent aracılığıyla ham bir SQL ifadesini yürütür.
- `query(string $sql, array $params = []): array`: Hazırlanmış bir select sorgusu yürütür.
- `tableExists(string $table): bool`: Veritabanında bir tablonun var olup olmadığını kontrol eder (sürücüye duyarlı).
- `columnExists(string $table, string $column): bool`: Bir tabloda bir sütunun var olup olmadığını kontrol eder (sürücüye duyarlı).

## Kaynak Referansları
- `core/Migration.php:1-65`
