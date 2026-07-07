# Sınıf Dizini (Class Index)

## Amaç
Framework ve uygulama sınıflarını ad alanları, üst sınıfları/arayüzleri, trait'leri ve dosya konumlarıyla birlikte dizinler. İlişkiler doğrulanmışsa listelenir; `—` işareti hiç yok veya doğrulanmadı anlamına gelir.

## Core (`Core\`)
| Sınıf | Üst Sınıf | Uygular / Trait'ler | Dosya |
|-------|--------|---------------------|------|
| `Application` | — | — | `core/Application.php` |
| `Container` | — | (PSR-11 container) | `core/Container.php` |
| `Route` | — | — | `core/Route.php` |
| `Request` | — | — | `core/Request.php` |
| `Response` | — | — | `core/Response.php` |
| `ResponseBuilder` | — | — | `core/ResponseBuilder.php` |
| `Redirect` | — | — | `core/Redirect.php` |
| `ResourceRegistrar` | — | — | `core/ResourceRegistrar.php` |
| `Auth` | — | — | `core/Auth.php` |
| `Cache` | — | — | `core/Cache.php` |
| `RateLimiter` | — | — | `core/RateLimiter.php` |
| `Database` | — | — | `core/Database.php` |
| `Model` | `Illuminate\...\Model` | — | `core/Model.php` |
| `Migration` | — | — | `core/Migration.php` |
| `Migrator` | — | — | `core/Migrator.php` |
| `Seeder` | — | — | `core/Seeder.php` |
| `Factory` | — | — | `core/Factory.php` |
| `Paginator` | — | — | `core/Paginator.php` |
| `Validator` | — | — | `core/Validator.php` |
| `FormRequest` | — | — | `core/FormRequest.php` |
| `View` | — | `League\Plates\Engine` kullanır | `core/View.php` |
| `Csrf` | — | — | `core/Csrf.php` |
| `Csp` (final) | — | — | `core/Csp.php` |
| `Logger` | — | — | `core/Logger.php` |
| `ExceptionHandler` | — | — | `core/ExceptionHandler.php` |
| `DebugBar` | — | — | `core/DebugBar.php` |
| `ServiceProvider` | — | — | `core/ServiceProvider.php` |
| `EventServiceProvider` | `ServiceProvider` | — | `core/EventServiceProvider.php` |
| `Middleware` (deprecated) | — | — | `core/Middleware.php` |
| `CsrfException` | `\Exception` | — | `core/CsrfException.php` |
| `TerminateException` | `\Exception` | — | `core/TerminateException.php` |
| `RedirectException` | `TerminateException` | — | `core/RedirectException.php` |

## Core alt ad alanları
| Sınıf | Üst Sınıf / Uygular | Dosya |
|-------|---------------------|------|
| `Core\Auth\EloquentUserProvider` | `Contracts\UserProvider` uygular | `core/Auth/EloquentUserProvider.php` |
| `Core\Auth\PersonalAccessToken` | `Core\Model` (Eloquent) | `core/Auth/PersonalAccessToken.php` |
| `Core\Auth\HasApiTokens` | trait | `core/Auth/HasApiTokens.php` |
| `Core\Concerns\SoftDeletes` | trait | `core/Concerns/SoftDeletes.php` |
| `Core\Console\Kernel` | — | `core/Console/Kernel.php` |
| `Core\Contracts\Authenticatable` | arayüz | `core/Contracts/Authenticatable.php` |
| `Core\Contracts\UserProvider` | arayüz | `core/Contracts/UserProvider.php` |
| `Core\Contracts\MiddlewareInterface` | arayüz | `core/Contracts/MiddlewareInterface.php` |
| `Core\Contracts\MailTransport` | arayüz | `core/Contracts/MailTransport.php` |
| `Core\Events\Dispatcher` | — | `core/Events/Dispatcher.php` |
| `Core\Events\Event` | — | `core/Events/Event.php` |
| `Core\Events\Listener` | — | `core/Events/Listener.php` |
| `Core\Exceptions\ContainerException` | PSR-11 `ContainerExceptionInterface` uygular | `core/Exceptions/ContainerException.php` |
| `Core\Exceptions\EntryNotFoundException` | PSR-11 `NotFoundExceptionInterface` uygular | `core/Exceptions/EntryNotFoundException.php` |
| `Core\Exceptions\HttpException` | `\Exception` | `core/Exceptions/HttpException.php` |
| `Core\Facades\{Auth,Cache,DB,Event,Log,RateLimiter,Route,Validator,View}` | `Core\Support\Facade` | `core/Facades/*.php` |
| `Core\Support\Facade` | — | `core/Support/Facade.php` |
| `Core\Mail\Mailable` | — | `core/Mail/Mailable.php` |
| `Core\Mail\Mailer` | — | `core/Mail/Mailer.php` |
| `Core\Mail\Transport\LogTransport` | `Contracts\MailTransport` uygular | `core/Mail/Transport/LogTransport.php` |
| `Core\Middleware\{SecurityHeaders,VerifyCsrfToken,RememberMe,Cors,ApiAuth}` | `Contracts\MiddlewareInterface` uygular | `core/Middleware/*.php` |
| `Core\Providers\FacadeServiceProvider` | `ServiceProvider` | `core/Providers/FacadeServiceProvider.php` |
| `Core\Profiler\Profiler` | — | `core/Profiler/Profiler.php` |
| `Core\Profiler\ProfilerController` | — | `core/Profiler/ProfilerController.php` |
| `Core\Profiler\ProfilerStorage` | — | `core/Profiler/ProfilerStorage.php` |
| `Core\Profiler\Contracts\DataCollectorInterface` | arayüz | `core/Profiler/Contracts/DataCollectorInterface.php` |

## Uygulama (`App\`)
| Sınıf | Üst Sınıf / Uygular | Dosya |
|-------|---------------------|------|
| `App\Controllers\Controller` | — | `app/Controllers/Controller.php` |
| `App\Middleware\ThrottleMiddleware` | `Core\Contracts\MiddlewareInterface` uygular | `app/Middleware/ThrottleMiddleware.php` |
| `App\Models\User` | `Core\Model`; `Core\Auth\HasApiTokens` kullanır; `Core\Contracts\Authenticatable` uygular | `app/Models/User.php` |
| `App\Providers\EventServiceProvider` | `Core\ServiceProvider` | `app/Providers/EventServiceProvider.php` |
| `App\Providers\RouteServiceProvider` | `Core\ServiceProvider` | `app/Providers/RouteServiceProvider.php` |

## Veritabanı (`Database\`)
| Sınıf | Üst Sınıf | Dosya |
|-------|--------|------|
| `Database\Factories\UserFactory` | `Core\Factory` | `database/factories/UserFactory.php` |
| `Database\Seeders\DatabaseSeeder` | `Core\Seeder` | `database/seeders/DatabaseSeeder.php` |

## Testler (`Tests\`)
| Sınıf | Üst Sınıf | Dosya |
|-------|--------|------|
| `Tests\TestCase` | `PHPUnit\Framework\TestCase` | `tests/TestCase.php` |
| `Tests\Unit\*`, `Tests\Feature\AuthTest` | `Tests\TestCase` | `tests/Unit/*.php`, `tests/Feature/AuthTest.php` |

## Çapraz Referanslar
- `DOCS/CLASS_GRAPH.md`, `DOCS/METHOD_INDEX.md`, `DOCS/core/index.md`, `DOCS/app/index.md`

## Kaynak Referansları
- `core/`, `app/`, `database/`, `tests/` (her sınıfın dosya bazlı raporuna bakın)
