# Dosya Raporu: core/Mail/Mailer.php

## Amaç
E-posta iletim koordinatörü.

## Genel Bakış
`Mailable` nesnelerini gönderme mantığını yönetir. Gerçek iletimi gerçekleştirmek için yapılandırılmış bir `MailTransport` (örneğin, `LogTransport`) kullanır.

## Dosya Konumu
`core/Mail/Mailer.php`

## Ad Alanı
`Core\Mail`

## Sınıflar
- `class Mailer`

## Özellikler
- `array $to`, `$cc`, `$bcc`: Alıcı listeleri.

## Metotlar
- `to(array|string $address): static`: "Kime" listesine alıcılar ekler.
- `cc(array|string $address): static`: "Bilgi" (CC) listesine alıcılar ekler.
- `bcc(array|string $address): static`: "Gizli Bilgi" (BCC) listesine alıcılar ekler.
- `send(Mailable $mailable): bool`: Final iletim metodudur. `config/mail.php`'den aktif taşıyıcıyı çözer ve onun `send()` metodunu çağırır.

## Bağımlılıklar
- `Core\Contracts\MailTransport` (Kullanır)
- `Core\Mail\Mailable` (Kullanır)

## Kaynak Referansları
- `core/Mail/Mailer.php:1-154`
