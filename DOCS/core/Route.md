# File Report: core/Route.php

## Purpose
HTTP Route Registry and Dispatcher.

## Overview
The core routing engine. It allows defining routes (GET, POST, etc.), grouping them with prefixes and middleware, and dispatching the current request to the correct handler. It supports named routes, RESTful resources, and parameterized URIs with regex matching.

## File Location
`core/Route.php`

## Namespace
`Core`

## Classes
- `class Route`

## Properties
- `static array $routes`: Registry of all registered routes.
- `static array $prefixStack`: Stack of active prefixes for grouping.
- `static array $middlewareStack`: Stack of active middleware for grouping.
- `static array $namedRoutes`: Mapping of route names to URIs.
- `static string $currentGroup`: The active middleware group ('web' or 'api').

## Methods
- `get()`, `post()`, `put()`, `patch()`, `delete()`: Register routes for specific HTTP methods.
- `match(array $methods, string $uri, \Closure|string $action): static`: Registers one route for multiple methods.
- `any(string $uri, \Closure|string $action): static`: Registers a route for all methods.
- `view(string $uri, string $view, array $data = []): static`: Registers a route that renders a view directly.
- `redirect(string $from, string $to, int $status = 302): static`: Registers a redirect route.
- `resource()`, `apiResource()`: Registers a set of RESTful resource routes via `ResourceRegistrar`.
- `prefix(string $prefix): static`: Pushes a prefix onto the stack for subsequent routes.
- `group(\Closure $callback): static`: Wraps route definitions in a closure and pops the prefix/middleware stacks after execution.
- `name(string $routeName): static`: Assigns a name to the current route.
- `middleware(string|array $middlewareName): static`: Assigns middleware to the current route or group.
- `url(string $name, array $params = []): string`: Generates a URL for a named route, resolving placeholders.
- `dispatch(): void`: The main dispatcher. Matches the request URI/method to a route, resolves middleware, and executes the handler.

## Internal Workflow
- **Route Compilation**: Parameterized routes (e.g., `/users/{id}`) are compiled into regex patterns during registration to optimize dispatch.
- **Method Spoofing**: Supports `_method` POST parameter to allow PUT/PATCH/DELETE in HTML forms.
- **Middleware Pipeline**: Uses `array_reduce` to build a nested closure chain (the "onion" model) for executing middleware and the final handler.
- **Parameter Casting**: `castRouteParam()` coerces URL string segments to the controller's declared scalar types (int, float, bool).
- **Trailing Slash Redirect**: Redirects `/path/` to `/path` for GET requests to ensure canonical URLs.

## Dependencies
- `Core\ResourceRegistrar` (Uses)
- `Core\Request` (Uses)
- `Core\Container` (Uses)
- `Core\Profiler\ProfilerController` (Uses for `/_profiler` routes)

## Source References
- `core/Route.php:1-985`
