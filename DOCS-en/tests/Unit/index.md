# Directory Report: tests/Unit

## Purpose
Unit tests covering individual framework components in isolation.

## Child Directories
- None.

## Source Files
- `ApplicationTest.php` — `Core\Application` (see `ApplicationTest.md`)
- `ApiAuthTest.php` — API token auth (see `ApiAuthTest.md`)
- `CacheTest.php` — `Core\Cache` (see `CacheTest.md`)
- `ContainerFixTest.php` — `Core\Container` edge cases (see `ContainerFixTest.md`)
- `CsrfTest.php` — `Core\Csrf` (see `CsrfTest.md`)
- `DatabaseFixTest.php` — `Core\Database` (see `DatabaseFixTest.md`)
- `EventDispatcherTest.php` — `Core\Events\Dispatcher` (see `EventDispatcherTest.md`)
- `ExceptionClassesTest.php` — exception classes (see `ExceptionClassesTest.md`)
- `ExceptionFixTest.php` — exception relationships (see `ExceptionFixTest.md`)
- `FacadeServiceProviderTest.php` — `Core\Providers\FacadeServiceProvider` (see `FacadeServiceProviderTest.md`)
- `FacadeTest.php` — `Core\Support\Facade` (see `FacadeTest.md`)
- `FileUploadTest.php` — `Core\FileUpload` (see `FileUploadTest.md`)
- `HelpersTest.php` — `core/helpers.php` (see `HelpersTest.md`)
- `LoggerTest.php` — `Core\Logger` (see `LoggerTest.md`)
- `MailerTest.php` — `Core\Mail\Mailer` (see `MailerTest.md`)
- `MiddlewareInterfaceTest.php` — middleware contract (see `MiddlewareInterfaceTest.md`)
- `PaginatorTest.php` — `Core\Paginator` (see `PaginatorTest.md`)
- `RateLimiterTest.php` — `Core\RateLimiter` (see `RateLimiterTest.md`)
- `RequestFixTest.php` — `Core\FormRequest` / JSON body (see `RequestFixTest.md`)
- `RequestTest.php` — `Core\Request` (see `RequestTest.md`)
- `ResponseBuilderTest.php` — `Core\ResponseBuilder` (see `ResponseBuilderTest.md`)
- `RouteExtendedTest.php` — `Core\Route` (see `RouteExtendedTest.md`)
- `RouteFixTest.php` — resource routing / dispatch (see `RouteFixTest.md`)
- `ValidatorExtendedTest.php` — `Core\Validator` extended rules (see `ValidatorExtendedTest.md`)
- `ValidatorTest.php` — `Core\Validator` core rules (see `ValidatorTest.md`)
- `ViewTest.php` — `Core\View` (see `ViewTest.md`)

## Public Entry Points
- Executed by PHPUnit (see `DOCS/phpunit.xml.md`).

## Internal Dependencies
- All classes extend `Tests\TestCase` and exercise `Core\*` components and `core/helpers.php`.

## External Dependencies
- PHPUnit.

## Cross References
- **Base Class:** `Tests\TestCase` (see `DOCS/tests/TestCase.md`)

## Source References
- `tests/Unit/`
