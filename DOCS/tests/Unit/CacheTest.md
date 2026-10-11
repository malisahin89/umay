# Dosya Raporu: tests/Unit/CacheTest.php

## Amaç
Dosya tabanlı önbellek için birim (unit) testler.

## Genel Bakış
`Core\Cache`'i doğrular: skaler, dizi, tamsayı, boolean değerler için set/get; `has`, `forget`, `flush`, `pull`, `remember`; eksik anahtarlar için varsayılan işlem; ve kurcalanmış, hatalı veya süresi dolmuş önbellek dosyalarına karşı dayanıklılık. Ayrıca yapılandırma odaklı varsayılan TTL ve ön ek tabanlı dosya adlarını doğrular.

## Dosya Konumu
`tests/Unit/CacheTest.php`

## İsim Uzayı
`Tests\Unit`

## Sınıflar
- `class CacheTest extends Tests\TestCase`

## Test Edilen Konu
- `Core\Cache`

## Test Metotları
- `test_set_and_get_returns_stored_value` — `:49`
- `test_get_returns_default_when_key_not_exists` — `:57`
- `test_get_returns_null_default_when_key_not_exists` — `:63`
- `test_cache_stores_array_values` — `:71`
- `test_cache_stores_integer_values` — `:79`
- `test_cache_stores_boolean_values` — `:85`
- `test_has_returns_true_for_existing_key` — `:93`
- `test_has_returns_false_for_missing_key` — `:99`
- `test_forget_removes_key` — `:106`
- `test_flush_removes_all_keys` — `:117`
- `test_pull_returns_value_and_removes_key` — `:132`
- `test_pull_returns_default_when_key_not_exists` — `:141`
- `test_remember_stores_and_returns_callback_value` — `:149`
- `test_tampered_cache_file_returns_default` — `:174`
- `test_invalid_cache_format_returns_default` — `:192`
- `test_expired_cache_returns_default` — `:206`
- `test_set_uses_config_default_ttl_when_omitted` — `:226`
- `test_prefix_applied_to_cache_filename` — `:239`

## Çapraz Referanslar
- **Test Eder:** `Core\Cache` (bkz. `DOCS/core/Cache.md`)

## Kaynak Referansları
- `tests/Unit/CacheTest.php:1-251`
