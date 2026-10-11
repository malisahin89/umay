# File Report: core/Facades/Auth.php

## Purpose
Static proxy for the `Core\Auth` instance.

## Overview
Implements the Facade pattern to provide a static interface to the `Core\Auth` class, which is resolved from the container.

## File Location
`core/Facades/Auth.php`

## Namespace
`Core\Facades`

## Classes
- `class Auth extends Facade`

## Methods
- `getFacadeRoot()`: Returns `Auth::class`.

## Dependencies
- `Core\Support\Facade` (Extends)
- `Core\Auth` (Resolved root)

## Source References
- `core/Facades/Auth.php:1-30`
