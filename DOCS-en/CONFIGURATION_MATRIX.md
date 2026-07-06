# Configuration Matrix

## Purpose
Lists verified configuration keys, defaults, and sources.

## Overview
Each key is read via `config('file.key')`. Defaults below are the in-code fallbacks; most are overridable through the listed environment variable. Keys from `config/database.php`, `config/mail.php`, and `config/profiler.php` exist but were not enumerated here — see their per-file reports.

## app (`config/app.php`)
| Key | Default | Env | Source |
|-----|---------|-----|--------|
| `name` | `Umay` | `APP_NAME` | `:18` |
| `version` | `1.0.0` | — | `:24` |
| `url` | `http://localhost` | `APP_URL` | `:30` |
| `controller_namespace` | `App\Controllers\` | `CONTROLLER_NAMESPACE` | `:45` |
| `env` | `local` | `APP_ENV` | `:51` |
| `trusted_proxies` | `127.0.0.1,::1` | `TRUSTED_PROXIES` | `:64-67` |
| `debug` | `false` | `APP_DEBUG` | `:76` |
| `timezone` | `Europe/Istanbul` | `APP_TIMEZONE` | `:82` |
| `key` | `` (empty) | `APP_KEY` | `:91` |
| `aliases` | facade map | — | `:106-116` |

## auth (`config/auth.php`)
| Key | Default | Env | Source |
|-----|---------|-----|--------|
| `default` | `eloquent` | `AUTH_PROVIDER` | `:27` |
| `providers.eloquent.driver` | `EloquentUserProvider::class` | — | `:38` |
| `providers.eloquent.model` | `User::class` | — | `:39` |

## cache (`config/cache.php`)
| Key | Default | Env | Source |
|-----|---------|-----|--------|
| `path` | `<BASE_PATH>/storage/cache` | — | `:17` |
| `prefix` | `umay_` | `CACHE_PREFIX` | `:23` |
| `default_ttl` | `3600` | `CACHE_TTL` | `:29` |

## session (`config/session.php`)
| Key | Default | Env | Source |
|-----|---------|-----|--------|
| `lifetime` | `30` | `SESSION_LIFETIME` | `:10` |
| `cookie` | `umay_session` | `SESSION_COOKIE` | `:16` |
| `secure` | auto (HTTPS) | `SESSION_SECURE` | `:22-25` |
| `http_only` | `true` | — | `:26` |
| `same_site` | `Strict` | `SESSION_SAME_SITE` | `:27` |

## middleware (`config/middleware.php`)
| Key | Default | Env | Source |
|-----|---------|-----|--------|
| `api_prefix` | `/api` | `API_PREFIX` | `:53` |
| `global` | `[]` | — | `:67-70` |
| `web` | `[RememberMe, SecurityHeaders, VerifyCsrfToken]` | — | `:84-88` |
| `api` | `[Cors, throttle:60,60]` | — | `:102-105` |
| `cors_origin` | `*` | `CORS_ORIGIN` | `:121` |
| `cors_methods` | `GET, POST, PUT, PATCH, DELETE, OPTIONS` | `CORS_METHODS` | `:138` |
| `cors_headers` | `Content-Type, Authorization, …` | `CORS_HEADERS` | `:139` |
| `cors_credentials` | `false` | `CORS_CREDENTIALS` | `:140` |
| `cors_max_age` | `86400` | `CORS_MAX_AGE` | `:141` |
| `namespaces` | `[App\Middleware\{name}Middleware, Core\Middleware\{name}]` | — | `:163-166` |
| `aliases` | `{throttle: Throttle, cors: Cors}` | — | `:182-185` |

## Cross References
- `DOCS/CONFIGURATION.md`, `DOCS/config/index.md`

## Source References
- `config/app.php`, `config/auth.php`, `config/cache.php`, `config/session.php`, `config/middleware.php`
