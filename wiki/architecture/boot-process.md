# Boot Süreci (Application Bootstrapping)

Sistemin bir isteği karşılamaya hazır hale gelene kadar izlediği yol oldukça sistematiktir.

## Başlatma Sıralaması

Süreç `public/index.php` dosyasının çalışmasıyla başlar:

### 1. Uygulama ve Container Kurulumu
`Application::getInstance()` çağrıldığında, uygulama singleton örneği oluşturulur ve `Core\Container` (Bağımlılık Enjeksiyon Sistemi) başlatılır.

### 2. Kayıt Aşaması (`register`)
Servis sağlayıcılar (`ServiceProvider`) sırayla kaydedilir. Bu aşamada sadece servislerin container'a nasıl bağlanacağı (binding) tanımlanır, henüz herhangi bir mantık çalıştırılmaz.
- Örn: `FacadeServiceProvider` çağrılarak `Cache`, `Auth`, `DB` gibi servisler tanımlanır.

### 3. İstek Yakalama (`captureRequest`)
Sistem, mevcut HTTP isteğini (`Core\Request`) yakalar ve container'a singleton olarak kaydeder. Böylece uygulamanın her yerinden güncel isteğe erişilebilir.

### 4. Başlatma Aşaması (`boot`)
Tüm sağlayıcılar kaydedildikten sonra `Application::boot()` çağrılır. Bu aşamada:
- **Facade Aliaslar**: `config/app.php`'deki kısa isimler (`Cache`, `Route` vb.) tanımlanır.
- **Route Yükleme**: `routes/web.php` ve `routes/api.php` dosyaları okunarak yönlendirme tablosu oluşturulur.

### 5. Yönlendirme ve Yanıt (`dispatch`)
Son adımda `Route::dispatch()` çağrılır. İstek, tanımlı middleware'lerden geçer, uygun kontrolcü metoduna ulaşır ve bir `Response` döner.
