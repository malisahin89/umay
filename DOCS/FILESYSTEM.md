# Dosya Sistemi

## Amaç
Çalışma zamanı depolama konumlarını ve dosya yükleme yönetimini belgeler.

## Genel Bakış
Umay, çalışma zamanı artıklarını `storage/` altında yazar ve kullanıcı yüklemelerini `Core\FileUpload` aracılığıyla yönetir. Genel bir dosya sistemi/disk soyutlaması yoktur; bileşenler bilinen yollar üzerinden doğrudan PHP dosya fonksiyonlarını kullanır.

## Depolama Düzeni (`storage/`)
- `storage/cache/` — dosya önbellek girdileri artı `*.lock` / `*.tmp` yan dosyaları (bkz. `DOCS/CACHE.md`).
- `storage/logs/` — günlük dosya kayıtları `Y-m-d.log` (bkz. `DOCS/LOGGING.md`).
- `storage/profiler/` — profiler artıkları (bkz. `DOCS/PERFORMANCE.md`).

Önbellek ve günlük dizinleri, `0700` modu ile talep üzerine oluşturulur.

## Dosya Yüklemeleri (`Core\FileUpload`)
Doğrulanmış davranışlar (`Core\FileUpload` ve testlerinden):
- **Dosya adı temizleme** — Türkçe/özel karakterleri temizler, alfanümerik/tire/alt çizgiye izin verir, sonuç boş olduğunda `uniqid()`'ye döner ve yol aşımı (path-traversal) girişimlerine karşı korur.
- **Yol sınırlaması** — doğrulanan yollar `public/` içinde çözümlenmelidir; dışındaki yollar hata fırlatır.
- **Tür izin listesi** — JPEG gibi resim türlerine izin verilir; yürütülebilir türler (örneğin PHP) reddedilir.
- **Boyut sınırı** — maksimum yükleme boyutu 2 MB'dır.
- **Güvenli işlemler** — `rename`/`delete` işlemleri, varsayılan/boş/olmayan hedefler için hata vermek yerine `false` döndürür.

## Güvenlik Gözlemleri
- Yükleme yolu sınırlaması + tür izin listesi + uzantı temizleme; yol aşımı ve yürütülebilir dosya yüklemesini engeller.
- Oluşturulan depolama dizinlerinde `0700` izinleri uygulanır.

## Çapraz Referanslar
- `Core\FileUpload` (bkz. `DOCS/core/FileUpload.md`), testler: `DOCS/tests/Unit/FileUploadTest.md`
- `Core\Cache` (bkz. `DOCS/CACHE.md`), `Core\Logger` (bkz. `DOCS/LOGGING.md`)

## Kaynak Referansları
- `core/FileUpload.php`
- `core/Cache.php:44-46`, `core/Logger.php:25-28`, `core/Logger.php:66`
