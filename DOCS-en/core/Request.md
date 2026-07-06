# File Report: core/Request.php

## Purpose
HTTP Request abstraction.

## Overview
Captures and provides a unified API to access superglobals (`$_GET`, `$_POST`, `$_FILES`, `$_SERVER`, `$_COOKIE`). It includes automatic JSON body parsing for API requests and security filters for sensitive input.

## File Location
`core/Request.php`

## Namespace
`Core`

## Classes
- `class Request`

## Properties
- `array $get`, `$post`, `$files`, `$server`, `$cookies`: Snapshots of the superglobals.
- `array $routeParams`: Parameters captured from the route pattern (e.g., `{id}`).

## Methods
- `capture(): static`: Factory method to create a `Request` from current superglobals.
- `input(string $key, mixed $default = null): mixed`: Retrieves a value from POST (priority) or GET.
- `all(): array`: Merges GET and POST data.
- `only(array $keys): array` / `except(array $keys): array`: Filters input data.
- `has(string $key): bool`: Checks if a key exists in input.
- `filled(string $key): bool`: Checks if a key exists and is not an empty string.
- `file(string $key): ?array`: Retrieves an uploaded file.
- `method(): string`: Returns the HTTP method (GET, POST, etc.).
- `isAjax(): bool`: Checks if the request is an AJAX request.
- `header(string $key, ?string $default = null): ?string`: Retrieves an HTTP header, with fallbacks for specific SAPI behaviors (e.g., `Authorization` header).
- `ip(): string`: Returns the client's real IP address via `getRealIP()`.
- `path(): string`: Returns the requested URI path.
- `fullUrl(): string`: Returns the complete URL of the request.
- `route(string $key, mixed $default = null): mixed`: Returns a route parameter.
- `bearerToken(): ?string`: Extracts the Bearer token from the `Authorization` header.
- `expectsJson(): bool`: Determines if the request expects a JSON response.
- `validate(array $rules): ?array`: Validates the request data using `Core\Validator`.

## Internal Workflow
- **JSON Parsing**: In the constructor, if the `Content-Type` is `application/json`, it reads `php://input` (up to 8MB) and merges the decoded JSON into the `$post` array.
- **Sensitive Filtering**: `exceptSensitive()` removes keys like `password`, `token`, `secret` from the input before it is flashed to the session, preventing plaintext credentials from being stored.

## Source References
- `core/Request.php:1-317`
