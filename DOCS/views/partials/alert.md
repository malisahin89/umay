# Parça (Partial): Alert

`views/partials/alert.php` dosyası, uygulamada bildirimler ve uyarılar için kullanılan yeniden kullanılabilir bir bileşendir.

## Özellikler
- **Dinamik Tipler**: `success`, `error`, `warning` ve `info` tiplerine göre farklı renkler ve FontAwesome ikonları atar.
- **Parametreler**:
    - `$type`: Uyarı tipi.
    - `$message`: Gösterilecek mesaj.
    - `$dismissible`: True ise kapatma butonu ekler.
- **Kullanım**: `$this->insert('partials::alert', [...])` şeklinde çağrılır.
