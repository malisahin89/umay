# File Report: core/Auth/HasApiTokens.php

## Purpose
Trait to enable API token issuance for a user model.

## Overview
Provides functionality for creating and managing personal access tokens (Bearer tokens). This allows the application to authenticate stateless API requests.

## File Location
`core/Auth/HasApiTokens.php`

## Namespace
`Core\Auth`

## Traits
- `trait HasApiTokens`

## Methods
- `tokens(): MorphMany`: Returns the relationship to the user's personal access tokens.
- `createToken(string $name, array $abilities = ['*'], ?\DateTimeInterface $expiresAt = null): array`: Generates a new token, hashes it, persists it to the database, and returns the plaintext token.
- `withAccessToken(PersonalAccessToken $token): static`: Binds a specific token to the model instance for the current request.
- `currentAccessToken(): ?PersonalAccessToken`: Returns the token used to authenticate the current request.
- `tokenCan(string $ability): bool`: Checks if the current token has a specific permission.

## Dependencies
- `Core\Auth\PersonalAccessToken` (Uses)
- `Illuminate\Database\Eloquent\Relations\MorphMany` (Uses)

## Source References
- `core/Auth/HasApiTokens.php:1-111`
