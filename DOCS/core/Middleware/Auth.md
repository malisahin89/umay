# Dosya Raporu: core/Middleware/Auth.php

## Amaç
`auth` middleware takma adıyla çözülen, session tabanlı web rotaları için kimlik doğrulama guard'ı.

## Genel Bakış
Misafir istekleri yönlendirir: istek JSON bekliyorsa (AJAX / `Accept: application/json` / Bearer token) `401` JSON yanıtı, aksi halde `config('auth.login_path', '/login')` yoluna (`RedirectException`) yönlendirilir. Kimliği doğrulanmış istekler `$next($request)` ile geçer.

## Dosya Konumu
`core/Middleware/Auth.php`

## İsim Uzayı
`Core\Middleware`

## Sınıflar
- `class Auth implements MiddlewareInterface`

## Metotlar
- `handle(Request $request, \Closure $next): mixed`:
    1. `Container`'dan `Core\Auth` guard'ını çözer.
    2. `check()` true ise `$next($request)` ile devam eder.
    3. Misafir istek JSON bekliyorsa `abort(401, ...)`.
    4. Web isteğinde `config('auth.login_path')` yoluna `redirect()` (RedirectException fırlatır).

## Bağımlılıklar
- `Core\Contracts\MiddlewareInterface` (Uygular)
- `Core\Request` (Kullanır)
- `Core\Auth` (Kullanır)

## Kaynak Referansları
- `core/Middleware/Auth.php:1-50`