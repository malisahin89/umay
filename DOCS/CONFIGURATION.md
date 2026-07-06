# Yapılandırma (Configuration)

## Amaç
Yapılandırmanın nasıl tanımlandığını, yüklendiğini ve erişildiğini belgeler.

## Genel Bakış
Yapılandırma dosyaları `config/` dizininde yer alır ve her biri bir PHP dizisi döndürür. Değerler genellikle ortam değişkenlerinden (`.env` üzerinden `vlucas/phpdotenv` ile yüklenir) alınır ve kod içinde varsayılan değerlere sahiptir. Yapılandırmaya küresel `config('dosya.anahtar', $varsayilan)` yardımcısı (nokta notasyonu) aracılığıyla erişilir.

## Yapılandırma Dosyaları
| Dosya | Sorumluluk | Örnek Anahtarlar |
|------|---------|--------------|
| `config/app.php` | Uygulama kimliği ve ortam | `name`, `version`, `url`, `env`, `debug`, `timezone`, `key`, `controller_namespace`, `trusted_proxies`, `aliases` |
| `config/auth.php` | Kimlik doğrulama sağlayıcıları | `default`, `providers.<name>.driver`, `providers.<name>.model` |
| `config/cache.php` | Dosya önbelleği | `path`, `prefix`, `default_ttl` |
| `config/database.php` | Veritabanı bağlantısı | (sürücü/host/port/veritabanı/kimlik bilgileri/karakter seti/karşılaştırma) |
| `config/mail.php` | E-posta iletimi | (mailer ayarları) |
| `config/middleware.php` | Middleware grupları ve CORS | `api_prefix`, `global`, `web`, `api`, `cors_*`, `namespaces`, `aliases` |
| `config/profiler.php` | Profiler | (etkinleştirme/ayarlar) |
| `config/session.php` | Oturum/çerez | `lifetime`, `cookie`, `secure`, `http_only`, `same_site` |

## Erişim Deseni
- PHP'de `config('app.name')` ve Plates şablonlarında `$this->config('app.name')`.
- `Core\Application` boot süreci ve `Core\Auth`, `Core\Cache`, `Core\Route` gibi servisler, değerleri sabit kodlamak yerine yapılandırmadan okur (örneğin `controller_namespace`, `middleware.namespaces`, `cache.prefix`).

## Ortam Geçersiz Kılmaları (Environment Overrides)
Çoğu değer `$_ENV['...'] ?? varsayilan` şeklinde geri döner. Boolean değerler `filter_var(..., FILTER_VALIDATE_BOOLEAN)` kullanır; listeler (örneğin `trusted_proxies`) virgülle ayrılır ve temizlenir.

## Çapraz Referanslar
- **Yapılandırma Matrisi:** bkz. `DOCS/CONFIGURATION_MATRIX.md`
- **Yardımcı:** `core/helpers.php` içindeki `config()` (bkz. `DOCS/core/helpers.md`)
- **Dosya bazlı yapılandırma raporları:** `DOCS/config/index.md`

## Kaynak Referansları
- `config/app.php:13-117`, `config/auth.php:21-48`, `config/cache.php:12-30`, `config/session.php:5-28`, `config/middleware.php:36-186`
