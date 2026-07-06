# Umay Framework - App Directory (Uygulama Katmanı)

`app/` klasörü projenizin kalbidir. Geliştiricinin (sizin) tüm iş mantığı (business logic), veritabanı modelleri ve HTTP kontrolcüleri bu dizinde yer alır. Core (çekirdek) katmanından farklı olarak, bu dizin tamamen projeye özeldir.

Başlangıç iskeleti **bilinçli olarak çıplaktır**: yalnızca temel yapı taşları gelir, kalanını siz eklersiniz. Hazır bir login/RBAC örneğini görmek isterseniz `demo-app` branch'ine bakın.

## Dizin Yapısı

- **`Controllers/`**: HTTP isteklerini karşılayan ve Views ile Models arasındaki köprüyü kuran sınıflardır. İskelette türetebileceğiniz bir `Controller` taban sınıfı gelir. Yeni controller: `php umay make:controller Post`.
- **`Middleware/`**: HTTP istekleri rotalara ulaşmadan önce araya giren filtreleme katmanıdır.
  - `ThrottleMiddleware`: Rate limiting uygular (iskelette gelir; `throttle:5,60` gibi kullanılır).
  - Kendi `Auth` / `Admin` / `Permission` middleware'lerinizi `php umay make:middleware Auth` ile ekleyin.
  - Not: CSRF, RememberMe ve güvenlik header'ları çekirdek katmanda yönetilir (`core/Middleware/`).
- **`Models/`**: Eloquent ORM tabanlı veritabanı modelleri. İskelette generic bir `User.php` gelir (`name`, `email`, `password`); `Core\Contracts\Authenticatable`'ı implemente eder. İlişkiler, mutator ve accessor'lar burada tanımlanır.
- **`Providers/`**: Servis sağlayıcıları.
  - `EventServiceProvider`: Event → Listener eşleştirmesi (`$listen`).
  - `RouteServiceProvider`: `routes/web.php` ve `routes/api.php`'yi yükler.
- **`Services/`**: Business logic servis sınıfları buraya eklenir. Controller 20 satırı geçtiğinde buraya taşı.

> `Events/`, `Listeners/`, `Mail/`, `Requests/` gibi dizinler iskelette **gelmez**; `php umay make:event`, `make:listener`, `make:mail`, `make:request` komutlarıyla ihtiyaç oldukça otomatik oluşturulur.

Tüm bu katmanlar PSR-4 standardıyla `App\` namespace'i altında yüklenir (namespace `config/app.php` → `controller_namespace` ve `config/middleware.php` → `namespaces` ile değiştirilebilir). Tam tip güvenliği (full type safety) sağlamak amacıyla tüm uygulama dosyalarında `declare(strict_types=1)` bildirimi bulunur.
