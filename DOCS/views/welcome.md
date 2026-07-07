# Dosya Raporu: views/welcome.php

## Amaç
Framework için karşılama (landing) sayfası.

## Genel Bakış
Tailwind CSS ve özel animasyonlar kullanarak framework'ün özelliklerini sergileyen, yüksek düzeyde stilize edilmiş bir tanıtım sayfasıdır.

## Dosya Konumu
`views/welcome.php`

## Uygulama
- **Düzen (Layout)**: `layouts/base` düzenini genişletir.
- **İçerik**:
    - Navigasyon çubuğu.
    - Logo ve değer önermesi içeren Hero bölümü.
    - Etkileşimli kod önizleme sekmeleri (Web, API, Controller, Model, Middleware).
    - Yönlendirme, Eloquent, Middleware, Konteyner, CLI ve Profiler'ı vurgulayan özellik ızgarası.
- **Dinamik Veriler**: `config('app.version')` kullanarak framework sürümünü ve PHP sürümünü görüntüler.

## Kaynak Referansları
- `views/welcome.php:1-539`
