# Dosya Raporu: tests/Unit/HelpersTest.php

## Amaç
Global yardımcı fonksiyonlar için birim (unit) testler.

## Genel Bakış
`core/helpers.php`'de tanımlanan yardımcıları doğrular: dize yardımcıları (`str_slug`, `str_limit`), tarih yardımcıları (`now`, `today`), form/HTML yardımcıları (`method_field`, `asset`, `old`, `csrf`, `csrf_token`), anlık mesajlaşma (`flash`), IP yardımcıları (`ip_in_cidr`, `is_cloudflare_ip`, `get_real_ip` güvenilir-proxy yönetimi dahil), doğrulama (`validate`), `abort` ve `config`.

## Dosya Konumu
`tests/Unit/HelpersTest.php`

## İsim Uzayı
`Tests\Unit`

## Sınıflar
- `class HelpersTest extends Tests\TestCase`

## Test Edilen Konu
- `core/helpers.php` global fonksiyonları.

## Test Metotları
`str_slug` (`:20-40`), `str_limit` (`:47-57`), `now`/`today` (`:64-82`), `method_field` (`:90-104`), `asset` (`:112-117`), `flash` (`:124-135`), `old` (`:144-163`), `ip_in_cidr` (`:172-192`), `is_cloudflare_ip` (`:199-209`), `get_real_ip` (`:216-246`), `validate` (`:256-262`), `abort` (`:271`), `csrf`/`csrf_token` (`:279-286`) ve `config` (`:294-300`) metotlarını kapsar.

## Çapraz Referanslar
- **Test Eder:** `core/helpers.php` (bkz. `DOCS/core/helpers.md`)

## Kaynak Referansları
- `tests/Unit/HelpersTest.php:1-304`
