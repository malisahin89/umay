# Error & Exception Handling

## Purpose
Documents centralized exception handling and error responses.

## Overview
`Core\ExceptionHandler::handle(\Throwable $e)` is the central handler invoked from the front controller's try/catch. It supports the hybrid web/API architecture: web requests get HTML error pages, API requests get JSON.

## Exception Dispatch
- `TerminateException` → `exit` silently (normal flow termination, e.g. after redirect/response send).
- `CsrfException` → HTTP **419**, logs a warning; JSON or plain message depending on request type.
- `HttpException` (from `abort()`) → `handleHttp()` with its status code.
- Eloquent `ModelNotFoundException` → mapped to `HttpException(404)`.
- Anything else → `handleGeneric()` (HTTP 500); reported to `DebugBar` if enabled.

## Web vs API Detection (`shouldReturnJson`)
Returns JSON when: the path starts with `config('middleware.api_prefix')` (default `/api`), the `Accept` header contains `application/json`, or `X-Requested-With: XMLHttpRequest`.

## HTTP Errors (`handleHttp`)
- Sets the response code, logs a warning.
- Web: renders `errors/403`, `errors/404`, or `errors/500` (fallback to plain text if the view fails).
- API: consistent JSON body `{error, status, message}`.

## Generic Errors (`handleGeneric`)
- HTTP 500; logs the exception (file/line/trace).
- API: `{message: "Internal Server Error"}`, plus a `debug` block when `APP_DEBUG`.
- Web: raw trace dump when `APP_DEBUG`, otherwise the `errors/500` view.

## Exception Classes
- `Core\Exceptions\HttpException` (status code; defaults 403/500), `Core\Exceptions\ContainerException`, `Core\Exceptions\EntryNotFoundException` (PSR-11), `Core\CsrfException`, `Core\TerminateException`, `Core\RedirectException` (extends `TerminateException`).

## Cross References
- `DOCS/core/ExceptionHandler.md`, `DOCS/core/Exceptions/index.md`, `DOCS/LOGGING.md`, `DOCS/VIEW_ENGINE.md`
- Tests: `DOCS/tests/Unit/ExceptionClassesTest.md`, `DOCS/tests/Unit/ExceptionFixTest.md`

## Source References
- `core/ExceptionHandler.php:22-196`
- `core/ExceptionHandler.php:151-173` (JSON detection)
