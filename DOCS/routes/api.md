# Dosya Raporu: routes/api.php

## Amaç
API uç noktalarının tanımı.

## Genel Bakış
API için rota tanımlarını içerir. Bu dosyadaki tüm rotalar otomatik olarak `api_prefix` (varsayılan `/api`) ile öneklenir ve `api` middleware grubuna atanır. Bu rotalar durumsuzdur (oturum veya CSRF yoktur).

## Dosya Konumu
`routes/api.php`

## Temel Kavramlar
- **Durumsuzluk (Statelessness)**: Oturumlar ve CSRF devre dışıdır.
- **Kimlik Doğrulama**: Bearer token doğrulaması için `api-auth` middleware kullanımı önerilir.
- **Yetenekler**: `api-auth:ability_name` aracılığıyla ayrıntılı izin kontrollerini destekler.
- **Kaynaklar**: Hızlı CRUD uç noktası üretimi için `Route::apiResource()` desteği sunar.

## Kaynak Referansları
- `routes/api.php:1-77`
