# Dosya Raporu: config/app.php

## Amaç
Genel uygulama yapılandırması.

## Genel Bakış
İsim, sürüm, URL, kontrolcü ad alanı, ortam, zaman dilimi ve Facade takma adları gibi temel uygulama ayarlarını tanımlar.

## Dosya Konumu
`config/app.php`

## Yapılandırma
- `name`: `.env`'deki `APP_NAME` aracılığıyla tanımlanır (varsayılan: 'Umay').
- `version`: '1.0.0'
- `url`: `.env`'deki `APP_URL` aracılığıyla tanımlanır (varsayılan: 'http://localhost').
- `controller_namespace`: `CONTROLLER_NAMESPACE` aracılığıyla tanımlanır (varsayılan: 'App\\Controllers\\').
- `env`: `APP_ENV` aracılığıyla tanımlanır (varsayılan: 'local').
- `trusted_proxies`: `TRUSTED_PROXIES`'den gelen IP dizisi (varsayılan: '127.0.0.1,::1').
- `debug`: `APP_DEBUG`'dan gelen boolean değer.
- `timezone`: `APP_TIMEZONE` aracılığıyla tanımlanır (varsayılan: 'Europe/Istanbul').
- `key`: `APP_KEY` aracılığıyla tanımlanır.

## Facade Takma Adları
`FacadeServiceProvider` tarafından kaydedilen kısa global isimler:
- `Cache` -> `Core\Facades\Cache`
- `Auth` -> `Core\Facades\Auth`
- `Log` -> `Core\Facades\Log`
- `DB` -> `Core\Facades\DB`
- `Event` -> `Core\Facades\Event`
- `Validator` -> `Core\Facades\Validator`
- `RateLimiter` -> `Core\Facades\RateLimiter`

## Kaynak Referansları
- `config/app.php:1-117`
