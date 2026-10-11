# Paket Yapısı

## Amaç
Umay projesinin disk üzerindeki düzenini ve ad alanlarını (namespaces) dizinlere eşleyen PSR-4 otomatik yüklemeyi (autoloading) açıklar.

## Genel Bakış
Umay, minimal bir PHP MVC framework'üdür (PHP ≥ 8.2). Uygulama kodu `app/` altında, framework çekirdeği `core/` altında ve destekleyici bileşenler (yapılandırma, rotalar, görünümler, veritabanı, testler) özel üst düzey dizinlerde yer alır.

## Dizin Düzeni
| Dizin | Rol |
|-----------|------|
| `app/` | Uygulama kodu: `Controllers/`, `Middleware/`, `Models/`, `Providers/`, `Services/` |
| `core/` | Framework dahili bileşenleri (yönlendirme, konteyner, ORM tabanı, kimlik doğrulama, önbellek, e-posta, profiler, …) |
| `config/` | Dizi döndüren yapılandırma dosyaları (`app`, `auth`, `cache`, `database`, `mail`, `middleware`, `profiler`, `session`) |
| `routes/` | Rota tanımları (`web.php`, `api.php`) |
| `views/` | Plates şablonları (`layouts/`, `partials/`, `errors/`) |
| `database/` | `migrations/`, `seeders/`, `factories/` |
| `stubs/` | Konsol `make:*` komutları tarafından kullanılan kod üretim şablonları |
| `public/` | Web kök dizini ve ön kontrolcü (`index.php`) |
| `storage/` | Çalışma zamanı artıkları (önbellek, günlükler, profiler) |
| `tests/` | PHPUnit test paketi (`Unit/`, `Feature/`) |

## PSR-4 Otomatik Yükleme
`composer.json` dosyasından:

- `App\` → `app/`
- `Core\` → `core/`
- `Database\Seeders\` → `database/seeders/`
- `Database\Factories\` → `database/factories/`
- `Tests\` → `tests/` (autoload-dev)

Global fonksiyonlar, otomatik yüklenmez; ön kontrolcü / test bootstrap aracılığıyla `core/helpers.php` dosyasından yüklenir.

## Çalışma Zamanı Bağımlılıkları (composer.json)
- `php >= 8.2`
- `illuminate/database ^10.0`, `illuminate/events ^10.0`, `illuminate/pagination ^10.0`
- `league/plates ^3.4`
- `vlucas/phpdotenv ^5.6`

Geliştirme: `laravel/pint`, `phpstan/phpstan ^2.1`, `phpunit/phpunit ^10.0`.

## Composer Scriptleri
`test`, `test:unit`, `test:feature`, `format` (pint), `format:test`, `phpstan`.

## Kaynak Referansları
- `composer.json:26-59`
- `core/helpers.php`, `stubs/`, `config/`, `public/index.php`
