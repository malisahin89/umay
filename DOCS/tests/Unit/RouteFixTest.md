# Dosya Raporu: tests/Unit/RouteFixTest.php

## Amaç
Kaynak rotalama ve dağıtımı (dispatch) için regresyon testleri.

## Genel Bakış
`Core\Route` kaynak kayıt filtrelerini (`only`, `except`) ve rota dağıtımının aynı `Request` örneğini yeniden kullandığını doğrular.

## Dosya Konumu
`tests/Unit/RouteFixTest.php`

## İsim Uzayı
`Tests\Unit`

## Sınıflar
- `class RouteFixTest extends Tests\TestCase`

## Test Edilen Konu
- `Core\Route`, `Core\ResourceRegistrar`, `Core\Request`

## Test Metotları
- `test_resource_only` — `:29`
- `test_resource_except` — `:41`
- `test_route_dispatch_uses_same_request_instance` — `:56`

## Çapraz Referanslar
- **Test Eder:** `Core\Route` (bkz. `DOCS/core/Route.md`), `Core\ResourceRegistrar` (bkz. `DOCS/core/ResourceRegistrar.md`)

## Kaynak Referansları
- `tests/Unit/RouteFixTest.php:1-80`
