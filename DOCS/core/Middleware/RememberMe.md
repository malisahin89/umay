# Dosya Raporu: core/Middleware/RememberMe.php

## Amaç
"Beni hatırla" çerezlerini kullanarak oturum geri yükleme middleware'i.

## Genel Bakış
Gelen isteklerde `remember_me` çerezini kontrol eder. Bulunursa ve geçerliyse, kullanıcıyı otomatik olarak tekrar giriş yaptırarak tarayıcı oturumları arasında kesintisiz bir deneyim sağlar.

## Dosya Konumu
`core/Middleware/RememberMe.php`

## Ad Alanı
`Core\Middleware`

## Sınıflar
- `class RememberMe implements MiddlewareInterface`

## Metotlar
- `handle(Request $request, \Closure $next): mixed`:
    1. Kullanıcı zaten giriş yapmışsa, bir sonraki işleyiciye geçer.
    2. Giriş yapılmamışsa, `remember_me` çerezini arar.
    3. Çerez bulunursa, kullanıcıyı doğrulamak için `UserProvider::retrieveByToken()` metodunu kullanır.
    4. Başarılı olursa, oturumu geri yüklemek için `Auth::login($user, true)` çağrılır.

## Bağımlılıklar
- `Core\Contracts\MiddlewareInterface` (Uygular)
- `Core\Request` (Kullanır)
- `Core\Auth` (Kullanır)

## Kaynak Referansları
- `core/Middleware/RememberMe.php:1-125`
