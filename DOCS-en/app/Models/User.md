# File Report: app/Models/User.php

## Purpose
User entity model for authentication and user data management.

## Overview
Represents the `users` table in the database and implements `Authenticatable` to allow the framework's auth system to handle user authentication.

## File Location
`app/Models/User.php`

## Namespace
`App\Models`

## Imports
- `Core\Auth\HasApiTokens`
- `Core\Contracts\Authenticatable`
- `Core\Model`
- `Illuminate\Support\Carbon`

## Classes
- `class User extends Model implements Authenticatable`

## Traits
- `HasApiTokens` (Used for Bearer-token issuance)

## Properties
- `$table`: `users`
- `$fillable`: `['name', 'email', 'password']`
- `$hidden`: `['password', 'remember_token']`

## Methods
- `setPasswordAttribute(mixed $value): void`: Mutator that automatically hashes the password using `password_hash` when set.
- `getAuthIdentifier(): int|string`: Returns the user's ID as the authentication identifier.
- `getAuthPassword(): string`: Returns the hashed password for authentication.
- `getRememberToken(): ?string`: Returns the remember token.
- `setRememberToken(?string $token): void`: Sets the remember token.

## Dependencies
- `Core\Model` (Extends)
- `Core\Contracts\Authenticatable` (Implements)

## Cross References
- `Core\Auth\HasApiTokens` (Uses)

## Source References
- `app/Models/User.php:1-74`
