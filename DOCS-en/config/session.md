# File Report: config/session.php

## Purpose
Session configuration.

## Overview
Defines session lifetime, cookie name, and security settings (Secure, HttpOnly, SameSite).

## File Location
`config/session.php`

## Configuration
- `lifetime`: Lifetime in minutes from `SESSION_LIFETIME` (default: 30).
- `cookie`: Session cookie name from `SESSION_COOKIE` (default: 'umay_session').
- `secure`: Boolean from `SESSION_SECURE` or detected via `$_SERVER['HTTPS']`.
- `http_only`: `true`
- `same_site`: From `SESSION_SAME_SITE` (default: 'Strict').

## Source References
- `config/session.php:1-28`
