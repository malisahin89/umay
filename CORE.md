# Umay Framework - Core Directory (Çekirdek Katmanı)

`core/` dizini, Umay Framework'ün arka planda çalışmasını sağlayan makine dairesidir. PSR-11 uyumlu Service Container, Facade proxy'leri, Routing motoru ve tüm çekirdek servisler burada bulunur. 

> **Not:** Uygulama geliştirilirken (özel bir framework eklentisi yapılmadıkça) bu klasördeki dosyalara müdahale edilmemelidir. Tam tip güvenliği (full type safety) sağlamak amacıyla tüm çekirdek dosyalarında `declare(strict_types=1)` bildirimi bulunur.

## Çekirdek Bileşenler

- **Container (`Core\Container`)**: PSR-11 uyumlu Service Container sınıfıdır. Bağımlılık enjeksiyonu (DI), reflection-tabanlı autowiring ve sınıfların tekil (singleton) yönetiminden sorumludur. `get()` metodu katı çözümleme yapar ve hata durumunda `EntryNotFoundException` fırlatırken, `make()` metodu otomatik çözümleme (auto-wire) sağlar. Tüm çözümlenemeyen durumlar `ContainerException` fırlatır.
- **Application (`Application.php`)**: Tüm framework'ü başlatan (bootstrap) ana sınıftır. Servis sağlayıcılarını (Providers) yükler ve HTTP isteğini Router'a iletir.
- **Facades (`Facades/`)**: Geliştiricinin çekirdek nesnelere statik metodlarla (ör. `Route::get()`, `Auth::user()`) kolayca erişmesini sağlayan proxy sınıflarıdır.
  - Arka planda `FacadeServiceProvider` aracılığıyla Container'daki asıl örneklere (instance) delege ederler.
- **Routing (`Route.php`)**: Gelen HTTP isteklerini uygun Controller veya Closure'a yönlendirir. Named routes, middleware grupları, prefix, resource routes ve opsiyonel parametre (`{name?}`) özelliklerini destekler.
- **Exception Handling (`ExceptionHandler.php`)**: Uygulama içindeki tüm hata ve istisnaları merkezi olarak yakalar ve geliştirici dostu hata sayfaları / JSON yanıtları üretir. Ayrıca mimari güvenlik gereği `HttpException::$statusCode` özelliği `readonly` (salt okunur) olarak tanımlanmıştır.
- **Authentication (`Core\Auth` + `Contracts/`)**: Takılabilir (pluggable) kimlik doğrulama guard'ı. Somut bir kullanıcı sınıfına bağlı **değildir**; `Core\Contracts\Authenticatable` (kullanıcı sözleşmesi) ve `Core\Contracts\UserProvider` (kullanıcı çekme + şifre doğrulama) arayüzleriyle çalışır. Varsayılan `Core\Auth\EloquentUserProvider` gelir; hangi model/provider kullanılacağı `config/auth.php` ile belirlenir — böylece kendi login mantığınızı çekirdeğe dokunmadan yazabilirsiniz.
- **Console (`Console/`)**: Framework'ün `php umay` CLI komut satırı arayüzünü yönetir (`make:*`, `migrate`, `db:seed`, `route:list`, `storage:link`, `cache:clear`, `test`, `key:generate`).
