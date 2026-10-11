# Dizin Raporu: tests/Unit

## Amaç
Çerçeve bileşenlerini tek başına kapsayan birim (unit) testler.

## Alt Dizinler
- Yok.

## Kaynak Dosyalar
- `ApplicationTest.php` — `Core\Application` (bkz. `ApplicationTest.md`)
- `ApiAuthTest.php` — API token kimlik doğrulama (bkz. `ApiAuthTest.md`)
- `CacheTest.php` — `Core\Cache` (bkz. `CacheTest.md`)
- `ContainerFixTest.php` — `Core\Container` uç durumları (bkz. `ContainerFixTest.md`)
- `CsrfTest.php` — `Core\Csrf` (bkz. `CsrfTest.md`)
- `DatabaseFixTest.php` — `Core\Database` (bkz. `DatabaseFixTest.md`)
- `EventDispatcherTest.php` — `Core\Events\Dispatcher` (bkz. `EventDispatcherTest.md`)
- `ExceptionClassesTest.php` — istisna sınıfları (bkz. `ExceptionClassesTest.md`)
- `ExceptionFixTest.php` — istisna ilişkileri (bkz. `ExceptionFixTest.md`)
- `FacadeServiceProviderTest.php` — `Core\Providers\FacadeServiceProvider` (bkz. `FacadeServiceProviderTest.md`)
- `FacadeTest.php` — `Core\Support\Facade` (bkz. `FacadeTest.md`)
- `FileUploadTest.php` — `Core\FileUpload` (bkz. `FileUploadTest.md`)
- `HelpersTest.php` — `core/helpers.php` (bkz. `HelpersTest.md`)
- `LoggerTest.php` — `Core\Logger` (bkz. `LoggerTest.md`)
- `MailerTest.php` — `Core\Mail\Mailer` (bkz. `MailerTest.md`)
- `MiddlewareInterfaceTest.php` — ara yazılım sözleşmesi (bkz. `MiddlewareInterfaceTest.md`)
- `PaginatorTest.php` — `Core\Paginator` (bkz. `PaginatorTest.md`)
- `RateLimiterTest.php` — `Core\RateLimiter` (bkz. `RateLimiterTest.md`)
- `RequestFixTest.php` — `Core\FormRequest` / JSON gövdesi (bkz. `RequestFixTest.md`)
- `RequestTest.php` — `Core\Request` (bkz. `RequestTest.md`)
- `ResponseBuilderTest.php` — `Core\ResponseBuilder` (bkz. `ResponseBuilderTest.md`)
- `RouteExtendedTest.php` — `Core\Route` (bkz. `RouteExtendedTest.md`)
- `RouteFixTest.php` — kaynak rotalama / dağıtım (bkz. `RouteFixTest.md`)
- `ValidatorExtendedTest.php` — `Core\Validator` genişletilmiş kurallar (bkz. `ValidatorExtendedTest.md`)
- `ValidatorTest.php` — `Core\Validator` temel kurallar (bkz. `ValidatorTest.md`)
- `ViewTest.php` — `Core\View` (bkz. `ViewTest.md`)

## Genel Giriş Noktaları
- PHPUnit tarafından yürütülür (bkz. `DOCS/phpunit.xml.md`).

## Dahili Bağımlılıklar
- Tüm sınıflar `Tests\TestCase`'i genişletir ve `Core\*` bileşenlerini ile `core/helpers.php`'yi test eder.

## Harici Bağımlılıklar
- PHPUnit.

## Çapraz Referanslar
- **Taban Sınıf:** `Tests\TestCase` (bkz. `DOCS/tests/TestCase.md`)

## Kaynak Referansları
- `tests/Unit/`
