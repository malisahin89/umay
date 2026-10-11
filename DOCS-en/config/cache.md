# File Report: config/cache.php

## Purpose
Cache configuration.

## Overview
Configures the file-based cache system used by `Core\Cache`, specifying the storage path, key prefix, and default TTL.

## File Location
`config/cache.php`

## Configuration
- `path`: Path to `storage/cache` (relative to `BASE_PATH`).
- `prefix`: Cache key prefix from `CACHE_PREFIX` (default: 'umay_').
- `default_ttl`: Default time-to-live in seconds from `CACHE_TTL` (default: 3600).

## Source References
- `config/cache.php:1-30`
