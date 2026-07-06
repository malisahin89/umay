# File Report: core/Auth/PersonalAccessToken.php

## Purpose
Model for hashed API access tokens.

## Overview
Represents a personal access token issued to a user. Tokens are stored as SHA256 hashes of the plaintext tokens provided to the client to ensure security.

## File Location
`core/Auth/PersonalAccessToken.php`

## Namespace
`Core\Auth`

## Classes
- `class PersonalAccessToken extends Model`

## Properties
- `$table`: `personal_access_tokens`
- `$fillable`: `['name', 'token', 'abilities', 'last_used_at', 'expires_at']`
- `$casts`: Casts `abilities` to array, and `last_used_at`/`expires_at` to datetime.

## Methods
- `tokenable(): MorphTo`: Returns the polymorphic relationship to the owner of the token.
- `findToken(string $tokenString): ?self`: Resolves a "{id}|{plaintext}" token string by fetching the record by ID and verifying the hash.
- `can(string $ability): bool`: Checks if the token has the required ability or a wildcard `*`.

## Dependencies
- `Core\Model` (Extends)
- `Illuminate\Database\Eloquent\Relations\MorphTo` (Uses)

## Source References
- `core/Auth/PersonalAccessToken.php:1-105`
