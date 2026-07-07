# Dosya Raporu: tests/Feature/AuthTest.php

## Amaç
Oturum tabanlı kimlik doğrulama davranışı için özellik (feature) testleri.

## Genel Bakış
`Core\Auth` durumunu (`check`, `guest`, `id`, `user`) oturum varlığına karşı test eder ve `validate()` yardımcısı aracılığıyla giriş girdi kurallarını doğrular.

## Dosya Konumu
`tests/Feature/AuthTest.php`

## İsim Uzayı
`Tests\Feature`

## Sınıflar
- `class AuthTest extends Tests\TestCase`

## Test Edilen Konu
- `Core\Auth` (konteynerden çözümlenir)
- `validate()` yardımcısı (`core/helpers.php`)

## Test Metotları
- `test_auth_check_returns_false_when_no_session` — `:21`
- `test_auth_guest_returns_true_when_not_logged_in` — `:26`
- `test_auth_id_returns_null_when_not_logged_in` — `:31`
- `test_auth_user_returns_null_when_no_session` — `:36`
- `test_session_user_id_makes_auth_check_true` — `:41`
- `test_login_validation_requires_email_and_password` — `:49`
- `test_login_validation_passes_with_valid_data` — `:57`

## Çapraz Referanslar
- **Test Eder:** `Core\Auth` (bkz. `DOCS/core/Auth.md`), `validate()` (bkz. `DOCS/core/helpers.md`)
- **Genişletir:** `Tests\TestCase`

## Kaynak Referansları
- `tests/Feature/AuthTest.php:1-65`
