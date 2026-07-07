# ServiceProvider.php

## Amaç
`ServiceProvider` soyut sınıfı, servislerin kontrollü bir sıra ile kaydedilmesine ve başlatılmasına olanak tanıyarak framework'ün önyükleme (bootstrapping) sürecinin temelini oluşturur.

## Metadatalar
- **Ad Alanı**: `Core`
- **Dosya Konumu**: `core\ServiceProvider.php`

## Yaşam Döngüsü Metotları

### 1. `register()`
Servisleri `Core\Container`'a bağlamak için kullanılan soyut bir metot.
- **Kısıtlama**: Bu metot yalnızca bağlama (binding) işlemleri için kullanılmalıdır. Diğer servisleri çözmeye çalışmamalıdır, çünkü henüz kaydedilmemiş olabilirler.

### 2. `boot()`
Tüm servis sağlayıcılar kaydedildikten sonra çağrılan isteğe bağlı bir metot.
- **Kullanım**: Diğer servislere bağımlı olan mantıklar (örneğin, rota dosyalarının kaydı, olay dinleyicileri veya görünüm oluşturucular) için ideal yerdir.

## Dahili İş Akışı
`Application` sınıfı sağlayıcıları yönetir:
1. Kaydedilen tüm sağlayıcıların `register()` metotları çağrılır.
2. Kayıt işlemi tamamlandığında, uygulama her sağlayıcı üzerinde `boot()` metodunu çağırır.
