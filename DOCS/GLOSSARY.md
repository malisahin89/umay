# Sözlük (Glossary)

## Amaç
Analiz edilen kaynaktan türetilen Umay'a özgü terminolojiyi ve dahili bileşen adlarını tanımlar.

## Terimler
- **Umay** — sıfırdan geliştirilmiş bu minimal PHP MVC framework'ü (ad alanı `Core\`).
- **Application** (`Core\Application`) — konteyner üzerinde çalışan önyükleme orkestratörü; servis sağlayıcıları kaydeder ve başlatır.
- **Container** (`Core\Container`) — PSR-11 bağımlılık enjeksiyonu/IoC konteyneri; singleton'ları, örnekleri, otomatik kablolamayı (union tipleri dahil) ve döngüsel bağımlılık tespitini destekler.
- **Servis Sağlayıcı (Service Provider)** (`Core\ServiceProvider`) — servisleri konteynere bağlayan bir `register()`/`boot()` birimidir.
- **Facade** (`Core\Support\Facade`) — çağrıları konteynerden çözümlenmiş bir örneğe ileten statik proxy; kısa takma adlar (örneğin `Cache`), `config('app.aliases')` üzerinden kaydedilir.
- **Middleware grubu** — `config/middleware.php` içindeki, yönlendirici tarafından uygulanan isimlendirilmiş middleware listeleridir (`web`, `api`, `global`).
- **Middleware ad alanları (namespaces)** — bir middleware ismini bir sınıfa çözümlemek için kullanılan sıralı sınıf adı şablonlarıdır (`App\Middleware\{name}Middleware`, `Core\Middleware\{name}`).
- **api_prefix** — API rotaları için URL öneki (varsayılan `/api`).
- **Guard** (`Core\Auth`) — `UserProvider`/`Authenticatable` sözleşmeleri aracılığıyla bağımsızlaştırılmış, istek kapsamlı bir kimlik doğrulama servisidir.
- **UserProvider** (`Core\Contracts\UserProvider`) — "giriş beyni"; kullanıcıları arar ve doğrular. Varsayılan: `EloquentUserProvider`.
- **Kişisel erişim token'ı / yetenekler** — `ApiAuth` tarafından zorunlu kılınan, kapsamlı yeteneklere sahip Bearer API token'larıdır (`HasApiTokens`, `PersonalAccessToken`); `*` = tüm yetenekler.
- **CSRF token** — `VerifyCsrfToken` tarafından doğrulanan, oturum başına hex token'dır (`Core\Csrf`); giriş yapıldığında döndürülür.
- **CSP nonce** (`Core\Csp`) — İçerik Güvenliği Politikası (CSP) başlığı için istek bazlı nonce'dır, görünüm `nonce()` yardımcısı tarafından okunur.
- **Güvenilir proxy'ler** — `config('app.trusted_proxies')`; yalnızca bunlar `X-Forwarded-For` üzerinden gerçek istemci IP'sini ayarlayabilir (`get_real_ip()`).
- **`remember_me` çerezi** — kalıcı giriş çerezi (`userId:token`); yalnızca token hash'i sunucu tarafında saklanır.
- **Önbellek `atomic()`** — süreçler arası bir kova kilidi altında yarışmasız oku-değiştir-yaz işlemidir; kapalı hata verir (hız sınırlayıcı tarafından kullanılır, TOCTOU-güvenlidir).
- **`remember()`** — bir callback sonucunu belirli bir TTL için önbelleğe alır (memoize).
- **DebugBar / Profiler** — sorguları/önbelleği/günlükleri/görünümleri/istisnaları toplayan geliştirme tanılama aracıdır; `UMAY_PROFILING` ile kontrol edilir.
- **UMAY_PROFILING** — ön kontrolcüde bir kez `Profiler::isEnabled()` değerine ayarlanan küresel sabittir.
- **TerminateException / RedirectException** — normal sonlanmayı (örneğin bir yönlendirmeden sonra) belirten kontrol akış istisnalarıdır; sessizce işlenir.
- **Flash / eski girdi (old input)** — görünüm işleme sırasında tüketilen, tek işlem ömürlü oturum verileridir (`_flash`, `_flash_errors`, `_old`) (PRG deseni).
- **Stub** — konsol `make:*` komutları tarafından kullanılan `stubs/` altındaki kod üretim şablonudur.
- **Capsule** — `Core\Database` tarafından sarmalanan Illuminate veritabanı yöneticisidir.
- **Plates** — `Core\View` tarafından sarmalanan League şablon motorudur.

## Çapraz Referanslar
- `DOCS/ARCHITECTURE.md`, `DOCS/FRAMEWORK_FEATURES.md` ve yukarıda referans verilen alt sistem raporları.

## Kaynak Referansları
- `core/`, `config/`, `public/index.php` ve `stubs/` dizinlerinden türetilmiştir (bağlantılı raporlara bakın).
