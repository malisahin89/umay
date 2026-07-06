# File Report: tests/Unit/CsrfTest.php

## Purpose
Unit tests for CSRF token generation and validation.

## Overview
Verifies `Core\Csrf`: token generation is stable within a session, tokens are 64-char hex, correct tokens validate, and wrong/empty/null/integer/array tokens are rejected. Also checks session storage of the token.

## File Location
`tests/Unit/CsrfTest.php`

## Namespace
`Tests\Unit`

## Classes
- `class CsrfTest extends Tests\TestCase`

## Subject Under Test
- `Core\Csrf`

## Test Methods
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

## Cross References
- **Tests:** `Core\Csrf` (see `DOCS/core/Csrf.md`)

## Source References
- `tests/Unit/CsrfTest.php:1-99`
