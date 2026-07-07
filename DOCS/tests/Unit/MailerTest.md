# Dosya Raporu: tests/Unit/MailerTest.php

## Amaç
Mailer ve onun günlük (log) taşıma katmanı için birim (unit) testler.

## Genel Bakış
`Core\Mail\Mailer`'ı doğrular: dizi ve isim-çift formları dahil akıcı alıcı metotları (`to`, `cc`, `bcc`), `LogTransport`'ın taşıma sözleşmesini uyguladığı, günlük taşıma yoluyla gönderimin bir günlük kaydı yazdığı ve alıcı değerlerindeki CRLF'lerin kaldırıldığı (başlık enjeksiyonu savunması).

## Dosya Konumu
`tests/Unit/MailerTest.php`

## İsim Uzayı
`Tests\Unit`

## Sınıflar
- `class MailerTest extends Tests\TestCase`

## Test Edilen Konu
- `Core\Mail\Mailer`, `Core\Mail\Transport\LogTransport`

## Test Metotları
- `test_to_returns_mailer_instance` — `:47`
- `test_to_accepts_array_of_recipients` — `:52`
- `test_to_with_name_stores_array_pair` — `:58`
- `test_cc_is_chainable` — `:64`
- `test_bcc_accepts_array` — `:70`
- `test_log_transport_implements_contract` — `:78`
- `test_send_uses_log_transport_and_writes_log` — `:83`
- `test_recipient_crlf_is_stripped` — `:101`

## Çapraz Referanslar
- **Test Eder:** `Core\Mail\Mailer` (bkz. `DOCS/core/Mail/Mailer.md`), `Core\Mail\Transport\LogTransport` (bkz. `DOCS/core/Mail/Transport/LogTransport.md`)

## Kaynak Referansları
- `tests/Unit/MailerTest.php:1-114`
