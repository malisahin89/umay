# File Report: app/Providers/RouteServiceProvider.php

## Purpose
Service provider responsible for loading application routes.

## Overview
Loads route definitions from `routes/web.php` and `routes/api.php`. It applies the 'web' middleware group to web routes and the 'api' middleware group with a configurable prefix to API routes.

## File Location
`app/Providers/RouteServiceProvider.php`

## Namespace
`App\Providers`

## Imports
- `Core\Route`
- `Core\ServiceProvider`

## Classes
- `class RouteServiceProvider extends ServiceProvider`

## Methods
- `register(): void`: No logic implemented (bindings are handled in `boot`).
- `boot(): void`: Triggers the loading of web and API routes.
- `loadWebRoutes(): void`: Checks for `routes/web.php` and loads it under the 'web' group.
- `loadApiRoutes(): void`: Checks for `routes/api.php` and loads it under the 'api' group with the prefix defined in `middleware.api_prefix` (default: `/api`).

## Dependencies
- `Core\ServiceProvider` (Extends)
- `Core\Route` (Uses)

## Source References
- `app/Providers/RouteServiceProvider.php:1-80`
