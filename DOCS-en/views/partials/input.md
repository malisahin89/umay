# Input Partial

`views/partials/input.php` dosyası, form giriş alanlarını standardize eden ve hata yönetimini kolaylaştıran bir bileşendir.

## Özellikler
- **Otomatik Veri**: `old($name)` helper'ı ile form hataları sonrası eski verileri otomatik olarak doldurur.
- **Hata Entegrasyonu**: `$_SESSION['_flash_errors']` üzerinden ilgili alanın hatasını kontrol eder ve kırmızı çerçeve ile hata mesajını gösterir.
- **Parametreler**:
    - `$name`: Input'un adı.
    - `$label`: Etiket metni.
    - `$type`: Input tipi (text, email, password vb.).
    - `$placeholder`: Yer tutucu metin.
    - `$required`: Zorunlu alan işareti (`*`).
    - `$icon`: Sol tarafa eklenen ikon.
- **Kullanım**: `$this->insert('partials::input', [...])` şeklinde çağrılır.
