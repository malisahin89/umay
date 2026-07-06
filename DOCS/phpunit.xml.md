# Dosya Raporu: phpunit.xml

## Amaç
PHPUnit test framework'ü yapılandırması.

## Genel Bakış
Test paketlerini (Unit, Feature), bootstrap dosyasını ve test ortamı için ortam değişkenlerini tanımlar.

## Dosya Konumu
`phpunit.xml`

## Yapılandırma
- `bootstrap`: `tests/bootstrap.php`
- `cacheDirectory`: `.phpunit.cache`
- `Test Paketleri`:
    - `Unit`: `tests/Unit`
    - `Feature`: `tests/Feature`
- `Ortam Değişkenleri`:
    - `APP_ENV`: testing
    - `DB_DRIVER`: sqlite

## Kaynak Referansları
- `phpunit.xml:1-33`
