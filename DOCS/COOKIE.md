# Çerezler (Cookies)

## Amaç
Framework'ün ayarladığı çerezleri ve bunların güvenlik özniteliklerini belgeler.

## Genel Bakış
Umay özel bir çerez soyutlaması sağlamaz; çerezler PHP'nin `setcookie()` fonksiyonu (ve yerel oturum çerezleri) ile ayarlanır. İki uygulama çerezi kullanılır: oturum çerezi ve "beni hatırla" çerezi.

## Çerezler
### Oturum çerezi
- Adı `config('session.cookie')`'den gelir (varsayılan `umay_session`).
- Öznitelikler `config/session.php`'den gelir: `http_only = true`, `same_site = Strict`, HTTPS altında `secure`.
- Çıkış yapıldığında `session_get_cookie_params()` kullanılarak `setcookie(session_name(), '', past, …)` ile temizlenir.

### `remember_me` çerezi (`Core\Auth`)
- `Auth::login($user, true)` tarafından ayarlanır: değer `userId:token` şeklindedir, burada yalnızca `sha256(token)` sunucu tarafında saklanır (`UserProvider::updateRememberToken` aracılığıyla).
- Öznitelikler: `expires` = 30 gün, `path=/`, `httponly=true`, `samesite=Lax`, HTTPS altında `secure`.
- Oturumu geri yüklemek için `Core\Middleware\RememberMe` tarafından tüketilir; `Auth::logout()` sırasında temizlenir.

## Güvenlik Gözlemleri
- Her iki çerezde de `HttpOnly` (JS erişimi yok); HTTPS altında `Secure`.
- Hatırlama token'ı sunucu tarafında hashlenmiş olarak saklanır; düz metin yalnızca çerezde bulunur.
- Oturum çerezi için `SameSite=Strict`, beni hatırla çerezi için `Lax` (üst düzey navigasyonlarda hayatta kalması için) kullanılır.

## Çapraz Referanslar
- `Core\Auth` (bkz. `DOCS/AUTHENTICATION.md`), `Core\Middleware\RememberMe` (bkz. `DOCS/core/Middleware/RememberMe.md`), `config/session.php` (bkz. `DOCS/config/session.md`)

## Kaynak Referansları
- `core/Auth.php:188-238`
- `config/session.php:12-27`
