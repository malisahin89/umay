# Glossary

## Purpose
Defines Umay-specific terminology and internal component names, derived from the analyzed source.

## Terms
- **Umay** — this minimal, from-scratch PHP MVC framework (namespace `Core\`).
- **Application** (`Core\Application`) — bootstrap orchestrator over the container; registers and boots service providers.
- **Container** (`Core\Container`) — PSR-11 dependency-injection/IoC container; supports singletons, instances, autowiring (incl. union types), and circular-dependency detection.
- **Service Provider** (`Core\ServiceProvider`) — a `register()`/`boot()` unit that wires services into the container.
- **Facade** (`Core\Support\Facade`) — static proxy forwarding calls to a container-resolved instance; short aliases (e.g. `Cache`) registered from `config('app.aliases')`.
- **Middleware group** — named list of middleware (`web`, `api`, `global`) in `config/middleware.php`, applied by the router.
- **Middleware namespaces** — ordered class-name templates (`App\Middleware\{name}Middleware`, `Core\Middleware\{name}`) used to resolve a middleware name to a class.
- **api_prefix** — URL prefix for API routes (default `/api`).
- **Guard** (`Core\Auth`) — request-scoped authentication service, decoupled via `UserProvider`/`Authenticatable` contracts.
- **UserProvider** (`Core\Contracts\UserProvider`) — the "login brain"; looks up and validates users. Default: `EloquentUserProvider`.
- **Personal access token / abilities** — Bearer API tokens (`HasApiTokens`, `PersonalAccessToken`) with scoped abilities enforced by `ApiAuth`; `*` = all abilities.
- **CSRF token** — per-session hex token (`Core\Csrf`) verified by `VerifyCsrfToken`; rotated on login.
- **CSP nonce** (`Core\Csp`) — request-local nonce for the Content-Security-Policy header, read back by the view `nonce()` helper.
- **Trusted proxies** — `config('app.trusted_proxies')`; only these may set the real client IP via `X-Forwarded-For` (`get_real_ip()`).
- **`remember_me` cookie** — persistent-login cookie (`userId:token`); only the token hash is stored server-side.
- **Cache `atomic()`** — race-free read-modify-write under a cross-process bucket lock; fails closed (used by the rate limiter, TOCTOU-safe).
- **`remember()`** — memoize a callback's result in the cache for a TTL.
- **DebugBar / Profiler** — development diagnostics collecting queries/cache/logs/views/exceptions; gated by `UMAY_PROFILING`.
- **UMAY_PROFILING** — global constant set once in the front controller to `Profiler::isEnabled()`.
- **TerminateException / RedirectException** — control-flow exceptions signaling normal termination (e.g. after a redirect); handled silently.
- **Flash / old input** — one-render session data (`_flash`, `_flash_errors`, `_old`) consumed during view rendering (PRG pattern).
- **Stub** — code-generation template under `stubs/` used by the console `make:*` commands.
- **Capsule** — the Illuminate database manager wrapped by `Core\Database`.
- **Plates** — the League template engine wrapped by `Core\View`.

## Cross References
- `DOCS/ARCHITECTURE.md`, `DOCS/FRAMEWORK_FEATURES.md`, and the subsystem reports referenced above.

## Source References
- Derived from `core/`, `config/`, `public/index.php`, and `stubs/` (see the linked reports).
