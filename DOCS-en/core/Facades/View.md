# File Report: core/Facades/View.php

## Purpose
Static proxy for the `Core\View` instance.

## Overview
Implements the Facade pattern to provide a static interface to the `Core\View` class.

## File Location
`core/Facades/View.php`

## Namespace
`Core\Facades`

## Classes
- `class View extends Facade`

## Methods
- `getFacadeRoot()`: Returns `View::class`.
- `@method static void share(string|array $key, mixed $value = null)`: Static proxy for global data sharing.

## Dependencies
- `Core\Support\Facade` (Extends)
- `Core\View` (Resolved root)

## Source References
- `core/Facades/View.php:1-20`
