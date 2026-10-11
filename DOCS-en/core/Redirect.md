# File Report: core/Redirect.php

## Purpose
Secure HTTP redirection utility.

## Overview
Handles redirection to internal or external URLs. It includes security checks to prevent "Open Redirect" attacks by ensuring that absolute URLs match the `APP_URL` host and that protocol-relative URLs are rejected.

## File Location
`core/Redirect.php`

## Namespace
`Core`

## Classes
- `class Redirect`

## Methods
- `to(string $url): void`: Redirects to the specified URL. Validates the URL for security before sending the `Location` header.
- `route(string $name): void`: Redirects to a named route by resolving its URL first.

## Internal Workflow
1. Strips CR/LF from the URL to prevent header injection.
2. Rejects protocol-relative URLs (e.g., `//evil.com`).
3. If the URL is relative (starts with `/`), it is allowed.
4. If the URL is absolute, it is allowed only if the host matches the `APP_URL` host.
5. If any check fails, it redirects to the homepage (`/`) as a safe fallback.
6. Throws a `RedirectException` to stop further execution of the request.

## Dependencies
- `Core\Route` (Uses)

## Source References
- `core/Redirect.php:1-51`
