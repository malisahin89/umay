# File Report: core/Facades/Event.php

## Purpose
Static proxy for the `Core\Events\Dispatcher` instance.

## Overview
Implements the Facade pattern to provide a static interface to the `Core\Events\Dispatcher` class.

## File Location
`core/Facades/Event.php`

## Namespace
`Core\Facades`

## Classes
- `class Event extends Facade`

## Methods
- `getFacadeRoot()`: Returns `Dispatcher::class`.

## Dependencies
- `Core\Support\Facade` (Extends)
- `Core\Events\Dispatcher` (Resolved root)

## Source References
- `core/Facades/Event.php:1-20`
