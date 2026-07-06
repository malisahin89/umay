# Service Container

Umay Framework'ün kalbinde yer alan Service Container (Servis Kapsayıcısı), uygulamanızın sınıflarını ve bağımlılıklarını (Dependencies) yöneten inanılmaz güçlü bir araçtır. 

PSR-11 uyumlu olan Container sayesinde, sınıfların nasıl ayağa kaldırılacağını (Instantiate) tanımlayabilir ve her yerden bu sınıflara erişebilirsiniz.

## Bind (Bağlama) İşlemi

Container'a bir servis eklemek için `bind` veya `singleton` metotlarını kullanırsınız. Singleton kullanıldığında sınıf, uygulama yaşam döngüsü boyunca sadece bir kez üretilir.

Genellikle servis bağlama işlemleri `app/Providers/` içindeki sınıflarda yapılır.

```php
use Core\Container;

// Her çağrıda yeni bir instance üretir
Container::getInstance()->bind('logger', function() {
    return new \App\Services\LoggerService();
});

// Tüm uygulama boyunca tek bir instance (Singleton) üretir
Container::getInstance()->singleton('db', function() {
    return new \Core\Database();
});
```

## Resolve (Çözümleme) İşlemi

Bağladığınız (Bind) bir servisi çağırmak çok kolaydır.

```php
// Container üzerinden çekmek
$logger = Container::getInstance()->get('logger');
$logger->info('İşlem başarılı');

// Helper fonksiyonu ile (Daha pratik)
$db = app('db');
```

## Otomatik Bağımlılık Çözümleme (Auto-wiring)

Umay Framework'ün Container mimarisi, Reflection API kullanarak Controller'larınızın veya servislerinizin kurucu metotlarında (Constructor) ihtiyaç duyulan bağımlılıkları otomatik olarak çözer (Inject eder).

```php
namespace App\Controllers;

use Core\Request;

class UserController 
{
    // Request sınıfı, Container tarafından otomatik enjekte edilir!
    public function store(Request $request)
    {
        $name = $request->post('name');
    }
}
```

Bu yetenek sayesinde, test yazmak ve mock nesneleri kullanmak son derece kolaylaşır.
