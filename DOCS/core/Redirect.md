# Dosya Raporu: core/Redirect.php

## Amaç
Güvenli HTTP yönlendirme aracı.

## Genel Bakış
Dahili veya harici URL'lere yönlendirmeleri yönetir. Mutlak URL'lerin `APP_URL` ana bilgisayarıyla eşleşmesini sağlayarak ve protokol bağımlı URL'leri (protocol-relative URLs) reddederek "Açık Yönlendirme" (Open Redirect) saldırılarını önlemek için güvenlik kontrolleri içerir.

## Dosya Konumu
`core/Redirect.php`

## Ad Alanı
`Core`

## Sınıflar
- `class Redirect`

## Metotlar
- `to(string $url): void`: Belirtilen URL'ye yönlendirir. `Location` başlığını göndermeden önce URL'yi güvenlik açısından doğrular.
- `route(string $name): void`: Önce URL'sini çözerek isimlendirilmiş bir rotaya yönlendirir.

## Dahili İş Akışı
1. Başlık enjeksiyonunu (header injection) önlemek için URL'den CR/LF karakterlerini temizler.
2. Protokol bağımlı URL'leri (örn. `//evil.com`) reddeder.
3. URL göreceli ise ( `/` ile başlıyorsa), izin verilir.
4. URL mutlak ise, yalnızca ana bilgisayar `APP_URL` ana bilgisayarıyla eşleşiyorsa izin verilir.
5. Herhangi bir kontrol başarısız olursa, güvenli bir yedek olarak ana sayfaya (`/`) yönlendirir.
6. İsteğin daha fazla yürütülmesini durdurmak için bir `RedirectException` fırlatır.

## Bağımlılıklar
- `Core\Route` (Kullanır)

## Kaynak Referansları
- `core/Redirect.php:1-51`
