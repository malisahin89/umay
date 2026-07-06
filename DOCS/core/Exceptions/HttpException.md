# File Report: core/Exceptions/HttpException.php

## Purpose
Custom exception for HTTP-specific errors.

## Overview
Used to trigger specific HTTP responses (e.g., 403, 404, 500) from anywhere in the application. It is caught by the `ExceptionHandler` to render the appropriate error view.

## File Location
`core/Exceptions/HttpException.php`

## Namespace
`Core\Exceptions`

## Classes
- `class HttpException extends \RuntimeException`

## Methods
- `getStatusCode(): int`: Returns the HTTP status code associated with the exception.

## Source References
- `core/Exceptions/HttpException.php:1-25`
