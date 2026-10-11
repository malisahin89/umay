# File Report: core/ResponseBuilder.php

## Purpose
Fluent HTTP response builder.

## Overview
Allows the construction of complex HTTP responses (headers, status codes, body types) using a fluent interface. It supports JSON, HTML, View rendering, and file downloads.

## File Location
`core/ResponseBuilder.php`

## Namespace
`Core`

## Classes
- `class ResponseBuilder`

## Properties
- `string $body`: The response body content.
- `int $status`: The HTTP status code.
- `array $headers`: Map of HTTP headers.
- `?string $downloadPath`: Path to a file for streaming downloads.

## Methods
- `status(int $code): static`: Sets the HTTP status code.
- `header(string $key, string $value): static`: Adds a single HTTP header.
- `withHeaders(array $headers): static`: Adds multiple HTTP headers.
- `json(mixed $data, int $status = 0): static`: Sets the body to a JSON-encoded string and sets the `Content-Type` to `application/json`.
- `html(string $content, int $status = 0): static`: Sets the body to an HTML string.
- `view(string $view, array $data = [], int $status = 0): static`: Renders a view template using `Core\View` and sets it as the body.
- `download(string $filePath, ?string $filename = null, int $status = 0): static`: Prepares a file for download, setting `Content-Disposition` and `Content-Length`.
- `send(): void`: Sends the headers and body to the client. If a `downloadPath` is set, it uses `readfile()` to stream the file.

## Internal Workflow
- **Header Sanitization**: `sanitizeHeader()` removes CR/LF characters to prevent header injection and response splitting.
- **Secure Downloads**: `download()` handles filename sanitization and uses RFC 5987 for non-ASCII filenames.
- **Execution Termination**: `send()` throws a `RedirectException` at the end to stop the request lifecycle.

## Dependencies
- `Core\Container` (Uses)
- `Core\View` (Uses)

## Source References
- `core/ResponseBuilder.php:1-160`
