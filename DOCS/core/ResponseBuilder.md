# Dosya Raporu: core/ResponseBuilder.php

## Amaç
Akıcı (Fluent) HTTP yanıt oluşturucu.

## Genel Bakış
Akıcı bir arayüz kullanarak karmaşık HTTP yanıtlarının (başlıklar, durum kodları, gövde türleri) oluşturulmasına olanak tanır. JSON, HTML, Görünüm işleme ve dosya indirmelerini destekler.

## Dosya Konumu
`core/ResponseBuilder.php`

## Ad Alanı
`Core`

## Sınıflar
- `class ResponseBuilder`

## Özellikler
- `string $body`: Yanıt gövdesi içeriği.
- `int $status`: HTTP durum kodu.
- `array $headers`: HTTP başlıkları eşlemesi.
- `?string $downloadPath`: Akışla indirme için dosya yolu.

## Metotlar
- `status(int $code): static`: HTTP durum kodunu ayarlar.
- `header(string $key, string $value): static`: Tek bir HTTP başlığı ekler.
- `withHeaders(array $headers): static`: Birden fazla HTTP başlığı ekler.
- `json(mixed $data, int $status = 0): static`: Gövdeyi JSON kodlu bir dizeye ayarlar ve `Content-Type`'ı `application/json` yapar.
- `html(string $content, int $status = 0): static`: Gövdeyi bir HTML dizesine ayarlar.
- `view(string $view, array $data = [], int $status = 0): static`: `Core\View` kullanarak bir görünüm şablonunu işler ve bunu gövde olarak ayarlar.
- `download(string $filePath, ?string $filename = null, int $status = 0): static`: `Content-Disposition` ve `Content-Length` ayarlarını yaparak bir dosyayı indirme için hazırlar.
- `send(): void`: Başlıkları ve gövdeyi istemciye gönderir. Eğer bir `downloadPath` ayarlanmışsa, dosyayı akışla göndermek için `readfile()` kullanır.

## Dahili İş Akışı
- **Başlık Temizleme**: `sanitizeHeader()`, başlık enjeksiyonunu ve yanıt bölünmesini önlemek için CR/LF karakterlerini kaldırır.
- **Güvenli İndirmeler**: `download()`, dosya adı temizlemeyi yönetir ve ASCII olmayan dosya adları için RFC 5987'yi kullanır.
- **Yürütme Sonlandırma**: `send()`, istek yaşam döngüsünü durdurmak için sonunda bir `RedirectException` fırlatır.

## Bağımlılıklar
- `Core\Container` (Kullanır)
- `Core\View` (Kullanır)

## Kaynak Referansları
- `core/ResponseBuilder.php:1-160`
