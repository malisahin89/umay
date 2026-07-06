# Filesystem

## Purpose
Documents runtime storage locations and file upload handling.

## Overview
Umay writes runtime artifacts under `storage/` and handles user uploads through `Core\FileUpload`. There is no general filesystem/disk abstraction; components use PHP file functions directly against known paths.

## Storage Layout (`storage/`)
- `storage/cache/` — file cache entries plus `*.lock` / `*.tmp` sidecars (see `DOCS/CACHE.md`).
- `storage/logs/` — daily log files `Y-m-d.log` (see `DOCS/LOGGING.md`).
- `storage/profiler/` — profiler artifacts (see `DOCS/PERFORMANCE.md`).

Cache and log directories are created on demand with mode `0700`.

## File Uploads (`Core\FileUpload`)
Verified behavior (from `Core\FileUpload` and its tests):
- **Filename sanitization** — strips Turkish/special characters, allows alphanumeric/dash/underscore, falls back to `uniqid()` when the result is empty, and defends against path-traversal attempts.
- **Path containment** — validated paths must resolve inside `public/`; paths outside throw.
- **Type allow-list** — image types such as JPEG are allowed; executable types (e.g. PHP) are rejected.
- **Size limit** — maximum upload size is 2 MB.
- **Safe operations** — `rename`/`delete` return `false` for the default/empty/nonexistent targets rather than erroring.

## Security Observations
- Upload path containment + type allow-list + extension sanitization mitigate path traversal and executable upload.
- `0700` permissions on created storage dirs.

## Cross References
- `Core\FileUpload` (see `DOCS/core/FileUpload.md`), tests: `DOCS/tests/Unit/FileUploadTest.md`
- `Core\Cache` (see `DOCS/CACHE.md`), `Core\Logger` (see `DOCS/LOGGING.md`)

## Source References
- `core/FileUpload.php`
- `core/Cache.php:44-46`, `core/Logger.php:25-28`, `core/Logger.php:66`
