# File Report: tests/Unit/RouteExtendedTest.php

## Purpose
Extended unit tests for the router.

## Overview
Verifies `Core\Route` across HTTP verb registration (`get`/`post`/`put`/`patch`/`delete`), `match`/`any` (methods, middleware, name propagation), named-route URL generation (params, query params, unknown-name fallback), prefix groups (including nesting), middleware assignment and group inheritance, compiled parameter regex (static dot escaping, optional params), group get/set, route removal, closure and root routes, and optional-parameter URL building.

## File Location
`tests/Unit/RouteExtendedTest.php`

## Namespace
`Tests\Unit`

## Classes
- `class RouteExtendedTest extends Tests\TestCase`

## Subject Under Test
- `Core\Route`

## Test Methods
Verb registration `:45-80`; `match`/`any` `:90-146`; named routes `:148-172`; prefix groups `:180-190`; middleware `:204-222`; compiled regex `:234`, `:329-341`; group get/set `:249`; remove `:260`; closure/root routes `:272-285`; optional-param URLs `:297-319`.

## Cross References
- **Tests:** `Core\Route` (see `DOCS/core/Route.md`)

## Source References
- `tests/Unit/RouteExtendedTest.php:1-351`
