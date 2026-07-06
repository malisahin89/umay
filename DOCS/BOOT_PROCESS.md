# Önyükleme Süreci

Bu rapor, sunucunun bir istek aldığı andan uygulamanın tamamen başlatılıp dağıtıma hazır hale gelene kadar gerçekleşen olaylar dizisini detaylandırır.

## Başlatma Dizisi

### 1. Giriş Noktası Yürütme
Süreç `public/index.php` içinde başlar.

### 2. Uygulama Örneği Oluşturma
`Core\Application` singleton'ı `Application::getInstance()` aracılığıyla alınır. Bu, uygulama örneğini oluşturur ve `Core\Container`'ı başlatır.

### 3. Servis Sağlayıcı Kaydı (`register` aşaması)
Uygulama çeşitli servis sağlayıcıları kaydeder. Genellikle ilk kaydedilen kritik sağlayıcı `Core\Providers\FacadeServiceProvider`'dır.
- **Kayıt**: Her sağlayıcının `register()` metodu yürütülür.
- **Çekirdek Servisler**: `FacadeServiceProvider::register()` şunları konteynerde singleton olarak bağlar:
    - `Core\Cache`
    - `Core\Auth`
    - `Core\Logger`
    - `Core\Route`
    - `Core\Database`
    - `Core\Events\Dispatcher`
    - `Core\Validator` (bir fabrika sarmalayıcısı aracılığıyla)
    - `Core\View`
    - `Core\RateLimiter`

### 4. İstek Yakalama
`Application::captureRequest()` çağrılır:
- `Core\Request::capture()`'ı çağırır.
- Oluşan `Request` örneği, konteynere singleton olarak bağlanır ve böylece sonraki tüm servisler tarafından erişilebilir hale gelir.

### 5. Servis Sağlayıcı Başlatma (`boot` aşaması)
Tüm sağlayıcılar kaydedildiğinde ve istek yakalandığında, `Application::boot()` çağrılır. Bu, kayıtlı her sağlayıcının `boot()` metodunu yürütür.
- **Facade Takma Adlandırma**: `FacadeServiceProvider::boot()`, `config/app.php`'deki `aliases` dizisini okur ve facade sınıflarını kısa global isimlere eşlemek için `class_alias()`'ı kullanır (örneğin, `\Core\Facades\Cache` $\to$ `Cache`).
- **Rota Yükleme**: `App\Providers\RouteServiceProvider::boot()`, `routes/web.php` ve `routes/api.php` dosyalarından rota tanımlarını yükler.

## Özet Zaman Çizelgesi

| Adım | Eylem | Bileşen | Amaç |
| :--- | :--- | :--- | :--- |
| 1 | `getInstance()` | `Application` | Giriş ve Konteyner kurulumu |
| 2 | `register()` | `ServiceProvider` | Servisleri Konteyner'a bağla |
| 3 | `captureRequest()` | `Application` | Mevcut HTTP isteğini bağla |
| 4 | `boot()` | `ServiceProvider` | Boot mantığını çalıştır ve rotaları yükle |
| 5 | `dispatch()` | `Route` | İsteği eylemle eşleştir |
