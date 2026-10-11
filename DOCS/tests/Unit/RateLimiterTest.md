# Dosya Raporu: tests/Unit/RateLimiterTest.php

## Amaç
Hız sınırlayıcı (rate limiter) için birim (unit) testler.

## Genel Bakış
`Core\RateLimiter`'ı doğrular: başlangıç durumu denemelere izin verir, `hit` sayacı artırır, sınır `tooManyAttempts`'i tetikler, `clear` sıfırlar, `remaining` vuruşlarla azalır ve adlandırılmış sınırlayıcılar kaydedilebilir.

## Dosya Konumu
`tests/Unit/RateLimiterTest.php`

## İsim Uzayı
`Tests\Unit`

## Sınıflar
- `class RateLimiterTest extends Tests\TestCase`

## Test Edilen Konu
- `Core\RateLimiter`

## Test Metotları
- `test_not_too_many_attempts_initially` — `:26`
- `test_hit_increments_counter` — `:31`
- `test_too_many_attempts_after_limit` — `:38`
- `test_clear_resets_counter` — `:46`
- `test_remaining_decreases_with_hits` — `:55`
- `test_named_limiter_registration` — `:62`

## Çapraz Referanslar
- **Test Eder:** `Core\RateLimiter` (bkz. `DOCS/core/RateLimiter.md`)

## Kaynak Referansları
- `tests/Unit/RateLimiterTest.php:1-69`
