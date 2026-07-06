# Facade Yapısı

Umay Framework'te Facade'ler, uygulamanızın Service Container'ında kayıtlı olan sınıflara "statik" bir arayüz (Static Proxy) sağlar.

Facade'ler sayesinde, nesneleri `new` anahtar kelimesiyle oluşturmak veya `app('service')` helper'ını kullanmak yerine, kolay okunabilir ve test edilebilir statik çağrılar yapabilirsiniz.

## Kullanım Örneği

Örneğin, normal şartlarda Log sınıfına ulaşmak için Container kullanabilirsiniz:
```php
app('log')->info('Kullanıcı giriş yaptı.');
```

Ancak Facade kullanarak bunu çok daha şık bir şekilde yazabilirsiniz:
```php
use Core\Facades\Log;

Log::info('Kullanıcı giriş yaptı.');
Log::error('Veritabanı bağlantı hatası!');
```

## Mevcut Dahili Facade'ler

Umay Framework kutudan çıktığı haliyle size şu Facade'leri sunar:

- `Core\Facades\Log`: Loglama işlemleri için (Arka planda Monolog/Umay Logger çalışır).
- `Core\Facades\Cache`: Önbellekleme işlemleri.
- `Core\Facades\Event`: Olay (Event) dinleyicileri.

> [!TIP]
> Facade kullanmak kodu "statik" hale getirmez. Arka planda Service Container üzerinden asıl sınıfın instance'ı çekilir. Bu nedenle Facade'ler, gerçek statik metotların aksine test edilebilir yapıdadır (Mocklanabilir).
