# Yapılandırma (Configuration)

Umay Framework, uygulama ayarlarını merkezi bir şekilde yönetmek için `config/` dizinini kullanır. Ayarlar, hem PHP dosyaları üzerinden hem de `.env` dosyası aracılığıyla ortam değişkenleri kullanılarak yönetilir.

## Yapılandırma Dosyaları

Aşağıdaki temel yapılandırma dosyaları mevcuttur:

- **`app.php`**: Uygulama adı, versiyonu, timezone ve Facade alias tanımlamaları.
- **`auth.php`**: Kimlik doğrulama guard'ları ve user provider ayarları.
- **`cache.php`**: Önbellek sürücüsü ve TTL ayarları.
- **`database.php`**: Veritabanı bağlantı bilgileri (Eloquent/Capsule).
- **`mail.php`**: E-posta gönderim ayarları ve transport seçenekleri.
- **`middleware.php`**: Global middleware'ler ve API prefix tanımları.
- **`profiler.php`**: Debug profiler etkinleştirme ve saklama ayarları.
- **`session.php`**: Session ömrü, cookie ayarları ve güvenlik seçenekleri.

## `config()` Yardımcısı

Herhangi bir yapılandırma değerine erişmek için `config()` yardımcı fonksiyonu kullanılır. Nokta notasyonu ile derinlemesine erişim sağlanabilir:

```php
// app.php içindeki 'timezone' değerini getirir
$timezone = config('app.timezone');

// Varsayılan bir değer belirterek kullanım
$timezone = config('app.timezone', 'UTC');
```

## Ortam Değişkenleri (.env)

Hassas veriler (şifreler, API anahtarları) `.env` dosyasında saklanır. Config dosyaları bu değerleri okumak için `env()` fonksiyonunu kullanır:

```php
// config/database.php
'host' => env('DB_HOST', '127.0.0.1'),
```
