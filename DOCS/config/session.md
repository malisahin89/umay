# Dosya Raporu: config/session.php

## Amaç
Oturum (session) yapılandırması.

## Genel Bakış
Oturum ömrünü, çerez adını ve güvenlik ayarlarını (Secure, HttpOnly, SameSite) tanımlar.

## Dosya Konumu
`config/session.php`

## Yapılandırma
- `lifetime`: `SESSION_LIFETIME`'dan gelen dakika cinsinden ömür (varsayılan: 30).
- `cookie`: `SESSION_COOKIE`'den gelen oturum çerezi adı (varsayılan: 'umay_session').
- `secure`: `SESSION_SECURE`'dan gelen boolean değer veya `$_SERVER['HTTPS']` üzerinden tespit edilen değer.
- `http_only`: `true`
- `same_site`: `SESSION_SAME_SITE`'dan gelir (varsayılan: 'Strict').

## Kaynak Referansları
- `config/session.php:1-28`
