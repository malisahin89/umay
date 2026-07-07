# Dosya Raporu: tests/Unit/ResponseBuilderTest.php

## Amaç
Yanıt oluşturucu (response builder) için birim (unit) testler.

## Genel Bakış
`Core\ResponseBuilder`'ı doğrular: kurucu gövde/durum, `json` (içerik türü, gövde, durum), `html` gövdesi, akıcı `header`/`withHeaders`/`status` metotları, zincirlenmiş akıcı API ve dosya eksik olduğunda `download`'ın hata fırlatması.

## Dosya Konumu
`tests/Unit/ResponseBuilderTest.php`

## İsim Uzayı
`Tests\Unit`

## Sınıflar
- `class ResponseBuilderTest extends Tests\TestCase`

## Test Edilen Konu
- `Core\ResponseBuilder`

## Test Metotları
- `test_constructor_accepts_body_and_status` — `:20`
- `test_json_sets_content_type_and_body` — `:30`
- `test_json_with_status_code` — `:39`
- `test_html_sets_body` — `:49`
- `test_header_returns_fluent_instance` — `:59`
- `test_with_headers_merges_headers` — `:67`
- `test_status_method_returns_fluent` — `:80`
- `test_chained_fluent_api` — `:90`
- `test_download_throws_when_file_not_found` — `:103`

## Çapraz Referanslar
- **Test Eder:** `Core\ResponseBuilder` (bkz. `DOCS/core/ResponseBuilder.md`)

## Kaynak Referansları
- `tests/Unit/ResponseBuilderTest.php:1-110`
