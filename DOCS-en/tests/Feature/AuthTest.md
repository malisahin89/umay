# File Report: tests/Feature/AuthTest.php

## Purpose
Feature tests for session-based authentication behavior.

## Overview
Exercises `Core\Auth` state (`check`, `guest`, `id`, `user`) against session presence, and validates login input rules via the `validate()` helper.

## File Location
`tests/Feature/AuthTest.php`

## Namespace
`Tests\Feature`

## Classes
- `class AuthTest extends Tests\TestCase`

## Subject Under Test
- `Core\Auth` (resolved from the container)
- `validate()` helper (`core/helpers.php`)

## Test Methods
- `test_auth_check_returns_false_when_no_session` — `:21`
- `test_auth_guest_returns_true_when_not_logged_in` — `:26`
- `test_auth_id_returns_null_when_not_logged_in` — `:31`
- `test_auth_user_returns_null_when_no_session` — `:36`
- `test_session_user_id_makes_auth_check_true` — `:41`
- `test_login_validation_requires_email_and_password` — `:49`
- `test_login_validation_passes_with_valid_data` — `:57`

## Cross References
- **Tests:** `Core\Auth` (see `DOCS/core/Auth.md`), `validate()` (see `DOCS/core/helpers.md`)
- **Extends:** `Tests\TestCase`

## Source References
- `tests/Feature/AuthTest.php:1-65`
