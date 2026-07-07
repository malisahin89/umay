# Bağımlılık Grafiği (Dependency Graph)

## Amaç
Harici (Composer) ve temel dahili bağımlılıkları belgeler.

## Harici Bağımlılıklar (composer.json)
| Paket | Kullananlar |
|---------|---------|
| `illuminate/database ^10.0` | `Core\Database` (Capsule), `Core\Model` (Eloquent), `Core\Validator` (`DB::table` unique/exists kontrollerinde) |
| `illuminate/events ^10.0` | `Core\Database::init` içinde yapılandırılan Eloquent olay dağıtıcısı |
| `illuminate/pagination ^10.0` | `Core\Paginator` |
| `league/plates ^3.4` | `Core\View` şablon motoru |
| `vlucas/phpdotenv ^5.6` | `.env` yükleme (ön kontrolcü / test bootstrap) |
| `php >= 8.2` | Tüm framework |

Sadece geliştirme (Dev-only): `laravel/pint`, `phpstan/phpstan ^2.1`, `phpunit/phpunit ^10.0`.

## Temel Dahili Bağımlılıklar
- `Core\Application` → `Core\Container` (kompozisyon); `Core\ExceptionHandler`'ı çözümler.
- `Core\Route` → middleware sınıfları (`config('middleware.namespaces')` aracılığıyla çözümlenir), kontrolcüler (`config('app.controller_namespace')` aracılığıyla), `Core\Request`.
- `Core\RateLimiter` → `Core\Cache` (`Container`'dan çözümlenir).
- `Core\Auth` → `Core\Contracts\UserProvider` / `Authenticatable` (`config/auth.php`'den somutlaştırma), oturumlar, çerezler.
- `Core\View` → `League\Plates\Engine`, `Core\Csrf`, `Core\Csp`, `Core\Profiler\Profiler`, `route()/asset()/old()` yardımcıları.
- `Core\Cache`, `Core\Logger`, `Core\Database`, `Core\View` → `Core\DebugBar` (profilleme kancaları, `UMAY_PROFILING` ile korumalı).
- `Core\Providers\FacadeServiceProvider` → cache/auth/logger/route/database/dispatcher/view/rate-limiter/validator için singleton'ları bağlar.

## Konteyner Tarafından Çözümlenen Servisler (singletons)
`Cache`, `Auth`, `Logger`, `Route`, `Database`, `Events\Dispatcher`, `View`, `RateLimiter`, `Validator` (proxy) — bkz. `DOCS/SERVICE_PROVIDERS.md` ve `DOCS/SERVICE_MATRIX.md`.

## Çapraz Referanslar
- `DOCS/PACKAGE_STRUCTURE.md`, `DOCS/CONTAINER.md`, `DOCS/SERVICE_PROVIDERS.md`

## Kaynak Referansları
- `composer.json:26-38`
- `core/RateLimiter.php:30-35`, `core/Providers/FacadeServiceProvider.php`, `config/middleware.php:163-166`, `config/app.php:45`
