# Dosya Raporu: core/Middleware/ApiAuth.php

## Amaç
Durumsuz (stateless) API kimlik doğrulama ara yazılımı.

## Genel Bakış
`Authorization` başlığındaki Bearer token'ını doğrulayarak API rotalarını korur. Geçerli bir token bulunduğunda, ilgili kullanıcı mevcut istek için `Auth` korumasına (guard) bağlanır.

## Dosya Konumu
`core/Middleware/ApiAuth.php`

## İsim Uzayı
`Core\Middleware`

## Sınıflar
- `class ApiAuth implements MiddlewareInterface`

## Metotlar
- `handle(Request $request, \Closure $next): mixed`:
    1. İstekten Bearer token'ını ayıklar.
    2. Token'ı çözmek için `PersonalAccessToken::findToken()` kullanır.
    3. Geçerli ve süresi dolmamışsa, kullanıcıyı `Auth::setUser()` ile bağlar.
    4. Geçersizse, 401 Unauthorized JSON yanıtı döndürür.
    5. Yetenek kontrollerini destekler (örneğin, `api-auth:posts.write`).

## Bağımlılıklar
- `Core\Contracts\MiddlewareInterface` (Uygular)
- `Core\Request` (Kullanır)
- `Core\Auth\PersonalAccessToken` (Kullanır)
- `Core\Auth` (Kullanır)
- `Core\Response` (Kullanır)

## Kaynak Referansları
- `core/Middleware/ApiAuth.php:1-100`
