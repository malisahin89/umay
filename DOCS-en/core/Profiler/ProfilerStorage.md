# File Report: core/Profiler/ProfilerStorage.php

## Purpose
Persistence layer for profiler data.

## Overview
Handles the saving, loading, and automatic cleanup of profiler JSON files in the `storage/profiler/` directory.

## File Location
`core/Profiler/ProfilerStorage.php`

## Namespace
`Core\Profiler`

## Classes
- `class ProfilerStorage`

## Methods
- `save(string $token, array $data): void`: Writes the profile data to a JSON file.
- `load(string $token): ?array`: Reads and decodes a profile JSON file.
- `cleanup(): void`: Deletes profiles that have exceeded their TTL or when the `max_entries` limit is reached.
- `listRecent(): array`: Returns a list of the most recent profiles.

## Dependencies
- `Core\Profiler\Profiler` (Uses)

## Source References
- `core/Profiler/ProfilerStorage.php:1-110`
