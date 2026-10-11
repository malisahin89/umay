# Dosya Raporu: tests/Unit/ViewTest.php

## Amaç
Görünüm motoru (view engine) entegrasyonu için birim (unit) testler.

## Genel Bakış
`Core\View`'u doğrular: motor, görünüm örneği başına bir singleton olan bir Plates örneği döndürür ve şablon yardımcı fonksiyonlarını (`csrf`, `csrf_token`, escape, `old`, `route`, `flash`, `dd`) kaydeder.

## Dosya Konumu
`tests/Unit/ViewTest.php`

## İsim Uzayı
`Tests\Unit`

## Sınıflar
- `class ViewTest extends Tests\TestCase`

## Test Edilen Konu
- `Core\View`

## Test Metotları
- `test_engine_returns_plates_instance` — `:35`
- `test_engine_is_singleton_per_instance` — `:44`
- `test_engine_has_csrf_function` — `:57`
- `test_engine_has_csrf_token_function` — `:66`
- `test_engine_has_escape_function` — `:75`
- `test_engine_has_old_function` — `:85`
- `test_engine_has_route_function` — `:94`
- `test_engine_has_flash_function` — `:103`
- `test_engine_has_dd_function` — `:112`

## Çapraz Referanslar
- **Test Eder:** `Core\View` (bkz. `DOCS/core/View.md`)

## Kaynak Referansları
- `tests/Unit/ViewTest.php:1-120`
