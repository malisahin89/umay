# File Report: core/ExceptionHandler.php

## Purpose
Centralized exception handler for the application.

## Overview
Manages all uncaught exceptions. It determines whether to return an HTML error page (for web requests) or a JSON error response (for API requests), and logs the errors accordingly.

## File Location
`core/ExceptionHandler.php`

## Namespace
`Core`

## Imports
- `Core\Exceptions\HttpException`
- `Core\Facades\Log as Logger`
- `Core\Facades\View`
- `Illuminate\Database\Eloquent\ModelNotFoundException`

## Classes
- `class ExceptionHandler`

## Methods
- `handle(\Throwable $e): void`: The main entry point. Routes exceptions to specific handlers based on their type.
- `handleHttp(HttpException $e): void`: Handles HTTP-specific exceptions, rendering the corresponding error view (403, 404, 500) or returning JSON.
- `handleGeneric(\Throwable $e): void`: Handles unexpected exceptions, logging the full trace and returning a 500 error.
- `shouldReturnJson(): bool`: Detects if the request expects a JSON response based on URI prefix, `Accept` header, or AJAX headers.
- `jsonResponse(int $status, string $message, string $error = 'error', ?array $debug = null): void`: Sends a standardized JSON error response.

## Internal Workflow
1. If `TerminateException` is thrown, exits silently.
2. If `CsrfException` is thrown, returns a 419 error.
3. If `HttpException` or `ModelNotFoundException` (404) is thrown, calls `handleHttp()`.
4. Otherwise, calls `handleGeneric()`.

## Dependencies
- `Core\Exceptions\HttpException` (Uses)
- `Core\Facades\Log` (Uses)
- `Core\Facades\View` (Uses)
- `Core\DebugBar` (Optional)

## Source References
- `core/ExceptionHandler.php:1-196`
