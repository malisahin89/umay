# İstek Yaşam Döngüsü

Bu rapor, bir HTTP isteğinin Umay framework içerisindeki akışını açıklar.

## 1. Giriş Noktası
Her istek, genel giriş noktasından başlar (genellikle `public/index.php`).

## 2. Önyükleme (Bootstrapping)
`Core\Application` örneği `getInstance()` aracılığıyla alınır.
1. **Konteyner Başlatma**: Uygulama, bağımlılık yönetimi için `Core\Container`'ı başlatır.
2. **Sağlayıcı Kaydı**: Servis Sağlayıcılar (örneğin, `RouteServiceProvider`), `$app->register()` kullanılarak kaydedilir ve `register()` metodları tetiklenir.
3. **İstek Yakalama**: `Application::captureRequest()` çağrılır, bu işlem:
    - PHP global değişkenlerinden bir istek nesnesi oluşturmak için `Core\Request::capture()` kullanır.
    - Bu `Request` örneğini `Container`'a singleton olarak bağlar.
4. **Uygulama Başlatma**: `Application::boot()` çağrılır ve kayıtlı tüm sağlayıcıların `boot()` metodları yürütülür.

## 3. Yönlendirme ve Dağıtım (Routing & Dispatching)
`Core\Route::dispatch()` metodu çağrılır:
1. **URI Normalizasyonu**: İstek URI'si yakalanır ve temizlenir.
2. **Kanonik Yönlendirme**: Eğer istek GET/HEAD ise ve sonunda eğik çizgi (trailing slash) varsa, eşleşen bir rota mevcutsa eğik çizgisiz versiyona 301 ile yönlendirilir.
3. **Profiler Müdahalesi**: `/_profiler` ile başlayan istekler doğrudan `ProfilerController` tarafından işlenir.
4. **Metot Taklidi (Method Spoofing)**: Metot POST ise, yönlendirici PUT, PATCH veya DELETE'i taklit etmek için `_method` parametresini kontrol eder.
5. **Rota Eşleştirme**:
    - **Tam Eşleşme**: URI'nin rota tablosunda bir anahtar olarak mevcut olup olmadığı kontrol edilir.
    - **Desen Eşleşme**: Eşleşme bulmak için derlenmiş regex desenleri üzerinden geçilir.
    - Eşleşme bulunamazsa `abort(404)` çağrılır.
6. **Parametre Bağlama**: Eşleşen rota parametreleri (örneğin, `{id}`) ayıklanır ve `Request` nesnesine bağlanır.

## 4. Middleware Hattı (Pipeline)
Yönlendirici tüm geçerli middleware'leri belirler:
- **Global Middleware**: `config/middleware.php` altında `global` olarak tanımlanmıştır.
- **Grup Middleware**: Rota grubuna bağlı olarak `web` veya `api` altında tanımlanmıştır.
- **Rota Middleware**: Rota tanımına eklenmiş özel middleware'lerdir.

Bunlar `array_reduce` kullanılarak bir "soğan" (onion) hattına sarılır. Her middleware, isteği alır ve isteği zincirin daha derinlerine iletmek için bir `$next` closure'ı kullanır.

## 5. Eylem Yürütme
Hat sona ulaştığında, rota eylemi yürütülür:
- **Görünüm Rotası**: `Core\View` aracılığıyla bir şablon işler.
- **Yönlendirme Rotası**: Bir konum başlığı (location header) gönderir ve sonlandırır.
- **Kontrolcü Eylemi**: 
    - Kontrolcü `Container`'dan çözümlenir.
    - Bağımlılıkları (Request, FormRequest veya rota parametreleri) enjekte etmek için reflection kullanılarak metot çağrılır.
- **Closure**: Closure doğrudan yürütülür.

## 6. Yanıt Teslimatı
Eylemin sonucu işlenir:
- Bir `Core\ResponseBuilder` döndürülürse, `send()` metodu çağrılır.
- Bir dize veya sayı döndürülürse, ekrana yazdırılır (echo).
- Bir dizi veya nesne döndürülürse, JSON olarak kodlanır ve ekrana yazdırılır.

## 7. İstisna Yönetimi
Bu süreç sırasında herhangi bir `Throwable` oluşursa, `Application::handleException()` bunu yakalar ve `Core\ExceptionHandler`'a devreder.
