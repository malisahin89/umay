# File Report: tests/Unit/ValidatorTest.php

## Purpose
Unit tests for core validation rules.

## Overview
Verifies `Core\Validator` fundamentals: `required`, `email`, `min`, `confirmed`, custom error messages, multi-rule error collection, `in`, and `numeric`.

## File Location
`tests/Unit/ValidatorTest.php`

## Namespace
`Tests\Unit`

## Classes
- `class ValidatorTest extends Tests\TestCase`

## Subject Under Test
- `Core\Validator`

## Test Methods
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

## Cross References
- **Tests:** `Core\Validator` (see `DOCS/core/Validator.md`)

## Source References
- `tests/Unit/ValidatorTest.php:1-107`
