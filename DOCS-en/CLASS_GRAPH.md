# Class Graph

## Purpose
Documents key inheritance and interface-implementation relationships. See `DOCS/CLASS_INDEX.md` for the full class listing.

## Inheritance (extends)
- `Core\Model` → `Illuminate\Database\Eloquent\Model`; `App\Models\User` → `Core\Model`.
- `Core\RedirectException` → `Core\TerminateException` → `\Exception`.
- Application service providers → `Core\ServiceProvider`: `App\Providers\EventServiceProvider`, `App\Providers\RouteServiceProvider`, `Core\Providers\FacadeServiceProvider`, `Core\EventServiceProvider`.
- Facades → `Core\Support\Facade`: `Auth`, `Cache`, `DB`, `Event`, `Log`, `RateLimiter`, `Route`, `Validator`, `View` (under `Core\Facades\`).
- Events/Listeners → `Core\Events\Event` / `Core\Events\Listener`.
- Generated app classes extend: `App\Controllers\Controller`, `Core\FormRequest`, `Core\Mail\Mailable`, `Core\Factory`, `Core\Seeder`, `Core\Migration`.
- Tests → `Tests\TestCase` → `PHPUnit\Framework\TestCase`.

## Interface Implementation (implements)
- `Core\Contracts\MiddlewareInterface`: `SecurityHeaders`, `VerifyCsrfToken`, `RememberMe`, `Cors`, `ApiAuth` (`Core\Middleware\*`), `App\Middleware\ThrottleMiddleware`.
- `Core\Contracts\UserProvider`: `Core\Auth\EloquentUserProvider`.
- `Core\Contracts\Authenticatable`: `App\Models\User` (via `Core\Auth\HasApiTokens` and model contract).
- `Core\Contracts\MailTransport`: `Core\Mail\Transport\LogTransport`.
- `Core\Exceptions\ContainerException` / `EntryNotFoundException`: PSR-11 container exception interfaces.
- `Core\Profiler\Contracts\DataCollectorInterface`: profiler collectors.

## Traits
- `Core\Concerns\SoftDeletes` — used by soft-deletable models.
- `Core\Auth\HasApiTokens` — used by API-token-bearing models (e.g. `App\Models\User`).

## Composition (notable)
- `Core\Application` holds a `Core\Container` and a `ServiceProvider[]`.
- `Core\RateLimiter` holds a `Core\Cache`.

## Cross References
- `DOCS/CLASS_INDEX.md`, `DOCS/DEPENDENCY_GRAPH.md`, `DOCS/core/index.md`

## Source References
- `core/Model.php:27`, `core/RedirectException.php`, `core/TerminateException.php`, `core/Support/Facade.php`, `core/Contracts/`, `core/Middleware/`
