# Dosya Raporu: tests/Unit/DatabaseFixTest.php

## Amaç
Veritabanı ekleme davranışı için regresyon testi.

## Genel Bakış
`Core\Database::insert()` metodunun doğru son ekleme ID'sini (last insert id) döndürdüğünü doğrular.

## Dosya Konumu
`tests/Unit/DatabaseFixTest.php`

## İsim Uzayı
`Tests\Unit`

## Sınıflar
- `class DatabaseFixTest extends Tests\TestCase`

## Test Edilen Konu
- `Core\Database`

## Test Metotları
- `test_insert_returns_last_insert_id_correctly` — `:18`

## Çapraz Referanslar
- **Test Eder:** `Core\Database` (bkz. `DOCS/core/Database.md`)

## Kaynak Referansları
- `tests/Unit/DatabaseFixTest.php:1-31`
