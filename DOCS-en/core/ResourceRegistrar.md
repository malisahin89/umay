# File Report: core/ResourceRegistrar.php

## Purpose
Helper for registering RESTful resource routes.

## Overview
Automates the creation of standard REST routes (index, create, store, show, edit, update, destroy) for a given controller. Supports `apiResource` to exclude view-related routes (create, edit).

## File Location
`core/ResourceRegistrar.php`

## Namespace
`Core`

## Classes
- `class ResourceRegistrar`

## Properties
- `string $name`: The resource URI prefix.
- `string $controller`: The controller class.
- `bool $api`: Whether it's an API resource.
- `array $only`, `$except`: Filters for which actions to register.
- `array $middlewares`: Middlewares to apply to all resource routes.

## Methods
- `only(array $actions): static`: Restricts registration to specific actions.
- `except(array $actions): static`: Excludes specific actions.
- `middleware(string|array $middleware): static`: Adds middleware to all resource routes.
- `register(): void`: The final step that actually calls `Route::get`, `Route::post`, etc.

## Internal Workflow
- **Deferred Registration**: The `register()` method is called in the `__destruct()` method. This ensures that all fluent calls (`only`, `except`, `middleware`) are completed before the routes are actually added to the `Route` registry, preventing issues with prefix stacks.

## Dependencies
- `Core\Route` (Uses)

## Source References
- `core/ResourceRegistrar.php:1-129`
