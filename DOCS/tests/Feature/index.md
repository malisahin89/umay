# Dizin Raporu: tests/Feature

## Amaç
Çerçeve alt sistemlerini daha üst düzey davranışlar aracılığıyla test eden özellik (feature) düzeyinde testler.

## Alt Dizinler
- Yok.

## Kaynak Dosyalar
- `AuthTest.php` — oturum tabanlı kimlik doğrulama ve giriş doğrulaması (bkz. `AuthTest.md`)

## Genel Giriş Noktaları
- PHPUnit tarafından yürütülür (bkz. `DOCS/phpunit.xml.md`).

## Dahili Bağımlılıklar
- `Tests\TestCase`'i genişletir; `Core\Auth` ve `validate()` yardımcısını test eder.

## Harici Bağımlılıklar
- PHPUnit.

## Çapraz Referanslar
- **Taban Sınıf:** `Tests\TestCase` (bkz. `DOCS/tests/TestCase.md`)

## Kaynak Referansları
- `tests/Feature/`
