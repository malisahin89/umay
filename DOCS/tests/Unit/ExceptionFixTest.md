# Dosya Raporu: tests/Unit/ExceptionFixTest.php

## Amaç
İstisna (exception) sınıf ilişkileri ve çözünürlüğü için regresyon testleri.

## Genel Bakış
`RedirectException`'ın `TerminateException`'ı genişlettiğini ve `ExceptionHandler`'ın konteynerden çözülebileceğini doğrular.

## Dosya Konumu
`tests/Unit/ExceptionFixTest.php`

## İsim Uzayı
`Tests\Unit`

## Sınıflar
- `class ExceptionFixTest extends Tests\TestCase`

## Test Edilen Konu
- `Core\RedirectException`, `Core\TerminateException`, `Core\ExceptionHandler`

## Test Metotları
- `test_redirect_exception_extends_terminate_exception` — `:25`
- `test_exception_handler_resolves_from_container` — `:33`

## Çapraz Referanslar
- **Test Eder:** `Core\RedirectException` (bkz. `DOCS/core/RedirectException.md`), `Core\TerminateException` (bkz. `DOCS/core/TerminateException.md`), `Core\ExceptionHandler` (bkz. `DOCS/core/ExceptionHandler.md`)

## Kaynak Referansları
- `tests/Unit/ExceptionFixTest.php:1-61`
