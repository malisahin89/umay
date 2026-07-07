# Dosya Raporu: core/Mail/Mailable.php

## Amaç
Tüm e-posta iletileri için temel sınıf.

## Genel Bakış
Bir e-postanın içeriğini, alıcılarını ve eklerini tanımlamak için bir yapı sağlar. Mailable'lar bir `Mailer` tarafından işlenir ve bir `MailTransport` aracılığıyla gönderilir.

## Dosya Konumu
`core/Mail/Mailable.php`

## Ad Alanı
`Core\Mail`

## Sınıflar
- `class Mailable`

## Özellikler
- `array $attachments`: Dosya ekleri listesi.
- `array $headers`: E-posta için özel HTTP başlıkları.

## Metotlar
- `getAttachments(): array`: Kaydedilen ekleri döndürür.
- `getExtraHeaders(): array`: Özel başlıkları döndürür.
- `getFrom(): string`: Göndericinin e-posta adresini döndürür.
- `getFromName(): string`: Göndericinin adını döndürür.
- `view(string $view, array $data = []): static`: E-posta gövdesini bir görünüm şablonunun işlenmiş çıktısına ayarlar.
- `renderView(string $view, array $data = []): string`: Belirtilen görünümü işler.

## Bağımlılıklar
- `Core\View` (`ResponseBuilder` veya doğrudan çözümleme yoluyla kullanır)

## Kaynak Referansları
- `core/Mail/Mailable.php:1-117`
