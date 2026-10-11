# Servis Matrisi (Service Matrix)

## Amaç
Konteynerde kayıtlı servisleri ve nasıl çözümlendiklerini listeler.

## Genel Bakış
`Core\Providers\FacadeServiceProvider::register()` çekirdek servisleri `Core\Container`'da **singleton** olarak bağlar. Bunlar `Container::make()` aracılığıyla — doğrudan veya facade'ları (`Core\Facades\*`) üzerinden çözümlenir; kısa takma adlar `boot()` aşamasında `config('app.aliases')`'den kaydedilir.

## Kayıtlı Singleton'lar
| Servis (soyut) | Somut / Fabrika | Yaşam Süresi | Çözümleme Yolu | Kaynak |
|--------------------|--------------------|----------|--------------|--------|
| `Core\Cache` | `new Cache` | singleton | `Cache` facade / `make()` | `core/Providers/FacadeServiceProvider.php:48` |
| `Core\Auth` | `new Auth` | singleton | `Auth` facade / `make()` | `:52` |
| `Core\Logger` | `new Logger` | singleton | `Log` facade / `make()` | `:56` |
| `Core\Route` | `new Route(null, null)` | singleton | `Route` facade / `make()` | `:65` |
| `Core\Database` | `new Database` | singleton | `DB` facade / `make()` | `:74` |
| `Core\Events\Dispatcher` | `Dispatcher::getInstance()` | singleton | `Event` facade / `make()` | `:78` |
| `Core\Validator` | anonim proxy $\to$ `Validator::make()` | singleton | `Validator` facade / `make()` | `:89` |
| `Core\View` | `new View` | singleton | `View` facade / `make()` | `:99` |
| `Core\RateLimiter` | `new RateLimiter` | singleton | `RateLimiter` facade / `make()` | `:103` |

Ayrıca çalışma zamanında şunlar bağlanır: `Core\Request` (`Application::captureRequest()` aracılığıyla bir örnek olarak) ve `Core\ExceptionHandler` (bağlanmışsa konteynerden çözümlenir, aksi takdirde örneklendirilir).

## Facade Takma Adları (`config/app.php` $\to$ `aliases`)
`Cache`, `Auth`, `Log`, `DB`, `Event`, `Validator`, `RateLimiter` (Route ve View takma adları kasıtlı olarak devre dışı bırakılmıştır).

## Çapraz Referanslar
- `DOCS/CONTAINER.md`, `DOCS/SERVICE_PROVIDERS.md`, `DOCS/core/Providers/FacadeServiceProvider.md`, `DOCS/core/Support/Facade.md`

## Kaynak Referansları
- `core/Providers/FacadeServiceProvider.php:37-124`
- `config/app.php:106-116`, `core/Application.php:159-165`
