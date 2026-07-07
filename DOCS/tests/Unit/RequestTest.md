# Dosya Raporu: tests/Unit/RequestTest.php

## Amaç
HTTP istek nesnesi için birim (unit) testler.

## Genel Bakış
`Core\Request`'i doğrular: Authorization başlığından Bearer token ayıklama, `expectsJson` algılama (bearer token / Accept başlığı), POST ve GET verilerine erişim, `only` alan filtreleme, `filled` semantiği ve HTTP metodu algılama.

## Dosya Konumu
`tests/Unit/RequestTest.php`

## İsim Uzayı
`Tests\Unit`

## Sınıflar
- `class RequestTest extends Tests\TestCase`

## Test Edilen Konu
- `Core\Request`

## Test Metotları
- `test_bearer_token_extracted_from_authorization_header` — `:11`
- `test_bearer_token_returns_null_when_no_header` — `:17`
- `test_bearer_token_returns_null_when_not_bearer_scheme` — `:24`
- `test_expects_json_true_when_bearer_token_present` — `:30`
- `test_expects_json_true_when_accept_json` — `:36`
- `test_post_data_accessible` — `:42`
- `test_get_data_accessible` — `:49`
- `test_only_filters_fields` — `:56`
- `test_filled_returns_false_for_empty_string` — `:65`
- `test_filled_returns_true_for_non_empty` — `:71`
- `test_method_detection` — `:77`

## Çapraz Referanslar
- **Test Eder:** `Core\Request` (bkz. `DOCS/core/Request.md`)

## Kaynak Referansları
- `tests/Unit/RequestTest.php:1-84`
