# File Report: core/EventServiceProvider.php

## Purpose
Base class for registering event listeners.

## Overview
Provides a structure for defining event-to-listener mappings. Application-level event providers extend this class and override the `$listen` property.

## File Location
`core/EventServiceProvider.php`

## Namespace
`Core`

## Imports
- `Core\Events\Dispatcher`

## Classes
- `abstract class EventServiceProvider extends ServiceProvider`

## Properties
- `array $listen`: Mapping of event classes to arrays of listener classes.

## Methods
- `register(): void`: Binds the `Dispatcher` singleton to the container.
- `boot(): void`: Registers the defined `$listen` mapping with the `Dispatcher`.

## Dependencies
- `Core\ServiceProvider` (Extends)
- `Core\Events\Dispatcher` (Uses)

## Source References
- `core/EventServiceProvider.php:1-48`
