# View Engine

## Purpose
Documents `Core\View`, the wrapper that renders templates and exposes view helpers.

## Overview
`Core\View` wraps the League Plates engine. It is instance-based (resolved from the container via the `View` facade) and caches a single `Engine` instance per view instance, registering all template functions once. Templates live in `views/`, with a `partials` folder registered for component-style includes (`$this->insert('partials::alert', …)`).

## Rendering (`render($template, $data)`)
1. Starts the session if needed.
2. Reads PRG validation errors from `$_SESSION['_flash_errors']` (kept until after render).
3. Consumes flash `success`/`error` once and remembers them (`$consumedFlash`) so the `flash()` helper stays consistent with the `$success`/`$error` globals.
4. Adds global template data: `title`, `errors`, `success`, `error`, `user_id`.
5. If the profiler is enabled, measures the render, appends view data, finishes profiling, and injects the toolbar HTML before `</body>`.
6. Echoes output, then clears `_old` and `_flash_errors` (one-render lifetime).

## Registered Template Functions
- Security: `csrf()` (hidden input), `csrf_token()`, `e()` (htmlspecialchars), `method()` (method spoofing), `nonce()` (CSP nonce).
- Routing/assets: `route()`, `url()` (legacy alias), `asset()` — all XSS-escaped.
- Forms/input: `old()` (escaped).
- Auth: `auth()`, `guest()`.
- Config/env: `config()`, `app_name()`.
- Date: `now()`.
- Misc: `class_if()`, `has_error()`, `error()`, `flash()`, `dd()`.

## Cross References
- `DOCS/TEMPLATE_ENGINE.md`, `DOCS/core/View.md`, `DOCS/core/Facades/View.md`
- Security helpers: `Core\Csrf`, `Core\Csp` (see `DOCS/SECURITY.md`)
- Tests: `DOCS/tests/Unit/ViewTest.md`

## Source References
- `core/View.php:20-306`
- `core/View.php:61-235` (function registration), `core/View.php:241-305` (render)
