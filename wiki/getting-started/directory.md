# Dizin Yapısı

Umay Framework'ün dizin yapısı, projenizi mantıksal olarak sınıflandırmak ve temiz bir mimari sunmak üzere tasarlanmıştır.

## Kök Dizin (Root)

Kök dizin, projenizin ana taşıyıcısıdır ve uygulamanızın çekirdeğini (Core), iş mantığını (App) ve konfigürasyonlarını barındırır.

```text
umay/
├── app/          # Uygulama iş mantığı (Controllers, Models, vb.)
├── config/       # Ayar dosyaları (app, database, cache)
├── core/         # Framework çekirdeği (Router, Container, Eloquent Setup)
├── database/     # Migration'lar, Factory'ler ve Seeder'lar
├── public/       # Dışa açık dizin (index.php, CSS, JS)
├── routes/       # Rota tanımlamaları (web.php, api.php)
├── storage/      # Loglar, önbellek dosyaları ve derlenmiş view'lar
├── stubs/        # Umay CLI için şablon dosyaları
├── tests/        # PHPUnit testleri (Unit ve Feature)
├── vendor/       # Composer bağımlılıkları
└── views/        # Plates şablon motoru görünümleri (.php)
```

## `app/` Dizini

Uygulamanızın kalbi `app/` dizinidir. Çoğu geliştirme işleminizi bu klasörde gerçekleştireceksiniz. Başlangıç iskeleti **bilinçli olarak çıplaktır** — yalnızca temel yapı taşları gelir, gerisini siz eklersiniz:

- **`Controllers/`**: HTTP isteklerini karşılayan kontrolcüler. İskelette türetebileceğiniz bir `Controller` taban sınıfı gelir.
- **`Middleware/`**: İstekleri filtreleyen aracı katmanlar. İskelette generic `ThrottleMiddleware` gelir; kendi `Auth`/`Admin` middleware'lerinizi `php umay make:middleware` ile ekleyin.
- **`Models/`**: Eloquent modelleri. İskelette generic bir `User` modeli (`name`, `email`, `password`) gelir; `Core\Contracts\Authenticatable`'ı implemente eder.
- **`Providers/`**: Servis sağlayıcıları — `EventServiceProvider` (event→listener eşleştirmesi) ve `RouteServiceProvider` (route dosyalarını yükler).
- **`Services/`**: Kendi servis/iş-mantığı sınıflarınız için boş dizin.

> `Events/`, `Listeners/`, `Mail/`, `Requests/` gibi dizinler iskelette gelmez; `php umay make:event`, `make:listener`, `make:mail`, `make:request` komutlarıyla ihtiyaç oldukça otomatik oluşturulur.

## `core/` Dizini

Bu klasör Umay Framework'ün iç işleyişini barındırır. Bir framework geliştiricisi değilseniz bu klasöre dokunmanız tavsiye edilmez:

- **`Application.php`**: Sistem bootstrap ve Service Container yönetimi.
- **`Route.php`**: URL yönlendirme motoru.
- **`Database.php`**: Eloquent kapsayıcısı.
- Diğer tüm çekirdek bileşenler (Request, Response, View, Session).

> [!NOTE]
> Umay Framework mimarisi, kod yazarken karmaşadan uzak durmanız için en iyi pratikleri varsayılan olarak size sunar. Dizinlere müdahale ederken PSR-4 standartlarına uyduğunuzdan emin olun.
