# File Report: core/Contracts/UserProvider.php

## Purpose
Interface for user retrieval and authentication logic.

## Overview
Defines the contract for "User Providers". It separates the authentication guard from the data source, allowing users to be fetched from a database, an external API, or LDAP without changing the core auth logic.

## File Location
`core/Contracts/UserProvider.php`

## Namespace
`Core\Contracts`

## Interfaces
- `interface UserProvider`

## Methods
- `retrieveById(int|string $id): ?Authenticatable`: Fetches a user by their ID.
- `retrieveByCredentials(array $credentials): ?Authenticatable`: Fetches a user based on credentials (e.g., email).
- `validateCredentials(Authenticatable $user, array $credentials): bool`: Verifies the user's password.
- `retrieveByToken(int|string $id, string $token): ?Authenticatable`: Fetches a user via a remember token.
- `updateRememberToken(Authenticatable $user, ?string $token): void`: Persists the remember token.

## Dependencies
- `Core\Contracts\Authenticatable` (Uses)

## Source References
- `core/Contracts/UserProvider.php:1-54`
