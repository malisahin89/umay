# Dosya Raporu: tests/Unit/FacadeServiceProviderTest.php

## Amaç
Facade servis sağlayıcı bağlamaları için birim (unit) testler.

## Genel Bakış
`Core\Providers\FacadeServiceProvider`'ın önbellek, kimlik doğrulama, günlükleyici, rota, veritabanı, dağıtıcı, görünüm ve hız sınırlayıcı için singleton'lar kaydettiğini, validator proxy'sini bağladığını ve validator proxy'sinin bir `Validator` örneği oluşturduğunu doğrular.

## Dosya Konumu
`tests/Unit/FacadeServiceProviderTest.php`

## İsim Uzayı
`Tests\Unit`

## Sınıflar
- `class FacadeServiceProviderTest extends Tests\TestCase`

## Test Edilen Konu
- `Core\Providers\FacadeServiceProvider`

## Test Metotları
- `test_register_binds_cache_singleton` — `:38`
- `test_register_binds_auth_singleton` — `:50`
- `test_register_binds_logger_singleton` — `:62`
- `test_register_binds_route_singleton` — `:70`
- `test_register_binds_database_singleton` — `:78`
- `test_register_binds_dispatcher_singleton` — `:86`
- `test_register_binds_view_singleton` — `:94`
- `test_register_binds_rate_limiter_singleton` — `:102`
- `test_register_binds_validator_proxy` — `:112`
- `test_validator_proxy_creates_validator_instance` — `:124`

## Çapraz Referanslar
- **Test Eder:** `Core\Providers\FacadeServiceProvider` (bkz. `DOCS/core/Providers/FacadeServiceProvider.md`)

## Kaynak Referansları
- `tests/Unit/FacadeServiceProviderTest.php:1-135`
