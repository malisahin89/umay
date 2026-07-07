# Dosya Raporu: tests/bootstrap.php

## Amaç
Test ortamını hazırlayan PHPUnit bootstrap dosyası.

## Genel Bakış
Test paketi çalışmadan önce yüklenir. `BASE_PATH`'i tanımlar, Composer autoloader'ı dahil eder, ortam değişkenlerini yükler (`.env.testing` varsa, yoksa `.env`), veritabanını başlatır ve global yardımcı fonksiyonları yükler.

## Dosya Konumu
`tests/bootstrap.php`

## İsim Uzayı
Global (isim uzayı yok).

## İçe Aktarmalar
- `Core\Database`
- `Dotenv\Dotenv` (vendor)

## Dahili İş Akışı
1. `BASE_PATH`'i proje kökü olarak tanımlar.
2. `vendor/autoload.php` dosyasını gerektirir.
3. Mevcutsa `.env.testing`, değilse `.env` kullanarak `Dotenv::createImmutable()` aracılığıyla env yükler (`safeLoad()`).
4. `DB_DRIVER=sqlite` ise, bellek içi bir SQLite veritabanı ile `Database::init()` çağrılır; aksi takdirde `config/database.php` dahil edilir.
5. `core/helpers.php` dosyasını gerektirir.

## Çapraz Referanslar
- **Çağırır:** `Core\Database::init()` (bkz. `DOCS/core/Database.md`)
- **Yükler:** `core/helpers.php` (bkz. `DOCS/core/helpers.md`)
- **Referans Veren:** `phpunit.xml` bootstrap ayarı (bkz. `DOCS/phpunit.xml.md`)

## Kaynak Referansları
- `tests/bootstrap.php:1-32`
