# File Report: core/helpers.php

## Purpose
Global helper functions for common framework tasks.

## Overview
Provides a set of shorthand functions for routing, configuration, authentication, and other utility tasks, mirroring the ergonomics of larger frameworks.

## File Location
`core/helpers.php`

## Functions
### Routing & HTTP
- `route(string $name, array $params = [])`: Generates a URL for a named route.
- `redirect(string $urlOrName)`: Redirects the user to a URL or named route.
- `back()`: Redirects the user to the previous page (HTTP_REFERER).
- `asset(string $path)`: Generates a URL for a static asset.
- `abort(int $code, string $message = '')`: Throws an `HttpException` to trigger an error page.
- `response(string $body = '', int $status = 200)`: Returns a `ResponseBuilder` instance.

### Environment & Config
- `env(string $key, mixed $default = null)`: Retrieves an environment variable from `.env` with type casting.
- `config(string $key, mixed $default = null)`: Retrieves a configuration value from files in `config/` using dot notation.

### Session & Flash
- `flash(string $key, ?string $message = null)`: Sets or retrieves a temporary flash message.
- `old(string $key, string $default = '', bool $escape = true)`: Retrieves old input from the session.

### Auth & Security
- `auth()`: Returns the currently authenticated user via `Core\Auth`.
- `csrf()` / `csrf_token()`: Generates a CSRF token.

### Validation & Data
- `validate(array $data, array $rules, array $messages = [])`: Validates data and returns errors or null.
- `collect(mixed $items = [])`: Creates an `Illuminate\Support\Collection`.
- `factory(string $modelClass, int $count = 1)`: Returns a model factory instance.

### Other Utilities
- `event(Event $event)`: Dispatches an event.
- `paginator(mixed $items, int $total = 0, int $perPage = 15)`: Returns a `Core\Paginator` instance.
- `method_field(string $method)`: Generates a hidden input for HTTP method spoofing.
- `cache(?string $key = null, mixed $value = null, ?int $ttl = null)`: Accesses the `Core\Cache` system.
- `str_slug(string $text, string $separator = '-')`: Generates a URL-safe slug.
- `str_limit(string $text, int $limit = 100, string $end = '...')`: Limits string length.
- `now(string $format = 'Y-m-d H:i:s')`: Returns current date/time.
- `today(string $format = 'Y-m-d')`: Returns current date.
- `isCloudflareIP(string $ip)`: Checks if an IP belongs to Cloudflare.
- `getRealIP()`: Securely retrieves the client's real IP address.

## Dependencies
- Uses almost every core component (`Route`, `Redirect`, `Auth`, `Cache`, `Container`, `Request`, `ResponseBuilder`, `Validator`, `Paginator`, `Factory`, `Dispatcher`).

## Source References
- `core/helpers.php:1-647`
