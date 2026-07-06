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
- `filename()`: Computes a SHA256 hash of the prefixed key for the filename.
- `encode()`: JSON-encodes value and expiry, then appends an HMAC signature.
- `decode()`: Verifies HMAC signature and checks if the current time is before the expiry.

## Dependencies
- `Core\DebugBar` (Optional profiling)

## Source References
- `core/Cache.php:1-319`
