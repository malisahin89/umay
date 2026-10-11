# Kimlik Doğrulama

## Amaç
Oturum tabanlı ve token tabanlı kimlik doğrulamayı belgeler.

## Genel Bakış
`Core\Auth`, konteynerden çözülen (`Auth` facade'ı aracılığıyla), istek kapsamlı ve önbelleğe alınmış bir kimlik doğrulama guard'ıdır. `App\Models\User`'dan tamamen bağımsızdır: yalnızca `Core\Contracts\UserProvider` ve `Core\Contracts\Authenticatable` ile iletişim kurar; somut sınıflar `config/auth.php` içinde seçilir.

## Sağlayıcı Bağlantıları (`config/auth.php`)
- `default`, aktif bir sağlayıcı seçer (varsayılan `eloquent`).
- Her sağlayıcının bir `driver`'ı (`UserProvider` sınıfı) vardır; eloquent driver'ı ayrıca bir `model` (`Authenticatable`) alır.
- Varsayılan: `EloquentUserProvider` + `App\Models\User`.
- `Core\Auth::provider()`, driver'ı tembelce (lazily) çözer ve `UserProvider`'ı uyguladığını doğrular; `setProvider()` geçersiz kılmaya (testler/çalışma zamanı) olanak tanır.

## Oturum Kimlik Doğrulama API'si
- Okuma: `user(): ?Authenticatable` (her istekte depoyu bir kez sorgular, ardından önbelleğe alır), `id(): int|string|null` (yerel anahtar türünü korur), `check(): bool`, `guest(): bool`.
- Yazma: `login(Authenticatable, bool $remember=false)`, `logout()`, `attempt(array $credentials): bool`.
- `login()`, oturum kimliğini yeniden oluşturur, CSRF token'ını döndürür, `user_id`/`login_time` değerlerini ayarlar ve (hatırla seçiliyse) hashlenmiş bir hatırlama token'ı saklar + `remember_me` çerezini ayarlar.
- `attempt()`, arama (`retrieveByCredentials`) ve şifre kontrolünü (`validateCredentials`) sağlayıcıya devreder.
- `setUser()`, bir kullanıcıyı yalnızca mevcut istek için kimliği doğrulanmış olarak işaretler (oturum yok) — durumsuz token guard'ları tarafından kullanılır.

## Token Kimlik Doğrulaması
- `Core\Middleware\ApiAuth`, Bearer token'larını doğrular ve istek için `Auth::setUser()`'ı çağırır (durumsuz). Yetenekler için `DOCS/AUTHORIZATION.md` ve `DOCS/core/Auth/HasApiTokens.md` / `DOCS/core/Auth/PersonalAccessToken.md` dosyalarına bakın.

## Sözleşmeler (Contracts)
- `Core\Contracts\UserProvider` — `retrieveById`, `retrieveByCredentials`, `validateCredentials`, `updateRememberToken`.
- `Core\Contracts\Authenticatable` — `getAuthIdentifier`, `getRememberToken` vb.

## Çapraz Referanslar
- `DOCS/core/Auth.md`, `DOCS/core/Auth/EloquentUserProvider.md`, `DOCS/core/Contracts/UserProvider.md`, `DOCS/core/Contracts/Authenticatable.md`
- `DOCS/config/auth.md`, `DOCS/tests/Feature/AuthTest.md`, `DOCS/tests/Unit/ApiAuthTest.md`

## Kaynak Referansları
- `core/Auth.php:38-277`
- `config/auth.php:21-48`
