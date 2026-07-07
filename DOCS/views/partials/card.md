# Parça (Partial): Card

`views/partials/card.php` dosyası, içerikleri gruplandırmak ve görsel olarak ayırmak için kullanılan standart bir kart bileşenidir.

## Özellikler
- **Yapı**: Opsiyonel bir başlık (ikon destekli), ana içerik alanı ve opsiyonel bir alt bilgi (footer) alanından oluşur.
- **Görünüm**: Tailwind CSS ile gölge (`shadow-sm`), yuvarlatılmış köşeler ve hover efekti uygulanmıştır.
- **Parametreler**:
    - `$title`: Kart başlığı.
    - `$icon`: Başlık yanındaki ikon.
    - `$content`: Kartın gövde içeriği (HTML).
    - `$footer`: Kartın alt bilgi içeriği (HTML).
- **Kullanım**: `$this->insert('partials::card', [...])` şeklinde çağrılır.
