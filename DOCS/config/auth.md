# Dosya Raporu: config/auth.php

## Amaç
Kimlik doğrulama yapılandırması.

## Genel Bakış
Framework'ün hangi kullanıcı modelini ve kimlik doğrulama sağlayıcısını kullanması gerektiğini yapılandırır. Çekirdeğin (`Core\Auth`), uygulama uygulamasından ayrıldığı takılabilir bir sistem kullanır.

## Dosya Konumu
`config/auth.php`

## Yapılandırma
- `default`: `.env`'deki `AUTH_PROVIDER` aracılığıyla tanımlanan aktif sağlayıcı (varsayılan: 'eloquent').
- `providers`:
    - `eloquent`: `App\Models\User` modeli ile `Core\Auth\EloquentUserProvider` kullanır.

## Kaynak Referansları
- `config/auth.php:1-48`
