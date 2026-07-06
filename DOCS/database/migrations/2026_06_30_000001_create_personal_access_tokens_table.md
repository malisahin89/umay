# File Report: database/migrations/2026_06_30_000001_create_personal_access_tokens_table.php

## Purpose
Migration to create the `personal_access_tokens` table for API authentication.

## Overview
Creates the backing store for Bearer tokens. Tokens are stored as sha256 hex digests.

## File Location
`database/migrations/2026_06_30_000001_create_personal_access_tokens_table.php`

## Implementation
- `up()`: Creates the table with `id`, `tokenable` (morphs), `name`, `token` (unique), `abilities` (text/JSON), `last_used_at`, `expires_at`, and timestamps.
- `down()`: Drops the table.

## Source References
- `database/migrations/2026_06_30_000001_create_personal_access_tokens_table.php:1-40`
