# Middleware Sistemi

## Amaç
HTTP middleware hattını, web/API middleware gruplarını ve middleware isimlerinin sınıflara nasıl çözümlendiğini belgeler.

## Genel Bakış
Middleware'ler, `Core\Contracts\MiddlewareInterface::handle(Request $request, \Closure $next): mixed` arayüzünü uygulayan sınıflardır. İstek işleme sürecinin etrafında çalışırlar; her biri ya isteği `$next($request)` ile iletir ya da işlemi kısa devre yaptırır. Gruplar ve çözümleme `config/middleware.php` içinde yapılandırılır ve yönlendirici (`Core\Route`) tarafından uygulanır.

## Middleware Grupları (`config/middleware.php`)
- **`global`** — her istekte çalışır (varsayılan olarak boştur; oturum/CSRF buraya eklenmemelidir).
- **`web`** — `RememberMe`, `SecurityHeaders`, `VerifyCsrfToken` (oturum tabanlı istekler, `routes/web.php`).
- **`api`** — `Cors`, `throttle:60,60` (durumsuz istekler, `routes/api.php`); oturum/CSRF hariçtir, token kimlik doğrulaması rota bazında eklenir.

`Route::setGroup('web'|'api')` bir rota dosyası için grubu seçer.

## İsim $\to$ Sınıf Çözümlemesi
Yönlendirici, StudlyCase formatındaki bir middleware ismini `config('middleware.namespaces')` içindeki sıralı şablonlarla karşılaştırarak çözer; mevcut olan ilk sınıf kazanır:

1. `App\Middleware\{name}Middleware`
2. `Core\Middleware\{name}`

Bu, çekirdek yönlendiricinin `App\`/`Core\` ad alanlarını kodlamadan middleware'leri çözümlemesini sağlar. Takma adlar (`throttle` $\to$ `Throttle`, `cors` $\to$ `Cors`), `config('middleware.aliases')` içinde tanımlanmıştır. Parametreli middleware'ler `isim:arg1,arg2` sözdizimini kullanır (örneğin `throttle:60,60`, `api-auth:permission`).

## Çekirdek Middleware'ler
| Middleware | Rol | Kaynak |
|-----------|------|--------|
| `Core\Middleware\SecurityHeaders` | Güvenlik başlıkları + nonce CSP + HSTS + HTTPS yönlendirmesi | `core/Middleware/SecurityHeaders.php` |
| `Core\Middleware\VerifyCsrfToken` | Durum değiştiren web istekleri için CSRF doğrulaması | `core/Middleware/VerifyCsrfToken.php` |
| `Core\Middleware\RememberMe` | `remember_me` çereziyle oturumu geri yükler | `core/Middleware/RememberMe.php` |
| `Core\Middleware\Cors` | API için CORS başlıkları / ön kontrol (preflight) | `core/Middleware/Cors.php` |
| `Core\Middleware\ApiAuth` | Bearer-token kimlik doğrulaması + yetenekler | `core/Middleware/ApiAuth.php` |
| `App\Middleware\ThrottleMiddleware` | Hız sınırlama (`throttle:max,decay`) | `app/Middleware/ThrottleMiddleware.php` |

> `Core\Middleware` (`core/Middleware.php`) `@deprecated` boş bir sınıftır; CSRF artık `Core\Middleware\VerifyCsrfToken` tarafından işlenmektedir.

## Çapraz Referanslar
- **Kontrat:** `Core\Contracts\MiddlewareInterface` (bkz. `DOCS/core/Contracts/MiddlewareInterface.md`)
- **Uygulayan:** `Core\Route` (bkz. `DOCS/ROUTING_SYSTEM.md`)
- **Dosya bazlı raporlar:** `DOCS/core/Middleware/index.md`

## Kaynak Referansları
- `config/middleware.php:36-186`
- `core/Middleware.php:1-14`
- `core/Middleware/SecurityHeaders.php:1-102`
