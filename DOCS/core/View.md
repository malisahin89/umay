# View.php

## Amaç
`View` sınıfı, HTML şablonlarını işlemek için basitleştirilmiş bir API ve zengin bir yardımcı fonksiyon seti sunan Plates şablon motoru için bir sarmalayıcıdır.

## Metadatalar
- **Ad Alanı**: `Core`
- **Dosya Konumu**: `core\View.php`

## Bağımlılıklar
- `League\Plates\Engine`
- `Core\Csrf`
- `Core\Csp`

## Temel Metotlar
- `render(string $template, array $data)`: Bir şablon dosyasını işler, küresel verileri (hatalar, flash mesajları) enjekte eder ve çıktıyı ekrana yazdırır.
- `share(string|array $key, mixed $value = null)`: Verileri tüm görünümler ve düzenler arasında küresel olarak paylaşır. Öncelik sırası: paylaşılan < sayfa $data < framework küreselleri.
- `engine()`: Paylaşılan Plates Engine örneğini döndürür.

## Şablon Yardımcı Fonksiyonları
Sınıf, şablonlarda kullanılmak üzere Plates motoru içinde çeşitli yardımcı fonksiyonlar kaydeder:
- **Güvenlik**: `csrf()` (gizli girdi), `csrf_token()` (ham değer), `e()` (XSS kaçış), `nonce()` (CSP nonce).
- **HTTP**: `method(string $method)` (PUT/PATCH/DELETE taklidi için).
- **Yönlendirme/Varlıklar**: `route($name, $params)`, `url($name)`, `asset($path)`.
- **Girdiler**: `old($key, $default)` (flash'lanmış girdiyi alır).
- **Kimlik Doğrulama**: `auth()` (mevcut kullanıcı), `guest()` (boolean kontrol).
- **Yapılandırma**: `config($key, $default)`, `app_name()`.
- **Araçlar**: `now($format)`, `class_if($classes)` (koşullu CSS sınıfları).
- **Doğrulama**: `has_error($field)`, `error($field)`.
- **Flash**: `flash($key)` (flash mesajlarını okur ve temizler).
- **Hata Ayıklama**: `dd($value)` (dök ve dur).

## Dahili İş Akışı (Render)
1. **Oturum Başlatma**: Oturumun aktif olduğundan emin olur.
2. **Flash Tüketimi**: Oturumdan `success` ve `error` flash mesajlarını okur ve bunları `$consumedFlash` içinde saklar; böylece küreseller ve yardımcılar arasında tutarlılık sağlanır.
3. **Küresel Veri Enjeksiyonu**: Paylaşılan verileri (`share()`'den), sayfaya özgü verileri ve framework küresellerini (`title`, `errors`, `success`, `error`, `user_id`) birleştirir.
4. **Profiler Entegrasyonu**: Profilleme etkinse:
    - İşleme süresini ölçer.
    - Görünümü profiler'a kaydeder.
    - `</body>` etiketinden önce `DebugBar` HTML araç çubuğunu enjekte eder.
5. **İşleme Sonrası Temizlik**: Oturumdaki `_old` ve `_flash_errors` değerlerini temizler.
