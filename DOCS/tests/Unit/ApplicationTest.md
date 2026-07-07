# Dosya Raporu: tests/Unit/ApplicationTest.php

## Amaç
Uygulama konteyneri/yaşam döngüsü orkestratörü için birim (unit) testler.

## Genel Bakış
`Core\Application`'ın singleton davranışını, `Container`'a olan delegasyonunu (`make`, `singleton`), sağlayıcı kaydını, tek seferlik başlatma (boot) garantisini ve istek yakalama bağlamasını doğrular.

## Dosya Konumu
`tests/Unit/ApplicationTest.php`

## İsim Uzayı
`Tests\Unit`

## Sınıflar
- `class ApplicationTest extends Tests\TestCase`

## Test Edilen Konu
- `Core\Application`

## Test Metotları
- `test_get_instance_returns_same_instance` — `:23`
- `test_container_returns_container_instance` — `:33`
- `test_make_delegates_to_container` — `:43`
- `test_singleton_delegates_to_container` — `:54`
- `test_register_calls_provider_register` — `:71`
- `test_boot_runs_only_once` — `:82`
- `test_capture_request_binds_to_container` — `:96`

## Çapraz Referanslar
- **Test Eder:** `Core\Application` (bkz. `DOCS/core/Application.md`), `Core\Container` (bkz. `DOCS/core/Container.md`)

## Kaynak Referansları
- `tests/Unit/ApplicationTest.php:1-109`
