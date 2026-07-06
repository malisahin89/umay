# File Report: database/factories/UserFactory.php

## Purpose
Factory for generating dummy User models.

## Overview
Defines a set of default attributes for the `User` model, using a faker proxy to generate random names and unique emails.

## File Location
`database/factories/UserFactory.php`

## Namespace
`Database\Factories`

## Classes
- `class UserFactory extends Factory`

## Methods
- `definition(): array`: Returns default attributes: `name` (random), `email` (unique random), and `password` (static 'password').

## Dependencies
- `App\Models\User` (Target Model)
- `Core\Factory` (Extends)

## Source References
- `database/factories/UserFactory.php:1-25`
