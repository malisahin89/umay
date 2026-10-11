# File Report: core/Response.php

## Purpose
Deprecated response utility.

## Overview
Provides static methods for sending common responses (JSON, redirects, 404, 403, 500). These methods are now deprecated in favor of the `ResponseBuilder` and `abort()` helper.

## File Location
`core/Response.php`

## Namespace
`Core`

## Classes
- `class Response`

## Methods
- `json(mixed $data, int $status = 200): void`: Deprecated. Use `response()->json()`.
- `redirect(string $url, int $status = 302): void`: Deprecated. Use `\Core\Redirect::to()`.
- `notFound(string $message = '404 - Sayfa Bulunamadı'): void`: Deprecated. Use `abort(404)`.
- `forbidden(string $message = '403 - Erişim Yasak'): void`: Deprecated. Use `abort(403)`.
- `serverError(string $message = '500 - Sunucu Hatası'): void`: Deprecated. Use `abort(500)`.

## Source References
- `core/Response.php:1-66`
