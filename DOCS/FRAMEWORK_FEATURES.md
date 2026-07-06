# Framework Özellikleri

Bu rapor, Umay framework kaynak kodunda doğrulanan alt sistemleri listeler.

| Özellik | Durum | Uygulama Referansı |
| :--- | :--- | :--- |
| **Yönlendirme** | Doğrulandı | `Core\Route` |
| **İstek (Request)** | Doğrulandı | `Core\Request` |
| **Yanıt (Response)** | Doğrulandı | `Core\ResponseBuilder` |
| **Middleware** | Doğrulandı | `Core\Route::runMiddleware` |
| **Bağımlılık Enjeksiyonu** | Doğrulandı | `Core\Container` |
| **IoC Konteyner** | Doğrulandı | `Core\Container` |
| **Olaylar (Events)** | Doğrulandı | `Core\Events\Dispatcher` |
| **Servis Sağlayıcılar** | Doğrulandı | `Core\ServiceProvider` |
| **Doğrulama (Validation)** | Doğrulandı | `Core\Validator` |
| **Oturum (Session)** | Doğrulandı | Yerel PHP oturumları; `config/session.php`, `Core\Csrf`/`Core\Auth`/`Core\View` |
| **Çerez (Cookie)** | Doğrulandı | `Core\Auth` içindeki `setcookie()` (`remember_me`) + yerel oturum çerezi (`config/session.php`) |
| **Önbellek (Cache)** | Doğrulandı | `Core\Cache` |
| **Veritabanı** | Doğrulandı | `Core\Database` |
| **ORM** | Doğrulandı | `Core\Model` |
| **Sorgu Oluşturucu (Query Builder)** | Doğrulandı | `Core\Database` |
| **Görünümler (Views)** | Doğrulandı | `Core\View` |
| **Şablon Motoru** | Doğrulandı | PHP tabanlı şablonlar |
| **Kimlik Doğrulama (Authentication)** | Doğrulandı | `Core\Auth` |
| **Yetkilendirme (Authorization)** | Doğrulandı | `Core\Auth\HasApiTokens` (Yetenekler) |
| **Günlükleme (Logging)** | Doğrulandı | `Core\Logger` |
| **Konfigürasyon** | Doğrulandı | `config()` yardımcısı, `config/` dizini |
| **Ortam (Environment)** | Doğrulandı | `vlucas/phpdotenv` aracılığıyla `.env` desteği |
| **Hata Yönetimi** | Doğrulandı | `Core\ExceptionHandler` |
| **İstisna Yönetimi** | Doğrulandı | `Core\Exceptions` |
| **Dosya Sistemi** | Doğrulandı | `Core\FileUpload` |
| **Yükleme (Upload)** | Doğrulandı | `Core\FileUpload` |
| **Yardımcılar (Helpers)** | Doğrulandı | `core/helpers.php` |
| **Konsol** | Doğrulandı | `Core\Console\Kernel` |
| **Kuyruk (Queue)** | Doğrulanamadı | Kaynak kodda bulunamadı |
| **Zamanlayıcı (Scheduler)** | Doğrulanamadı | Kaynak kodda bulunamadı |
| **Bildirimler** | Doğrulanamadı | Kaynak kodda bulunamadı |
| **E-posta (Mail)** | Doğrulandı | `Core\Mail\Mailer` |
| **Yerelleştirme (Localization)** | Doğrulanamadı | Kaynak kodda bulunamadı |
| **Güvenlik** | Doğrulandı | `Core\Csrf`, `Core\Middleware\SecurityHeaders` |

## Mevcut Olmayan Alt Sistemler
Aşağıdakiler analiz edilen kaynak kodda aranmış ancak bulunamamıştır: **Kuyruk (Queue)**, **Zamanlayıcı (Scheduler)**, **Bildirimler (Notifications)**, **Yerelleştirme (Localization)**. Bunlar için rapor oluşturulmamıştır.

## Kapsam Dışı Bırakılanlar
Dokümantasyon kuralları gereği, `vendor/` ve tüm `*.md` dosyaları hariç tutulmuştur. Ayrıca şunlar da hariç tutulmuştur:
- **`docs/`** — projenin yayınlanmış GitHub Pages el kitabı (Markdown + `.nojekyll` + bir açılış `index.html`); bu bir dokümantasyon çıktısıdır, framework kaynağı değildir, bu nedenle burada tersine mühendislik ile analiz edilmemiştir.
- **`RAPOR/`** — önceki bir Markdown rapor seti (çalışma ağacından kaldırıldı).
- **`storage/`** çalışma zamanı artıkları (önbellek/günlükler/profiler) ve `.gitkeep` yer tutucuları.
