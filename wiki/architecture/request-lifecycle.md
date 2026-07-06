# İstek Yaşam Döngüsü

Umay Framework'te bir HTTP isteğinin (Request) nasıl işlenip kullanıcıya yanıt (Response) olarak döndüğünü anlamak, mimariyi kavramanın en önemli adımıdır.

## 1. Giriş Noktası: `public/index.php`

Tüm web istekleri `public/index.php` dosyasına yönlendirilir. Bu dosya, framework'ün başlatılmasından (bootstrap) sorumludur.
Burada sistem bağımlılıkları (Composer `autoload.php`) yüklenir ve `Core\Application` sınıfı ayağa kaldırılır.

```php
require_once __DIR__ . '/../vendor/autoload.php';

$app = new Core\Application(dirname(__DIR__));
```

## 2. Kernel ve Middleware Katmanı

`Application` nesnesi başlatıldıktan sonra istek, `Core\Http\Kernel` (HTTP Çekirdeği) tarafına iletilir. Kernel, gelen isteği alır, genel (global) middleware'lerden geçirir ve Route motoruna teslim eder.

## 3. Rota Çözümleme (Routing)

`Core\Route` modülü, `routes/web.php` veya `routes/api.php` içinde tanımlanan rotaları kontrol eder. Gelen isteğin URL'i ve HTTP metodu eşleşirse:
- Rotaya atanmış özel Middleware'ler çalıştırılır.
- Rota bir Closure fonksiyonu ise o fonksiyon çalışır.
- Rota bir Controller metodu ise, Controller'ın ilgili metodu çağrılır (`UserController@index` gibi).

## 4. İş Mantığı (Controller)

Controller içerisinde iş mantığınız çalışır. Model'ler aracılığıyla veritabanı işlemleri yapılır, doğrulama (Validation) gerçekleştirilir ve nihayetinde bir yanıt hazırlanır.

```php
public function index()
{
    $users = User::where('status', 'active')->get();
    
    // View olarak dön
    View::render('users.index', ['users' => $users]);
}
```

## 5. Yanıtın Gönderilmesi (Response)

İstek yaşam döngüsünün son aşamasında, Controller veya Route'tan dönen veri (HTML String, JSON veya Redirect nesnesi) istemciye gönderilir. İşlem tamamlandıktan sonra PHP süreci sonlanır ve bellek boşaltılır.
