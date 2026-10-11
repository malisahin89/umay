# Dosya Raporu: config/mail.php

## Amaç
E-posta sistemi yapılandırması.

## Genel Bakış
Sürücü tabanlı bir e-posta sistemi tanımlar. Framework varsayılan olarak e-postaları `storage/logs` dizinine yazan bir `LogTransport` sağlar. Diğer taşıyıcılar `Core\Contracts\MailTransport` uygulanarak eklenebilir.

## Dosya Konumu
`config/mail.php`

## Yapılandırma
- `default`: `MAIL_MAILER`'dan gelen aktif mailer (varsayılan: 'log').
- `mailers`:
    - `log`: `Core\Mail\Transport\LogTransport` kullanır.
- `from`:
    - `address`: `MAIL_FROM_ADDRESS`'ten gelir (varsayılan: 'noreply@localhost').
    - `name`: `MAIL_FROM_NAME`'den gelir (varsayılan: 'Umay').

## Kaynak Referansları
- `config/mail.php:1-58`
