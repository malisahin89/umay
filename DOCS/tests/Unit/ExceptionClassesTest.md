# Dosya Raporu: tests/Unit/ExceptionClassesTest.php

## Amaç
İstisna (exception) sınıfları için birim (unit) testler.

## Genel Bakış
`ContainerException` ve `EntryNotFoundException`'ın PSR-11 istisna arayüzlerini uyguladığını ve mesajlar taşıdığını, ayrıca `HttpException`'ın doğru varsayılanlarla (403 / 500) bir durum kodu sunduğunu doğrular.

## Dosya Konumu
`tests/Unit/ExceptionClassesTest.php`

## İsim Uzayı
`Tests\Unit`

## Sınıflar
- `class ExceptionClassesTest extends Tests\TestCase`

## Test Edilen Konu
- `Core\Exceptions\ContainerException`, `Core\Exceptions\EntryNotFoundException`, `Core\Exceptions\HttpException`

## Test Metotları
- `test_container_exception_implements_psr11` — `:24`
- `test_container_exception_has_message` — `:30`
- `test_entry_not_found_implements_psr11` — `:38`
- `test_entry_not_found_has_message` — `:44`
- `test_http_exception_has_status_code` — `:52`
- `test_http_exception_default_403` — `:60`
- `test_http_exception_default_500` — `:67`

## Çapraz Referanslar
- **Test Eder:** `Core\Exceptions\ContainerException` (bkz. `DOCS/core/Exceptions/ContainerException.md`), `Core\Exceptions\EntryNotFoundException` (bkz. `DOCS/core/Exceptions/EntryNotFoundException.md`), `Core\Exceptions\HttpException` (bkz. `DOCS/core/Exceptions/HttpException.md`)

## Kaynak Referansları
- `tests/Unit/ExceptionClassesTest.php:1-74`
