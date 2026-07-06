# TerminateException

`core/TerminateException.php` dosyası, uygulamanın çalışma akışını (request lifecycle) kontrollü bir şekilde sonlandırmak için kullanılan özel bir exception sınıfıdır.

## Temel Görevi
Bu exception gerçek bir sistem hatasını değil, yanıtın (response) istemciye başarıyla gönderildiğini ve artık PHP sürecinin durdurulması gerektiğini işaret eder.

## Çalışma Mekanizması
1. **Tetikleme**: `Redirect::to()` veya `ResponseBuilder::send()` gibi metotlar, HTTP header'larını ve body'sini gönderdikten sonra bu exception'ı fırlatır.
2. **Yakalama**: `index.php` veya `ExceptionHandler` tarafından yakalanır.
3. **Sonlandırma**: `ExceptionHandler` bu exception'ı gördüğünde, herhangi bir hata mesajı göstermeden işlemi sessizce sonlandırır.

## Kullanım Alanları
- **Yönlendirmeler**: `header('Location: ...')` çağrısından sonra kodun çalışmaya devam etmesini engellemek için.
- **Yanıt Gönderimi**: Response body'si echo edildikten sonra lifecycle'ı bitirmek için.
- **Güvenlik**: HTTPS yönlendirmeleri gibi kritik işlemlerde akışı kesmek için.
