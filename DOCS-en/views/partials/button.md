# Button Partial

`views/partials/button.php` dosyası, tutarlı bir buton yapısı sağlamak için oluşturulmuş yeniden kullanılabilir bir bileşendir.

## Özellikler
- **Varyantlar**: `primary`, `secondary`, `danger` ve `success` olmak üzere 4 farklı renk teması sunar.
- **Esneklik**: Hem `<button>` hem de `<a>` etiketi olarak render edilebilir (`$href` parametresine göre).
- **Parametreler**:
    - `$text`: Buton üzerindeki metin.
    - `$variant`: Renk teması.
    - `$type`: HTML buton tipi (`submit`, `button`, `reset`).
    - `$icon`: FontAwesome ikon sınıfı.
    - `$full`: True ise butonu tam genişlik (`w-full`) yapar.
- **Kullanım**: `$this->insert('partials::button', [...])` şeklinde çağrılır.
