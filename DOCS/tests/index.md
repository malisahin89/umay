# Dizin Raporu: tests

## Amaç
PHPUnit altında çalışan, çerçeve için otomatik test paketi.

## Alt Dizinler
- `Feature/` — özellik düzeyinde testler (bkz. `Feature/index.md`)
- `Unit/` — bileşen birim testleri (bkz. `Unit/index.md`)

## Kaynak Dosyalar
- `bootstrap.php` — PHPUnit bootstrap: env, veritabanı, yardımcılar (bkz. `bootstrap.md`)
- `TestCase.php` — çerçeve farkındalığına sahip kurulum ve yardımcılar içeren temel test durumu (bkz. `TestCase.md`)

## Genel Giriş Noktaları
- `tests/bootstrap.php` (`phpunit.xml` tarafından referans verilir).

## Dahili Bağımlılıklar
- `Core\Database`, `core/helpers.php`, `Core\Auth`, `Core\Container`, `Core\Events\Dispatcher`, `Core\Request`, `App\Models\User`.

## Harici Bağımlılıklar
- PHPUnit, `vlucas/phpdotenv`.

## Çapraz Referanslar
- **Yapılandırılan:** `phpunit.xml` (bkz. `DOCS/phpunit.xml.md`)
- **Başlatılan:** `Core\Database`, `core/helpers.php`

## Kaynak Referansları
- `tests/`
- `tests/bootstrap.php:1-32`, `tests/TestCase.php:1-166`
