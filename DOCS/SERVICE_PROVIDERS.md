# Servis Sağlayıcılar

Bu rapor, Umay framework'ünde önyükleme ve servis kaydını yönetmek için kullanılan Servis Sağlayıcı (Service Provider) desenini açıklar.

## Genel Bakış
Servis Sağlayıcılar, uygulamayı yapılandırmak için merkezi yerlerdir. Servislerin `Core\Container` içerisine modüler bir şekilde kaydedilmesini sağlayarak, framework çekirdeğinin belirli uygulamalardan bağımsız kalmasını sağlar.

## `Core\ServiceProvider` Temel Sınıfı
Tüm sağlayıcılar, `Container`'a erişim sağlayan `Core\ServiceProvider` sınıfını genişletir.

### Yaşam Döngüsü Metotları

#### 1. `register()`
`register()` metodu ilk önce çağrılır. Tek sorumluluğu, servisleri konteynere bağlamaktır.
- **Kural**: `register()` içinde diğer servisleri çözümlemeye veya uygulama mantığını kullanmaya çalışmayın; çünkü diğer sağlayıcılar henüz kaydedilmemiş olabilir.

#### 2. `boot()`
`boot()` metodu, tüm sağlayıcılar kaydedildikten sonra çağrılır.
- **Kural**: Rotaları yüklemek veya olay dinleyicilerini kaydetmek gibi, diğer servislerin mevcut olmasını gerektiren eylemler için güvenli yer burasıdır.

## Çekirdek Sağlayıcı Uygulamaları

### `Core\Providers\FacadeServiceProvider`
Bu, en kritik çekirdek sağlayıcıdır. İki ana görevi yerine getirir:
1. **Çekirdek Servis Bağlama**: `Cache`, `Auth`, `Logger`, `Route`, `Database`, `Dispatcher`, `Validator`, `View` ve `RateLimiter` dahil olmak üzere temel framework servislerini konteynere singleton olarak bağlar.
2. **Facade Takma Adlandırma**: `config/app.php`'deki `aliases` yapılandırmasını okur ve facade sınıfları için küresel kısa isimler oluşturmak için `class_alias()` kullanır.

### `App\Providers\RouteServiceProvider`
Bu uygulama düzeyi sağlayıcı, rota dosyalarının yüklenmesini yönetir:
- **Web Rotaları**: `routes/web.php` dosyasını `web` middleware grubu altında yükler.
- **API Rotaları**: `routes/api.php` dosyasını `api` middleware grubu altında yükler ve `config/middleware.php`'de yapılandırılan `api_prefix` öneki uygular.

## Yürütme Akışı Özeti
1. `Application::register(Provider::class)` $\to$ `Provider::register()` (Servisleri bağlar).
2. `Application::boot()` $\to$ `Provider::boot()` (Boot mantığını yürütür).
