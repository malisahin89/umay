# Dosya Yükleme (File Upload)

Umay Framework, güvenli dosya yüklemeleri için kapsamlı bir `FileUpload` sınıfı sunar.

## Temel Yükleme İşlemi

Dosya yüklemek için `FileUpload::upload()` metodunu kullanın:

```php
$file = $_FILES['avatar'];

try {
    $path = FileUpload::upload(
        $file, 
        'avatars', // Kaydedilecek dizin (public/avatars)
        true,      // WebP'ye dönüştür (Optimize et)
        'user_' . $userId // İsteğe bağlı özel isim
    );
    
    echo "Dosya başarıyla yüklendi: " . $path;
} catch (Exception $e) {
    echo "Hata: " . $e->getMessage();
}
```

## Güvenlik Özellikleri

Sistem, dosya yükleme sırasında şu güvenlik kontrollerini otomatik olarak yapar:

1. **MIME Tipi Kontrolü**: Sadece izin verilen (JPEG, PNG, GIF, WebP) formatlar kabul edilir.
2. **Boyut Sınırı**: Varsayılan olarak 2MB sınırı uygulanır.
3. **Yol Güvenliği (Path Traversal)**: Dosya isimleri sanitize edilir ve `..` gibi tehlikeli karakterler engellenerek dosyaların sadece `public/` dizinine kaydedilmesi sağlanır.
4. **Hız Sınırı (Rate Limiting)**: IP başına dakikada maksimum 10 yükleme sınırı uygulanır.

## Dosya Yönetimi

### Dosya Yeniden Adlandırma
```php
FileUpload::rename('uploads/old.jpg', 'new_name.jpg');
```

### Dosya Silme
```php
FileUpload::delete('uploads/file.jpg');
```
