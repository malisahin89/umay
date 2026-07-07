# Dosya Raporu: tests/Unit/RequestFixTest.php

## Amaç
Form isteği durumu ve JSON gövdesi ayrıştırması için regresyon testleri.

## Genel Bakış
Bir `FormRequest` alt sınıfının üst istek durumunu yeniden kullandığını ve JSON istek gövdelerinin ayrıştırıldığını doğrular. `Core\FormRequest`'i genişleten bir `TestFormRequest` fixture'ı tanımlar.

## Dosya Konumu
`tests/Unit/RequestFixTest.php`

## İsim Uzayı
`Tests\Unit`

## Sınıflar
- `class TestFormRequest extends Core\FormRequest` (`:11`)
- `class RequestFixTest extends Tests\TestCase` (`:19`)

## Test Edilen Konu
- `Core\FormRequest`, `Core\Request`

## Test Metotları
- `test_form_request_uses_parent_state` — `:21`
- `test_json_body_parsing` — `:48`

## Çapraz Referanslar
- **Test Eder:** `Core\FormRequest` (bkz. `DOCS/core/FormRequest.md`), `Core\Request` (bkz. `DOCS/core/Request.md`)

## Kaynak Referansları
- `tests/Unit/RequestFixTest.php:1-69`
