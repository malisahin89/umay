# File Report: tests/Unit/ApiAuthTest.php

## Purpose
Unit tests for Bearer-token API authentication.

## Overview
Verifies the `ApiAuth` middleware and personal access token flow: valid tokens authenticate their owner, tokens are stored hashed, missing/invalid/expired tokens are rejected with 401, ability (scope) checks are enforced (including wildcard), and `last_used_at` is recorded.

## File Location
`tests/Unit/ApiAuthTest.php`

## Namespace
`Tests\Unit`

## Classes
- `class ApiAuthTest extends Tests\TestCase`

## Subject Under Test
- `Core\Middleware\ApiAuth`, `Core\Auth\HasApiTokens`, `Core\Auth\PersonalAccessToken`

## Test Methods
- `test_valid_token_authenticates_the_owning_user` — `:81`
- `test_token_is_stored_hashed_not_in_plaintext` — `:94`
- `test_missing_token_is_rejected_with_401` — `:104`
- `test_invalid_token_is_rejected_with_401` — `:111`
- `test_ability_protected_route_allows_matching_token` — `:119`
- `test_ability_protected_route_rejects_token_without_ability` — `:131`
- `test_wildcard_token_grants_every_ability` — `:141`
- `test_last_used_at_is_recorded` — `:151`
- `test_unexpired_token_is_accepted` — `:162`
- `test_expired_token_is_rejected_with_401` — `:172`

## Cross References
- **Tests:** `Core\Middleware\ApiAuth` (see `DOCS/core/Middleware/ApiAuth.md`), `Core\Auth\HasApiTokens` (see `DOCS/core/Auth/HasApiTokens.md`), `Core\Auth\PersonalAccessToken` (see `DOCS/core/Auth/PersonalAccessToken.md`)

## Source References
- `tests/Unit/ApiAuthTest.php:1-181`
