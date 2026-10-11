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
- `string $name`: Resource URI prefix.
- `string $controller`: Controller class.
- `bool $api`: Whether this is an API resource.
- `array $only`, `$except`: Filters for which actions are registered.
- `array $middlewares`: Middleware applied to all resource routes.
- `string $prefix`: Group prefix captured at construction (for deferred registration).

## Methods
- `only(array $actions): static`: Restricts registration to specific actions.
- `except(array $actions): static`: Excludes specific actions.
- `middleware(string|array $middleware): static`: Adds middleware to all resource routes.
- `registerRoutes(): void`: Internal step that turns actions into `Route::get/post/...` calls under the captured prefix.
- `register(): void`: Final step invoked from `__destruct()`; runs registration inside `Route::withPrefix($this->prefix, ...)`.
- `__destruct()`: Triggers registration once all fluent `only()`/`except()`/`middleware()` calls are complete.

## Internal Workflow
- **Deferred Registration**: `register()` is invoked from `__destruct()`. This ensures all fluent calls (`only`, `except`, `middleware`) complete before routes are actually added to the `Route` registry, avoiding prefix-stack issues.
- **Prefix Capture**: The active group prefix is captured into `$prefix` via `Route::currentPrefix()` at construction and re-applied **exactly** (not appended) via `Route::withPrefix()` during registration, so routes land under the correct prefix even after the enclosing group has popped its prefix, without double-prefixing.
- **`update` PUT+PATCH**: The `update` action is registered for both `PUT` and `PATCH`; with only PUT registered, a real PATCH request (or a `method_field('PATCH')` form) would 404.

## Dependencies
- `Core\Route` (Uses)

## Source References
- `core/ResourceRegistrar.php:1-156`
