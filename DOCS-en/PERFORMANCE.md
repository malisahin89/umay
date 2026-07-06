# Performance & Profiling

## Purpose
Documents the profiling/diagnostics subsystem and performance-relevant mechanisms.

## Overview
Umay ships a development profiler that collects queries, cache operations, logs, views, and exceptions, and renders a debug toolbar. Profiling is gated by configuration and a runtime `UMAY_PROFILING` flag, so it adds no overhead in production.

## Components
| Component | Role | Source |
|-----------|------|--------|
| `Core\Profiler\Profiler` | Central collector; `startMeasure`/`stopMeasure`, `addView`, `finish`, `renderToolbar`, `isEnabled` | `core/Profiler/Profiler.php` |
| `Core\DebugBar` | Collection facade for queries/cache/logs/exceptions | `core/DebugBar.php` |
| `Core\Profiler\ProfilerStorage` | Persists profiler payloads (`storage/profiler/`) | `core/Profiler/ProfilerStorage.php` |
| `Core\Profiler\ProfilerController` | Serves stored profiler data (AJAX endpoint) | `core/Profiler/ProfilerController.php` |
| `Core\Profiler\Contracts\DataCollectorInterface` | Collector contract | `core/Profiler/Contracts/DataCollectorInterface.php` |
| `core/Profiler/Views/toolbar.php` | Toolbar template | `core/Profiler/Views/toolbar.php` |

## Instrumentation Hooks (verified)
- **Database:** in debug mode, `Core\Database::init` registers a query listener that calls `DebugBar::addQuery` (with SQL, bindings, time, caller/model) when `UMAY_PROFILING` is set.
- **Cache:** `Core\Cache` records `DebugBar::addCacheOp('get'|'set', key, hit?)` under `UMAY_PROFILING`.
- **View:** `Core\View::render` wraps rendering in `Profiler::startMeasure/stopMeasure`, calls `Profiler::addView`, `Profiler::finish`, and injects `Profiler::renderToolbar()` before `</body>`.
- **Logger:** forwards entries to `DebugBar::addLog` when enabled.

## Performance-Relevant Design
- File cache with atomic writes and `remember()` for memoizing expensive work (see `DOCS/CACHE.md`).
- `Core\Auth` caches the resolved user once per request; `Core\View` reuses a single Plates engine instance.

## Configuration
- `config/profiler.php` (and `PROFILER_ENABLED`) toggles profiling.

## Cross References
- `DOCS/core/Profiler/index.md`, `DOCS/core/DebugBar.md`, `DOCS/config/profiler.md`, `DOCS/CACHE.md`

## Source References
- `core/Database.php:76-90`, `core/Cache.php:122-154`, `core/View.php:276-296`, `core/Logger.php:69-71`
- `core/Profiler/Profiler.php`, `core/DebugBar.php`
