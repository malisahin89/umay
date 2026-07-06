# Cache

## Purpose
Documents the file-based cache: storage format, integrity, concurrency, and API.

## Overview
`Core\Cache` is an instance-based, file-backed cache resolved from the container (via the `Cache` facade). Entries are stored under `config('cache.path')` (default `storage/cache/`), keyed by `sha256(prefix + key)`, and protected with an HMAC signature plus a TTL.

## Storage Format & Integrity
- Payload: `hash_hmac('sha256', json, appKey) . ':' . json`, where `json = {"value":…, "expires":ts}`.
- The app key is `APP_KEY` or a derived fallback (`getAppKey()`).
- On read, `decode()` verifies the HMAC (`hash_equals`) and expiry; missing/tampered/malformed/expired payloads return the default and corrupt/expired files are unlinked. `array_key_exists` is used so a legitimately stored `null` round-trips.

## Concurrency
- `set()` writes to a temp file then `rename()`s atomically, so concurrent unlocked readers never see a half-written file.
- `atomic($key, $mutator, $ttl)` performs a race-free read-modify-write under a cross-process `flock(LOCK_EX)` on a fixed pool of 256 bucket lock files (`umay-lock-N.lock`), preventing unbounded lock-file growth. It **fails closed** (throws) if the lock cannot be acquired — this is what makes the rate limiter TOCTOU-safe.

## Public API
- `get(key, default)`, `set(key, value, ttl?)`, `has(key)`, `forget(key)`, `flush()`, `pull(key, default)`, `remember(key, ttl, callback)`, `atomic(key, mutator, ttl?)`.
- A `"\0__umay_cache_miss__\0"` sentinel distinguishes a stored `null` from a miss (used by `remember`/`has`).
- When profiling, records cache ops via `DebugBar::addCacheOp`.

## Configuration (`config/cache.php`)
- `path` (default `storage/cache`), `prefix` (default `umay_`), `default_ttl` (default 3600s).

## Cross References
- **Consumers:** `Core\RateLimiter` (see `DOCS/SECURITY.md`), profiler.
- **Facade:** `Core\Facades\Cache` (see `DOCS/core/Facades/Cache.md`)
- **Tests:** `DOCS/tests/Unit/CacheTest.md`

## Security Observations
- HMAC integrity prevents cache tampering; atomic writes + fail-closed locking prevent races.

## Source References
- `core/Cache.php:23-319`
- `config/cache.php:12-30`
