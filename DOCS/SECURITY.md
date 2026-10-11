# Güvenlik

## Amaç
Framework'ün güvenlik mekanizmalarını birleştirir.

## Genel Bakış
Umay'ın savunmaları; CSRF koruması, güvenlik başlıkları ile nonce tabanlı İçerik Güvenliği Politikası (CSP), hız sınırlama, girdi doğrulaması/temizleme, günlük enjeksiyonu savunması ve oturum/token sertleştirmesini kapsar.

## CSRF Koruması
- `Core\Csrf`: `$_SESSION['csrf_token']` içinde saklanan 64 karakterli hex token'ı oluşturur; `check()` metodu `hash_equals` kullanır ve dize olmayan/boş/eşleşmeyen token'ları reddeder.
- `web` grubundaki `Core\Middleware\VerifyCsrfToken` tarafından zorunlu kılınır.
- Giriş yapıldığında token döndürülür (`Core\Auth::login` ile `csrf_token` temizlenir).

## Güvenlik Başlıkları ve CSP (`Core\Middleware\SecurityHeaders`)
- `X-Content-Type-Options: nosniff`, `X-Frame-Options: DENY`, `Referrer-Policy: strict-origin-when-cross-origin`, `X-XSS-Protection: 0` (eski filtre kasıtlı olarak devre dışı bırakılmıştır).
- **HSTS** yalnızca aktif HTTPS üzerinden gönderilir ve yerel ortamda asla gönderilmez (`max-age=31536000; includeSubDomains`).
- **CSP** nonce tabanlıdır: `local` ortamı `unsafe-inline`'a izin verir; production/staging katı bir politika kullanır (`object-src 'none'`, `base-uri 'self'`, `form-action 'self'`, `frame-ancestors 'none'`). Nonce, eşzamanlı istek yarışlarını önlemek için oturum paylaşımı yerine istek bazlıdır (`Core\Csp`).
- Production ortamındaki HTTP$\to$HTTPS yönlendirmesi, host bilgisini istemci `Host` başlığından değil, `config('app.url')`'den alır (host-header injection savunması).

## Hız Sınırlama (Rate Limiting)
- `Core\RateLimiter` (önbellek destekli) `hit`/`tooManyAttempts`/`clear`/`remaining`/`availableIn` metodlarına ve isimlendirilmiş sınırlayıcılara (`for`) sahiptir.
- Sayaç artırımları `Cache::atomic` (süreçler arası kilit, kapalı hata verme) kullanır $→$ TOCTOU-güvenlidir.
- `App\Middleware\ThrottleMiddleware` bunu `throttle:max,decay` olarak sunar.
- `Core\Auth::attempt` login denemelerini **kimlik + IP** başına **5 deneme / 300sn** ile sınırlar (`throttleKey`, anahtar `login_<sha256(kimlik|IP)>`); bilinmeyen kullanıcılarda yanıt süresini eşitlemek için sabit zamanlı (dedikodu önleyici) sahte `password_verify` çalıştırır.

## Girdi Doğrulaması ve Temizleme
- `Core\Validator` dizi değerlerini reddeder, uzunluk/eşitlik kuralları için ham değerleri ve temizlenmiş değerleri doğru şekilde kullanır ve sorgulamadan önce `unique`/`exists` kurallarındaki tablo/sütun adlarını (regex izin listesi ile) temizler.

## Günlük Enjeksiyonu Savunması
- `Core\Logger`, yazmadan önce mesajdan, IP'den ve user-agent'tan CR/LF karakterlerini temizler.
- `Core\Logger`, context içindeki credential benzeri anahtarları (parola, token, secret, api anahtarı, cookie/session vs.) özyinelemeli olarak `***` ile maskeler; ham sırlar diske asla yazılmaz.

## Kimlik Doğrulama Sertleştirme
- Giriş yapıldığında `session_regenerate_id(true)` (fixation savunması); hatırlama token'ı hash'li **ve sunucu tarafı süreli** (`hash(sha256, token)|expiryUnix`) saklanır; çerez `HttpOnly`/`SameSite=Strict`'tir. Süresi geçmiş token, çerezin kendi süresi atlatılsa bile reddedilir. `logout()` depodaki token'ı da siler (çalınan çerez o anda geçersizleşir). Eski süresiz `hash` değerleri geriye dönük uyumluluk için hâlâ kabul edilir.

## Güvenilir Proxy'ler / Gerçek IP
- `config('app.trusted_proxies')` + `get_real_ip()` fonksiyonları, hız sınırlama/günlükleme için `X-Forwarded-For` sahteciliğini önler (bkz. `DOCS/core/helpers.md`).

## Çapraz Referanslar
- `DOCS/AUTHENTICATION.md`, `DOCS/AUTHORIZATION.md`, `DOCS/VALIDATION.md`, `DOCS/CACHE.md`
- `Core\Csrf`, `Core\Csp`, `Core\Middleware\SecurityHeaders`, `Core\RateLimiter`

## Kaynak Referansları
- `core/Csrf.php:9-40`, `core/Csp.php:24-45`, `core/Middleware/SecurityHeaders.php:14-101`
- `core/RateLimiter.php:62-104`, `core/Validator.php:93-101`, `core/Validator.php:300-303`, `core/Logger.php:50-130`, `core/Auth.php:189-227,309-417`
