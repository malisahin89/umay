# Dosya Raporu: core/Mail/Transport/LogTransport.php

## Amaç
Günlükleme için e-posta iletim uygulaması.

## Genel Bakış
Gerçek e-postalar göndermek yerine, bir `Mailable` içeriğini uygulama günlük dosyalarına yazar. Geliştirme ortamları için varsayılan iletim yöntemidir.

## Dosya Konumu
`core/Mail/Transport/LogTransport.php`

## Ad Alanı
`Core\Mail\Transport`

## Sınıflar
- `class LogTransport implements MailTransport`

## Metotlar
- `send(Mailable $mailable, array $to, array $cc = [], array $bcc = []): bool`: E-postayı bir günlük girdisi olarak formatlar ve `Core\Logger` kullanarak yazar.

## Bağımlılıklar
- `Core\Contracts\MailTransport` (Uygular)
- `Core\Mail\Mailable` (Kullanır)
- `Core\Logger` (Kullanır)

## Kaynak Referansları
- `core/Mail/Transport/LogTransport.php:1-50`
