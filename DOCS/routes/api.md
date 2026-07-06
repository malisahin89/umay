# File Report: routes/api.php

## Purpose
Definition of API endpoints.

## Overview
Contains route definitions for the API. All routes in this file are automatically prefixed with the `api_prefix` (default `/api`) and assigned the `api` middleware group. These routes are stateless (no session or CSRF).

## File Location
`routes/api.php`

## Key Concepts
- **Statelessness**: Sessions and CSRF are disabled.
- **Authentication**: Recommended use of `api-auth` middleware for Bearer token validation.
- **Abilities**: Supports granular permission checks via `api-auth:ability_name`.
- **Resources**: Supports `Route::apiResource()` for quick CRUD endpoint generation.

## Source References
- `routes/api.php:1-77`
