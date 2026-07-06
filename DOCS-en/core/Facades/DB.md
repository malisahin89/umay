# File Report: core/Facades/DB.php

## Purpose
Static proxy for the `Core\Database` instance.

## Overview
Implements the Facade pattern to provide a static interface to the `Core\Database` class.

## File Location
`core/Facades/DB.php`

## Namespace
`Core\Facades`

## Classes
- `class DB extends Facade`

## Methods
- `getFacadeRoot()`: Returns `Database::class`.

## Dependencies
- `Core\Support\Facade` (Extends)
- `Core\Database` (Resolved root)

## Source References
- `core/Facades/DB.php:1-20`
