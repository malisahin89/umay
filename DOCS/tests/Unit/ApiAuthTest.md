# Dosya Raporu: tests/Unit/ApiAuthTest.php

## Amaç
Bearer-token API kimlik doğrulama için birim (unit) testler.

## Genel Bakış
`ApiAuth` ara yazılımını ve kişisel erişim token'ı akışını doğrular: geçerli token'lar sahibini kimliklendirir, token'lar özetlenmiş (hashed) olarak saklanır, eksik/geçersiz/süresi dolmuş token'lar 401 ile reddedilir, yetenek (scope) kontrolleri uygulanır (wildcard dahil) ve `last_used_at` kaydedilir.

## Dosya Konumu
`tests/Unit/ApiAuthTest.php`

## İsim Uzayı
`Tests\Unit`

## Sınıflar
- `class ApiAuthTest extends Tests\TestCase`

## Test Edilen Konu
- `Core\Middleware\ApiAuth`, `Core\Auth\HasApiTokens`, `Core\Auth\PersonalAccessToken`

## Test Metotları
- `test_valid_token_authenticates_the_owning_user` — `:81`
- `test_token_is_stored_hashed_not_in_plaintext` — `:94`
- `test_missing_token_is_rejected_with_401` — `:104`
- `test_invalid_token_is_rejected_with_401` — `:111`
- `test_ability_protected_route_allows_matching_token` — `:119`
- `test_ability_protected_route_rejects_token_without_ability` — `:131`
- `test_wildcard_token_grants_every_ability` — `:141`
- `test_last_used_at_is_recorded` — `:151`
- `test_unexpired_token_is_accepted` — `:162`
- `test_expired_token_is_rejected_with_401` — `:172`

## Çapraz Referanslar
- **Test Eder:** `Core\Middleware\ApiAuth` (bkz. `DOCS/core/Middleware/ApiAuth.md`), `Core\Auth\HasApiTokens` (bkz. `DOCS/core/Auth/HasApiTokens.md`), `Core\Auth\PersonalAccessToken` (bkz. `DOCS/core/Auth/PersonalAccessToken.md`)

## Kaynak Referansları
- `tests/Unit/ApiAuthTest.php:1-181`
