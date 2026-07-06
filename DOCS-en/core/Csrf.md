# File Report: core/Csrf.php

## Purpose
Cross-Site Request Forgery (CSRF) protection.

## Overview
Generates and verifies a secret CSRF token stored in the session to ensure that state-changing requests (POST, PUT, etc.) originate from the authenticated user.

## File Location
`core/Csrf.php`

## Namespace
`Core`

## Classes
- `class Csrf`

## Methods
- `generate(): string`: Generates a random 32-byte token, stores it in `$_SESSION['csrf_token']`, and returns it.
- `check(mixed $token): bool`: Compares the provided token with the one in the session using `hash_equals` to prevent timing attacks.

## Source References
- `core/Csrf.php:1-41`
