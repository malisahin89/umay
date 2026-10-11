# File Report: core/Cache.php

## Purpose
File-based cache system with HMAC integrity and TTL.

## Overview
Provides a simple key-value store using the filesystem. It ensures data integrity using HMAC signatures and supports expiration times (TTL). It includes an `atomic()` method for race-condition-free updates.

## File Location
`core/Cache.php`

## Namespace
`Core`

## Properties
- `string $cachePath`: Directory where cache files are stored.
- `string $prefix`: Prefix added to keys before hashing.
- `int $defaultTtl`: Default expiration time in seconds.
- `?string $appKey`: Secret key used for HMAC signing.

## Methods
- `get(string $key, mixed $default = null): mixed`: Retrieves a value from cache, verifying HMAC and TTL.
- `set(string $key, mixed $value, ?int $ttl = null): void`: Stores a value in cache using atomic rename.
- `atomic(string $key, callable $mutator, ?int $ttl = null): mixed`: Performs an atomic read-modify-write operation using cross-process file locks.
- `remember(string $key, int $ttl, callable $callback): mixed`: Retrieves a value or executes a callback and caches the result.
- `forget(string $key): void`: Deletes a specific cache entry.
- `flush(): void`: Clears all cache files and lock/temp files.
- `pull(string $key, mixed $default = null): mixed`: Retrieves and then deletes a cache entry.
- `has(string $key): bool`: Checks if a cache entry exists and is not expired.

## Internal Workflow
- `filename()`: Computes the SHA256 hash of the prefixed key for the file name.
- `getAppKey()`: Returns `APP_KEY`; if empty and the environment is not explicitly dev/test it throws a `RuntimeException` (**fails closed**) — the path-derived predictable fallback key is only used in local/dev/testing and warns once.
- `encode()`: JSON-encodes the value and expiry, then prepends an HMAC signature.
- `decode()`: Verifies the HMAC signature and checks that the current time is before expiry; deletes the file if corrupt.

## Dependencies
- `Core\DebugBar` (optional profiling)
- `Core\Facades\Log` (only for the fallback-key warning)

## Source References
- `core/Cache.php:1-396`
