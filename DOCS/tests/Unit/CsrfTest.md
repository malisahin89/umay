# Dosya Raporu: tests/Unit/CsrfTest.php

## Amaç
CSRF token oluşturma ve doğrulama için birim (unit) testler.

## Genel Bakış
`Core\Csrf`'i doğrular: token oluşturma bir oturum içinde stabildir, token'lar 64 karakterli hex formatındadır, doğru token'lar doğrulanır ve yanlış/boş/null/tamsayı/dizi token'lar reddedilir. Ayrıca token'ın oturumda saklandığını kontrol eder.

## Dosya Konumu
`tests/Unit/CsrfTest.php`

## İsim Uzayı
`Tests\Unit`

## Sınıflar
- `class CsrfTest extends Tests\TestCase`

## Test Edilen Konu
- `Core\Csrf`

## Test Metotları
- `test_generate_creates_token` — `:20`
- `test_generate_returns_same_token_in_same_session` — `:28`
- `test_token_is_64_characters_hex` — `:36`
- `test_check_validates_correct_token` — `:46`
- `test_check_rejects_wrong_token` — `:52`
- `test_check_rejects_empty_string` — `:58`
- `test_check_rejects_null` — `:64`
- `test_check_rejects_integer` — `:70`
- `test_check_rejects_array` — `:76`
- `test_token_stored_in_session` — `:84`
- `test_check_fails_when_no_session_token` — `:92`

## Çapraz Referanslar
- **Test Eder:** `Core\Csrf` (bkz. `DOCS/core/Csrf.md`)

## Kaynak Referansları
- `tests/Unit/CsrfTest.php:1-99`
