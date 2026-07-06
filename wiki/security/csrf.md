# CSRF Koruması (Cross-Site Request Forgery)

Umay Framework, formlarınızın kötü niyetli kişiler tarafından sahte isteklerle (CSRF) doldurulmasını otomatik olarak engeller.

Sistem, POST, PUT, PATCH ve DELETE isteklerinde otomatik olarak `csrf_token` isimli token'ın varlığını ve doğruluğunu denetler.

## CSRF Token Ekleme

HTML formlarınızda, isteğin Umay tarafından güvenli kabul edilmesi için form içerisine muhakkak bir CSRF token alanı eklemelisiniz.

Plates view dosyalarında `$this->csrf()` metodunu kullanın:

```html
<form method="POST" action="/profile/update">
    <!-- Güvenlik için token alanı -->
    <?= $this->csrf() ?>
    
    <label>İsim:</label>
    <input type="text" name="name">
    
    <button type="submit">Güncelle</button>
</form>
```

`$this->csrf()` arka planda şu HTML çıktısını üretir:
```html
<input type="hidden" name="csrf_token" value="b4f6...9a21">
```

> [!CAUTION]  
> GET metodu ile çalışan formlarda (örneğin Arama formları) CSRF token eklemenize gerek yoktur, zira GET istekleri sunucuda kalıcı bir veri değişikliği yapmamalıdır.

## AJAX İsteklerinde CSRF

Javascript (Ör: Fetch API, Axios, jQuery) ile arkaplanda AJAX istekleri atarken token'ı form datası içine eklemek yerine `Header` (Başlık) olarak gönderebilirsiniz. Umay, `X-CSRF-TOKEN` başlığını otomatik tanır.

Sayfanızın `<head>` bölümünde token'ı bir meta etiketi ile tutun:
```html
<meta name="csrf-token" content="<?= csrf_token() ?>">
```

Sonra Axios vb. kütüphaneler ile bu token'ı çekip başlık olarak gönderin:
```javascript
let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

axios.post('/api/endpoint', data, {
    headers: {
        'X-CSRF-TOKEN': token
    }
});
```

Eğer gönderilen token geçersizse, eksikse veya session bitmişse, uygulama otomatik olarak `TokenMismatchException` fırlatır ve isteği bloke eder.
