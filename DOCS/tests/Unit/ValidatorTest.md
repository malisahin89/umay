# Dosya Raporu: tests/Unit/ValidatorTest.php

## Amaç
Temel doğrulama kuralları için birim (unit) testler.

## Genel Bakış
`Core\Validator` temellerini doğrular: `required`, `email`, `min`, `confirmed`, özel hata mesajları, çoklu kural hata koleksiyonu, `in` ve `numeric`.

## Dosya Konumu
`tests/Unit/ValidatorTest.php`

## İsim Uzayı
`Tests\Unit`

## Sınıflar
- `class ValidatorTest extends Tests\TestCase`

## Test Edilen Konu
- `Core\Validator`

## Test Metotları
- `test_required_rule_fails_on_empty` — `:12`
- `test_required_rule_passes_with_value` — `:19`
- `test_email_rule_fails_on_invalid` — `:26`
- `test_email_rule_passes_on_valid` — `:32`
- `test_min_rule_fails_when_too_short` — `:38`
- `test_min_rule_passes_when_long_enough` — `:44`
- `test_confirmed_rule_fails_when_mismatch` — `:50`
- `test_confirmed_rule_passes_when_match` — `:59`
- `test_custom_error_messages` — `:68`
- `test_multiple_rules_collect_all_errors` — `:79`
- `test_in_rule` — `:90`
- `test_numeric_rule` — `:99`

## Çapraz Referanslar
- **Test Eder:** `Core\Validator` (bkz. `DOCS/core/Validator.md`)

## Kaynak Referansları
- `tests/Unit/ValidatorTest.php:1-107`
