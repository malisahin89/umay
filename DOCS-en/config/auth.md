# File Report: config/auth.php

## Purpose
Authentication configuration.

## Overview
Configures which user model and authentication provider the framework should use. It uses a pluggable system where the core (`Core\Auth`) is decoupled from the application implementation.

## File Location
`config/auth.php`

## Configuration
- `default`: The active provider, defined via `AUTH_PROVIDER` in `.env` (default: 'eloquent').
- `login_path`: The path the `auth` middleware redirects guests to (`AUTH_LOGIN_PATH`, default: '/login').
- `providers`:
    - `eloquent`: Uses `Core\Auth\EloquentUserProvider` with the `App\Models\User` model.

## Source References
- `config/auth.php:1-48`
