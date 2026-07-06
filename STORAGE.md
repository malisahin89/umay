# Umay Framework - Storage Directory (Depolama)

`storage/` dizini, framework'ün çalışma zamanında ürettiği dinamik verileri tuttuğu alandır. Git tarafından takip edilmemesi gereken (ignore) dosyalar buraya kaydedilir.

## Alt Dizinler

- **`logs/`**: Uygulamanın günlük log dosyaları (ör. `2026-05-04.log`). Hatalar, uyarılar ve geliştirici düzeyinde info logları Monolog benzeri Logger tarafından buraya günlük olarak yazılır.
- **`cache/`**: Framework önbellek (cache) mekanizması bu dizini kullanır. HMAC imza doğrulaması (file tampering koruması) içeren güvenli bir dosya tabanlı önbellekleme sistemi aktiftir. Veritabanı veya uzun süren işlemlerin sonuçları burada serileştirilerek tutulur.
- **`framework/`**: Şablon (View) önbellekleri, derlenmiş dosyalar veya sistem geçici dosyaları için kullanılabilir.
- **`app/`**: Kullanıcıların yüklediği (upload) resimler veya dokümanlar için kullanılır. Web üzerinden erişim sağlamak için `php umay storage:link` komutu ile `public/storage` dizinine sembolik bağ (symlink/junction) oluşturulmalıdır.

Dizinin web üzerinden doğrudan okunmaması ve uygulamanın bu dizine yazma (write) izinlerine sahip olması kritik öneme sahiptir.
