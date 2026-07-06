# File Report: public/index.php

## Purpose
The main entry point (Front Controller) of the application.

## Overview
Initializes the environment, bootstraps the `Application`, handles session management, manages the `Profiler`, and dispatches the request to the `Route` system.

## File Location
`public/index.php`

## Key Responsibilities
- **Environment Setup**: Defines `BASE_PATH` and loads Composer autoload and configuration.
- **Profiler**: Initializes the debug profiler and registers a shutdown function to save profile data.
- **API vs Web Detection**: Detects if a request is an API call based on the prefix (default `/api`).
- **Session Management**: For web requests, configures secure cookie settings (`httponly`, `secure`, `samesite`) and implements an idle timeout mechanism.
- **Bootstrapping**: 
    - Initializes the `Application` singleton.
    - Captures the current request.
    - Registers core service providers: `FacadeServiceProvider`, `EventServiceProvider`, `RouteServiceProvider`.
    - Boots the application.
- **Routing**: Calls `Route::dispatch()` to execute the handler for the current URI.
- **Exception Handling**: Wraps the dispatch process in a try-catch block, delegating errors to `$app->handleException($e)`.

## Source References
- `public/index.php:1-124`
