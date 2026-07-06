# File Report: core/Auth/EloquentUserProvider.php

## Purpose
Eloquent-based implementation of the `UserProvider` contract.

## Overview
The default user provider for the framework. It handles retrieving users from the database using Eloquent, validating passwords with `password_verify`, and managing "remember me" tokens.

## File Location
`core/Auth/EloquentUserProvider.php`

## Namespace
`Core\Auth`

## Classes
- `class EloquentUserProvider implements UserProvider`

## Methods
- `__construct(array $config)`: Initializes the provider with a configuration array containing the model class.
- `retrieveById(int|string $id): ?Authenticatable`: Finds a user by their primary key.
- `retrieveByCredentials(array $credentials): ?Authenticatable`: Finds a user by their email address.
- `validateCredentials(Authenticatable $user, array $credentials): bool`: Verifies the provided password against the user's hashed password.
- `retrieveByToken(int|string $id, string $token): ?Authenticatable`: Verifies a "remember me" token using `hash_equals`.
- `updateRememberToken(Authenticatable $user, ?string $token): void`: Updates the user's remember token in the database.
- `model(): string`: Returns the FQCN of the configured user model.

## Dependencies
- `Core\Contracts\UserProvider` (Implements)
- `Core\Contracts\Authenticatable` (Uses)

## Source References
- `core/Auth/EloquentUserProvider.php:1-99`
