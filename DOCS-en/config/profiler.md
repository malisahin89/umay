# File Report: config/profiler.php

## Purpose
Profiler configuration.

## Overview
Controls the behavior of the application profiler, including whether it's enabled, where data is stored, and who can access it.

## File Location
`config/profiler.php`

## Configuration
- `enabled`: Boolean from `PROFILER_ENABLED` or `APP_DEBUG`.
- `storage_path`: Path to `storage/profiler` (relative to `BASE_PATH`).
- `ttl`: Lifetime of profiles in seconds from `PROFILER_TTL` (default: 7200).
- `max_entries`: Max number of profiles to keep from `PROFILER_MAX_ENTRIES` (default: 200).
- `ip_whitelist`: IPs allowed to access the profiler from `PROFILER_IP_WHITELIST` (default: '127.0.0.1,::1').

## Source References
- `config/profiler.php:1-49`
