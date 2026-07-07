# Dosya Raporu: app/Providers/RouteServiceProvider.php

## Amaç
Uygulama rotalarını yüklemekten sorumlu servis sağlayıcısı.

## Genel Bakış
Rota tanımlarını `routes/web.php` ve `routes/api.php` dosyalarından yükler. Web rotalarına 'web' ara yazılım grubunu, API rotalarına ise yapılandırılabilir bir ön ek ile 'api' ara yazılım grubunu uygular.

## Dosya Konumu
`app/Providers/RouteServiceProvider.php`

## İsim Uzayı
`App\Providers`

## İçe Aktarmalar
- `Core\Route`
- `Core\ServiceProvider`

## Sınıflar
- `class RouteServiceProvider extends ServiceProvider`

## Metotlar
- `register(): void`: Uygulanmış bir mantık yoktur (bağlamalar `boot` içerisinde yönetilir).
- `boot(): void`: Web ve API rotalarının yüklenmesini tetikler.
- `loadWebRoutes(): void`: `routes/web.php` dosyasının olup olmadığını kontrol eder ve onu 'web' grubu altında yükler.
- `loadApiRoutes(): void`: `routes/api.php` dosyasının olup olmadığını kontrol eder ve onu `middleware.api_prefix` (varsayılan: `/api`) altında tanımlanan ön ek ile 'api' grubu altında yükler.

## Bağımlılıklar
- `Core\ServiceProvider` (Genişletir)
- `Core\Route` (Kullanır)

## Kaynak Referansları
- `app/Providers/RouteServiceProvider.php:1-80`
