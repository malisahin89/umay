# File Report: core/Contracts/Authenticatable.php

## Purpose
Interface for authenticatable entities.

## Overview
Defines the minimum requirements for a model to be handled by the authentication system. It ensures the auth guard can retrieve the ID, password, and remember token without knowing the model's concrete implementation.

## File Location
`core/Contracts/Authenticatable.php`

## Namespace
`Core\Contracts`

## Interfaces
- `interface Authenticatable`

## Methods
- `getAuthIdentifier(): int|string`: Returns the unique identifier for the user.
- `getAuthPassword(): string`: Returns the hashed password.
- `getRememberToken(): ?string`: Returns the current remember token.
- `setRememberToken(?string $token): void`: Updates the remember token.

## Source References
- `core/Contracts/Authenticatable.php:1-42`
