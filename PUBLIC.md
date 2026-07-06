# Umay Framework - Public Directory

`public/` dizini uygulamanın web sunucusuna (Apache/Nginx) açık olan tek klasörüdür. Bu güvenlik açısından kritik bir izolasyon sağlar; çekirdek kodlar ve `.env` dosyası web üzerinden asla doğrudan erişilemez.

## Dizinin Görevleri

- **`index.php`**: Tüm uygulamanın giriş noktasıdır (Front Controller). Gelen her HTTP isteği sunucu tarafından (örn. `.htaccess` kuralları ile) bu dosyaya yönlendirilir, framework burada başlatılır ve yanıt (response) kullanıcıya gönderilir.
- **Asset Dosyaları (`css/`, `js/`, `images/`)**: Uygulamanın statik dosyaları burada tutulur. CSS dosyalarınız (ör. `index.css`) veya public resimleriniz buradadır.

## Güvenlik
Kök dizindeki `.htaccess` dosyası, tüm trafiği `public/index.php` dosyasına yönlendirir. Bu sayede `app/`, `core/` veya `.env` gibi dizinler tarayıcı üzerinden okunmaya karşı korunur.
