# Dosya Raporu: stubs/middleware.stub

## Amaç
Bir HTTP ara yazılımı (middleware) için kod oluşturma şablonu.

## Genel Bakış
Konsol oluşturucusu tarafından `App\Middleware` altında, `Core\Contracts\MiddlewareInterface` arayüzünü uygulayan ve isteği `$next` kapatmasına (closure) yönlendiren bir `handle()` metoduna sahip yeni bir ara yazılım iskeleti oluşturmak için kullanılan şablon.

## Dosya Konumu
`stubs/middleware.stub`

## Oluşturulan Artifakt
- **Ad Alanı (Namespace):** `App\Middleware`
- **Sınıf:** `{{ClassName}} implements Core\Contracts\MiddlewareInterface`
- **İçe Aktarmalar (Imports):** `Core\Contracts\MiddlewareInterface`, `Core\Request`
- **Metotlar:** `handle(Request $request, \Closure $next): mixed`

## Yer Tutucular
- `{{ClassName}}` — oluşturulan ara yazılım sınıfı adı (`Middleware` son eki oluşturucu tarafından zorunlu kılınır).

## Çapraz Referanslar
- **Kullanan:** `Core\Console\Kernel::renderStub()` — `core/Console/Kernel.php:232`, `core/Console/Kernel.php:699-703`
- **Sözleşmeyi Uygular:** `Core\Contracts\MiddlewareInterface` (bakınız `DOCS/core/Contracts/MiddlewareInterface.md`)

## Kaynak Referansları
- `stubs/middleware.stub:1-18`
- `core/Console/Kernel.php:213-233`
