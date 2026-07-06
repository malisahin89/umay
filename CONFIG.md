# Umay Framework - Config Directory (Yapılandırma)

`config/` dizini, uygulamanın tüm yapılandırma ayarlarını barındıran PHP dizilerini içerir. Bu dizindeki tüm ayarlar, `.env` dosyasındaki çevre değişkenlerinden beslenir.

## Ana Yapılandırma Dosyaları

- **`app.php`**: Uygulamanın adı, URL'i, zaman dilimi, hata ayıklama (debug) modu, güvenlik anahtarı (APP_KEY) gibi temel framework ayarları. Ayrıca `controller_namespace` — router'ın string action'lara eklediği önek (varsayılan `App\Controllers\`).
- **`auth.php`**: Kimlik doğrulama yapılandırması. Çekirdek (`Core\Auth`), somut bir kullanıcı sınıfına değil bu dosyaya bakar:
  - `default` → aktif provider'ın adı (aşağıdaki `providers` listesinden).
  - `providers` → her biri bir `driver` (`Core\Contracts\UserProvider` sınıfı) içerir; varsayılan `eloquent` driver'ı ayrıca bir `model` (`Core\Contracts\Authenticatable` implemente eden, örn. `App\Models\User`) bekler.
  - Kendi login mantığınızı yazmak için `Core\Contracts\UserProvider` implemente edip bir provider'ın `driver`'ı olarak gösterin — çekirdeğe dokunmadan.
- **`database.php`**: Veritabanı bağlantı bilgileri. Sadece MySQL/MariaDB değil, test ortamı için SQLite (memory) bağlantı yapılandırmaları da burada yer alır.
- **`cache.php`**: Dosya tabanlı cache ayarları (HMAC imzalı): `path` (dizin), `prefix` (anahtar öneki) ve `default_ttl` (varsayılan ömür, saniye).
- **`mail.php`**: Driver tabanlı mail yapılandırması. `default` aktif mailer'ı, `mailers` her birinin `transport`'unu (`Core\Contracts\MailTransport` sınıfı), `from` ise varsayılan göndericiyi belirler. Framework yalnızca `log` transport'u ile gelir (maili `storage/logs`'a yazar); gerçek gönderim için kendi transport'unuzu yazıp ekleyin — bkz. `docs/digging-deeper/mail.md`.
- **`middleware.php`**: `web` ve `api` middleware grupları, `api_prefix`, CORS kaynağı ve `namespaces` — router'ın middleware adlarını sınıflara çözerken kullandığı şablonlar (varsayılan `App\Middleware\{name}Middleware` → `Core\Middleware\{name}`).
- **`profiler.php`**: Geliştirme profiler'ı/DebugBar ayarları (etkinlik, TTL, IP beyaz listesi).
- **`session.php`**: Oturum yönetimi, yaşam süresi (lifetime) ve çerez güvenlik ayarları (Secure, HttpOnly, SameSite).

> Tüm ayar değerlerine framework içerisinde `config('dosya.anahtar')` helper fonksiyonu ile erişilebilir. Ör: `config('app.timezone')`.
