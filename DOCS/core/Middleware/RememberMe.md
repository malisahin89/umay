# File Report: core/Middleware/RememberMe.php

## Purpose
Session restoration middleware using "remember me" cookies.

## Overview
Checks for a `remember_me` cookie on incoming requests. If found and valid, it automatically logs the user back in, providing a seamless experience across browser sessions.

## File Location
`core/Middleware/RememberMe.php`

## Namespace
`Core\Middleware`

## Classes
- `class RememberMe implements MiddlewareInterface`

## Methods
- `handle(Request $request, \Closure $next): mixed`:
    1. If the user is already logged in, it proceeds to the next handler.
    2. If not, it looks for the `remember_me` cookie.
    3. If found, it uses the `UserProvider::retrieveByToken()` method to authenticate the user.
    4. If successful, it calls `Auth::login($user, true)` to restore the session.

## Dependencies
- `Core\Contracts\MiddlewareInterface` (Implements)
- `Core\Request` (Uses)
- `Core\Auth` (Uses)

## Source References
- `core/Middleware/RememberMe.php:1-125`
