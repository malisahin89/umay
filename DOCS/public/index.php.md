# Dosya Raporu: public/index.php

## Amaç
Uygulamanın ana giriş noktası (Front Controller).

## Genel Bakış
Ortamı başlatır, `Application`'ı önyükler, oturum yönetimini gerçekleştirir, `Profiler`'ı yönetir ve isteği `Route` sistemine yönlendirir.

## Dosya Konumu
`public/index.php`

## Temel Sorumluluklar
- **Ortam Kurulumu**: `BASE_PATH`'i tanımlar, Composer autoload ve yapılandırmasını yükler.
- **Profiler**: Hata ayıklama profiler'ını başlatır ve profil verilerini kaydetmek için bir shutdown fonksiyonu kaydeder.
- **API vs Web Tespiti**: Öneke (varsayılan `/api`) dayanarak bir isteğin API çağrısı olup olmadığını tespit eder.
- **Oturum Yönetimi**: Web istekleri için güvenli çerez ayarlarını (`httponly`, `secure`, `samesite`) yapılandırır ve bir boşta kalma zaman aşımı (idle timeout) mekanizması uygular.
- **Önyükleme (Bootstrapping)**: 
    - `Application` singleton'ını başlatır.
    - Mevcut isteği yakalar.
    - Çekirdek servis sağlayıcıları kaydeder: `FacadeServiceProvider`, `EventServiceProvider`, `RouteServiceProvider`.
    - Uygulamayı başlatır (boot).
- **Yönlendirme (Routing)**: Mevcut URI için işleyiciyi yürütmek üzere `Route::dispatch()` çağrılır.
- **İstisna Yönetimi**: Yönlendirme sürecini bir try-catch bloğuna sarar ve hataları `$app->handleException($e)`'ye devreder.

## Kaynak Referansları
- `public/index.php:1-124`
