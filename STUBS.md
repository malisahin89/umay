# Umay Framework - Stubs (Şablonlar)

`stubs/` dizini, `php umay make:*` komutlarıyla otomatik olarak oluşturulan dosyaların (Controller, Model, Middleware, vb.) taslak (template) metinlerini barındırır.

## Kullanım Mantığı
Eğer komut satırından `php umay make:controller User` yazarsanız, framework `stubs/controller.stub` dosyasını okur, içerisindeki `{{ClassName}}` veya `{{namespace}}` gibi yer tutucuları (placeholders) projedeki gerçek değerlerle değiştirir ve yeni dosyayı `app/Controllers/UserController.php` olarak kaydeder.

## Özelleştirme
Projenizin yapısına göre standart Controller, Model veya Migration şablonlarını değiştirmek isterseniz bu dizindeki `.stub` uzantılı dosyaları dilediğiniz gibi düzenleyebilirsiniz. Yeni oluşturulacak tüm sınıflar bu düzenlediğiniz yapıya uygun olarak üretilecektir.
