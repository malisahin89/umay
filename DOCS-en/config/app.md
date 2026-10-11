# File Report: config/app.php

## Purpose
General application configuration.

## Overview
Defines core application settings such as name, version, URL, controller namespace, environment, timezone, and Facade aliases.

## File Location
`config/app.php`

## Configuration
- `name`: Defined via `APP_NAME` in `.env` (default: 'Umay').
- `version`: '1.0.0'
- `url`: Defined via `APP_URL` in `.env` (default: 'http://localhost').
- `controller_namespace`: Defined via `CONTROLLER_NAMESPACE` (default: 'App\\Controllers\\').
- `env`: Defined via `APP_ENV` (default: 'local').
- `trusted_proxies`: Array of IPs from `TRUSTED_PROXIES` (default: '127.0.0.1,::1').
- `debug`: Boolean from `APP_DEBUG`.
- `timezone`: Defined via `APP_TIMEZONE` (default: 'Europe/Istanbul').
- `key`: Defined via `APP_KEY`.

## Facade Aliases
Short global names registered by `FacadeServiceProvider`:
- `Cache` -> `Core\Facades\Cache`
- `Auth` -> `Core\Facades\Auth`
- `Log` -> `Core\Facades\Log`
- `DB` -> `Core\Facades\DB`
- `Event` -> `Core\Facades\Event`
- `Validator` -> `Core\Facades\Validator`
- `RateLimiter` -> `Core\Facades\RateLimiter`

## Source References
- `config/app.php:1-117`
