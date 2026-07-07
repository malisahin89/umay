# Dosya Raporu: public/css/style.css

## Amaç
Web kök dizininden sunulan temel stil dosyası.

## Genel Bakış
Temel bir yönetici tarzı kullanıcı arayüzü için düzen ve bileşen stillerini tanımlayan küçük, bağımsız bir CSS dosyasıdır: sayfa gövdesi, üst bilgi çubuğu, navigasyon bağlantıları içeren sabit bir yan menü, ana içerik alanı ve uyarı kutuları.

## Dosya Konumu
`public/css/style.css`

## Seçiciler / Kurallar
- `body` — temel yazı tipi (`'Segoe UI', sans-serif`), açık renkli arka plan (`#f4f6f9`), kenar boşluğu yok.
- `header` — koyu çubuk (`#343a40`), beyaz metin, flex düzeni (space-between, ortalanmış).
- `.sidebar` — sabit sol yan menü, 220px genişlik, tam yükseklik, koyu arka plan (`#222d32`).
- `.sidebar a` / `.sidebar a:hover` — blok navigasyon bağlantıları, hover arka planı `#1a2226`.
- `.main-content` — yan menü genişliği kadar kaydırılmış (`margin-left: 220px`), iç boşluk verilmiş.
- `.alert`, `.alert-success`, `.alert-error` — başarı (`#d4edda`/`#155724`) ve hata (`#f8d7da`/`#721c24`) renk çiftlerine sahip bildirim kutuları.

## Harici Kullanım
- Web kök dizininden statik olarak sunulur; şablonlar tarafından `asset()` yardımcısı aracılığıyla referans verilir (bkz. `DOCS/core/View.md`, `DOCS/core/helpers.md` içindeki `asset()`).

> Analiz edilen `views/` şablonlarında `css/style.css`'e yönelik doğrulanmış bir `<link>` referansı bulunmamıştır; statik bir varlık olarak sunulur.

## Kaynak Referansları
- `public/css/style.css:1-57`
