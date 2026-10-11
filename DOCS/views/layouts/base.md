# Dosya Raporu: views/layouts/base.php

## Amaç
Temel HTML düzen şablonu.

## Genel Bakış
Metadata, CSS içe aktarmaları (Tailwind CSS, Google Fonts, Font Awesome) ve gövde içeriği için bir yer tutucu dahil olmak üzere standart HTML yapısını sağlar.

## Dosya Konumu
`views/layouts/base.php`

## Temel Özellikler
- **Dinamik Başlık**: Kontrolcü veya görünümden iletilen `$title` değişkenini kullanır.
- **CSP Entegrasyonu**: Satır içi stiller için nonce'lar sağlamak amacıyla `$this->nonce()` kullanır ve katı İçerik Güvenliği Politikalarıyla uyumluluğu sağlar.
- **İçerik Bölümleri**: Alt görünümlerden içeriği enjekte etmek için `<?= $this->section('body') ?>` kullanır.

## Kaynak Referansları
- `views/layouts/base.php:1-38`
