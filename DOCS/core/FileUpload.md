# Dosya Raporu: core/FileUpload.php

## Amaç
Güvenli dosya yükleme ve işleme aracı.

## Genel Bakış
Sıkı güvenlik kontrolleriyle (MIME türü, boyut, yol aşımı koruması) dosya yüklemelerini yönetir. Optimizasyon için resimleri otomatik olarak WebP formatına dönüştürebilir.

## Dosya Konumu
`core/FileUpload.php`

## Ad Alanı
`Core`

## İçe Aktarmalar
- `Core\Facades\Log`

## Sınıflar
- `class FileUpload`

## Özellikler
- `$allowedTypes`: İzin verilen MIME türlerinin listesi (JPEG, PNG, GIF, WebP).
- `$mimeToExt`: MIME türlerinden dosya uzantılarına eşleme.
- `$maxSize`: İzin verilen maksimum dosya boyutu (2MB).
- `$quality`: WebP sıkıştırma kalitesi (70).

## Metotlar
- `upload(array $file, string $directory = 'uploads', bool $convertToWebP = true, ?string $customFilename = null): string`: Ana yükleme işlemi. Hız sınırlama, güvenlik kontrolleri ve isteğe bağlı WebP dönüşümünü içerir.
- `rename(string $oldPath, string $newFilename): string|false`: Yüklenen bir dosyayı güvenli bir şekilde yeniden adlandırır, yeni yolun `public/` dizini içinde kaldığından emin olur.
- `delete(string $filePath): bool`: Dosyanın `public/` dizini içinde olduğunu doğruladıktan sonra dosyayı siler.

## Dahili İş Akışı
1. **Hız Sınırlama**: `Cache::atomic` kullanarak yüklemeleri IP başına dakikada 10 ile sınırlar.
2. **Güvenlik Kontrolleri**: `is_uploaded_file`'ı doğrular, dosya boyutunu kontrol eder ve `finfo` aracılığıyla MIME türünü doğrular.
3. **Yol Aşımı Koruması**: `..` içeren veya mutlak yollar içeren dizinleri reddeder.
4. **Güvenli Adlandırma**: Özel bir ad sağlanmadıkça kriptografik olarak rastgele bir dosya adı oluşturur (sağlanan ad ise temizlenir).
5. **WebP Dönüşümü**: Etkinse ve destekleniyorsa GD'nin `imagewebp` fonksiyonunu kullanır.
6. **Yol Doğrulaması**: `assertPathInsidePublic()` tüm dosya işlemlerinin `public/` klasörü ile sınırlandırılmasını sağlar.

## Bağımlılıklar
- `Core\Cache` (Hız sınırlama için kullanır)
- `Core\Facades\Log` (Yol aşımı girişimlerini kaydetmek için kullanır)

## Kaynak Referansları
- `core/FileUpload.php:1-298`
