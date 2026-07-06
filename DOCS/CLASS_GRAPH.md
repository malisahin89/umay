# Sınıf Grafiği

## Amaç
Temel kalıtım ve arayüz-uygulama ilişkilerini belgeler. Tüm sınıf listesi için `DOCS/CLASS_INDEX.md` dosyasına bakın.

## Kalıtım (extends)
- `Core\Model` $\rightarrow$ `Illuminate\Database\Eloquent\Model`; `App\Models\User` $\rightarrow$ `Core\Model`.
- `Core\RedirectException` $\rightarrow$ `Core\TerminateException` $\rightarrow$ `\Exception`.
- Uygulama servis sağlayıcıları $\rightarrow$ `Core\ServiceProvider`: `App\Providers\EventServiceProvider`, `App\Providers\RouteServiceProvider`, `Core\Providers\FacadeServiceProvider`, `Core\EventServiceProvider`.
- Facade'lar $\rightarrow$ `Core\Support\Facade`: `Auth`, `Cache`, `DB`, `Event`, `Log`, `RateLimiter`, `Route`, `Validator`, `View` (`Core\Facades\` altında).
- Event'ler/Listener'lar $\rightarrow$ `Core\Events\Event` / `Core\Events\Listener`.
- Oluşturulan uygulama sınıfları şunları genişletir: `App\Controllers\Controller`, `Core\FormRequest`, `Core\Mail\Mailable`, `Core\Factory`, `Core\Seeder`, `Core\Migration`.
- Testler $\rightarrow$ `Tests\TestCase` $\rightarrow$ `PHPUnit\Framework\TestCase`.

## Arayüz Uygulama (implements)
- `Core\Contracts\MiddlewareInterface`: `SecurityHeaders`, `VerifyCsrfToken`, `RememberMe`, `Cors`, `ApiAuth` (`Core\Middleware\*`), `App\Middleware\ThrottleMiddleware`.
- `Core\Contracts\UserProvider`: `Core\Auth\EloquentUserProvider`.
- `Core\Contracts\Authenticatable`: `App\Models\User` (`Core\Auth\HasApiTokens` ve model sözleşmesi aracılığıyla).
- `Core\Contracts\MailTransport`: `Core\Mail\Transport\LogTransport`.
- `Core\Exceptions\ContainerException` / `EntryNotFoundException`: PSR-11 konteyner istisna arayüzleri.
- `Core\Profiler\Contracts\DataCollectorInterface`: profiler toplayıcıları.

## Trait'ler
- `Core\Concerns\SoftDeletes` — yumuşak silinebilir (soft-deletable) modeller tarafından kullanılır.
- `Core\Auth\HasApiTokens` — API token taşıyan modeller tarafından kullanılır (örneğin `App\Models\User`).

## Kompozisyon (Önemli)
- `Core\Application`, bir `Core\Container` ve `ServiceProvider[]` tutar.
- `Core\RateLimiter`, bir `Core\Cache` tutar.

## Çapraz Referanslar
- `DOCS/CLASS_INDEX.md`, `DOCS/DEPENDENCY_GRAPH.md`, `DOCS/core/index.md`

## Kaynak Referansları
- `core/Model.php:27`, `core/RedirectException.php`, `core/TerminateException.php`, `core/Support/Facade.php`, `core/Contracts/`, `core/Middleware/`
