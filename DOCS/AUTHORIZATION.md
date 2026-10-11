# Yetkilendirme

## Amaç
Erişim kontrolünü belgeler: API için token yetenekleri (scopes).

## Genel Bakış
Umay'ın doğrulanmış yetkilendirme mekanizması, API kimlik doğrulama middleware'i tarafından uygulanan kişisel erişim token'larındaki **token yetenekleridir**. Analiz edilen çekirdekte ayrı bir rol/politika/kapı (gate) sistemi yoktur; web yetkilendirmesi middleware'ler ve kontrolcülerdeki `abort()` aracılığıyla ifade edilir.

## Token Yetenekleri (API)
`Core\Middleware\ApiAuth`, `Core\Auth\HasApiTokens`, `Core\Auth\PersonalAccessToken` ve onların testlerinden doğrulanmıştır:
- Token'lar `HasApiTokens::createToken('name', ['ability', …])` aracılığıyla oluşturulur ve **hashlenmiş** olarak (`PersonalAccessToken`) saklanır.
- `Core\Middleware\ApiAuth`, rota başına `api-auth` veya `api-auth:ability` olarak uygulanır:
  - `api-auth` — geçerli ve süresi dolmamış bir token gerektirir; eksik/geçersiz/süresi dolmuş token'lar **401** ile reddedilir.
  - `api-auth:ability` — ek olarak token'ın `ability` yeteneğine sahip olmasını gerektirir.
  - Joker karakterli bir yetenek (`*`), her yetkiyi verir.
- Başarılı olduğunda, token sahibi `Auth::setUser()` aracılığıyla mevcut kullanıcı olarak ayarlanır ve `last_used_at` kaydedilir.

## Web Yetkilendirmesi
- Rota/middleware grupları erişimi kısıtlar (`web`/`api`); kontrolcüler `abort(403|404|…)` yapabilir (`core/helpers.php` `abort()` $\rightarrow$ `HttpException` bakın), bunu `Core\ExceptionHandler` bir hata sayfası/JSON olarak işler.

## Çapraz Referanslar
- `DOCS/core/Middleware/ApiAuth.md`, `DOCS/core/Auth/HasApiTokens.md`, `DOCS/core/Auth/PersonalAccessToken.md`
- `DOCS/AUTHENTICATION.md`, `DOCS/tests/Unit/ApiAuthTest.md`, `DOCS/ERROR_HANDLING.md`

## Kaynak Referansları
- `core/Middleware/ApiAuth.php:1-83`
- `core/Auth/HasApiTokens.php:1-111`, `core/Auth/PersonalAccessToken.php:1-105`
- `tests/Unit/ApiAuthTest.php:119-151`
