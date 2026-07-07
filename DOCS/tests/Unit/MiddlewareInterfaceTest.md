# Dosya Raporu: tests/Unit/MiddlewareInterfaceTest.php

## Amaç
Tüm ara yazılımların ara yazılım sözleşmesine uyduğunu sağlayan tutarlılık testi.

## Genel Bakış
Her ara yazılım sınıfının `Core\Contracts\MiddlewareInterface` arayüzünü uyguladığını doğrular.

## Dosya Konumu
`tests/Unit/MiddlewareInterfaceTest.php`

## İsim Uzayı
`Tests\Unit`

## Sınıflar
- `class MiddlewareInterfaceTest extends Tests\TestCase`

## Test Edilen Konu
- `Core\Contracts\MiddlewareInterface` ve uygulayan tüm ara yazılım sınıfları.

## Test Metotları
- `test_all_middlewares_implement_interface` — `:17`

## Çapraz Referanslar
- **Test Eder:** `Core\Contracts\MiddlewareInterface` (bkz. `DOCS/core/Contracts/MiddlewareInterface.md`) ve `Core\Middleware\*` (bkz. `DOCS/core/Middleware/index.md`)

## Kaynak Referansları
- `tests/Unit/MiddlewareInterfaceTest.php:1-43`
