# Dosya Raporu: core/Contracts/MailTransport.php

## Amaç
E-posta iletim uygulamaları için arayüz.

## Genel Bakış
Bir `Mailable` nesnesinin alıcılara nasıl iletileceğini tanımlar. Bu, yapılandırmadaki iletim sınıfını değiştirerek framework'ün birden fazla iletim yöntemini (örneğin Log, SMTP, API) desteklemesini sağlar.

## Dosya Konumu
`core/Contracts/MailTransport.php`

## Ad Alanı
`Core\Contracts`

## Arayüzler
- `interface MailTransport`

## Metotlar
- `send(Mailable $mailable, array $to, array $cc = [], array $bcc = []): bool`: E-postayı belirtilen adreslere iletir.

## Bağımlılıklar
- `Core\Mail\Mailable` (Kullanır)

## Kaynak Referansları
- `core/Contracts/MailTransport.php:1-33`
