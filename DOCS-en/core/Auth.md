# File Report: core/Auth.php

## Purpose
Request-based cached authentication guard.

## Overview
Manages user authentication, session-based login/logout, and "remember me" functionality. It is decoupled from specific user models via the `UserProvider` and `Authenticatable` contracts.

## File Location
`core/Auth.php`

## Namespace
`Core`

## Imports
- `Core\Auth\EloquentUserProvider`
- `Core\Contracts\Authenticatable`
- `Core\Contracts\UserProvider`

## Classes
- `class Auth`

## Properties
- `?Authenticatable $cachedUser`: Caches the authenticated user for the current request.
- `?UserProvider $provider`: The active user provider resolved from config.

## Methods
- `provider(): UserProvider`: Resolves the active `UserProvider` from `config/auth.php`.
- `setProvider(UserProvider $provider): void`: Manually overrides the active provider.
- `user(): ?Authenticatable`: Returns the currently logged-in user.
- `id(): int|string|null`: Returns the authenticated user's identifier.
- `check(): bool`: Checks if a user is logged in.
- `guest(): bool`: Checks if the current visitor is a guest.
- `setUser(Authenticatable $user): void`: Manually sets the authenticated user for the current request (stateless).
- `login(Authenticatable $user, bool $remember = false): void`: Logs a user into the session and optionally sets a "remember me" cookie.
- `logout(): void`: Logs out the user, clearing session and cookies.
- `attempt(array $credentials): bool`: Attempts to authenticate a user using provided credentials.
- `clearCache(): void`: Clears the request-local user cache.

## Dependencies
- `Core\Contracts\UserProvider` (Uses)
- `Core\Contracts\Authenticatable` (Uses)
- `Core\Auth\EloquentUserProvider` (Default)

## Cross References
- `config/auth.php` (Configuration)

## Source References
- `core/Auth.php:1-277`
