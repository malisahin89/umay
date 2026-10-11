# Middleware (Ara Katmanlar)

Middleware'ler, bir HTTP isteği kontrolcüye ulaşmadan önce veya yanıt kullanıcıya dönmeden önce araya giren katmanlardır.

## Middleware Nasıl Çalışır?

Umay, "Onion" (Soğan) mimarisini kullanır. İstek her bir middleware katmanından geçer, en içteki kontrolcüye ulaşır ve ardından tekrar aynı katmanlardan geçerek yanıt olarak döner.

## Yerleşik Middleware'ler

Framework ile birlikte gelen bazı önemli middleware'ler:

- **`VerifyCsrfToken`**: Form gönderimlerinde CSRF token kontrolü yapar.
- **`Auth`**: Kullanıcının oturum açmış olmasını zorunlu kılar; misafirleri `config('auth.login_path')` yoluna yönlendirir, JSON bekleyen isteklerde ise 401 döndürür. `auth` takma adıyla kullanılır.
- **`ApiAuth`**: Bearer Token ile API kimlik doğrulaması yapar.
- **`SecurityHeaders`**: `X-Frame-Options`, `X-Content-Type-Options` gibi güvenlik başlıklarını ekler.
- **`Cors`**: Cross-Origin Resource Sharing ayarlarını yönetir.
- **`RememberMe`**: "Beni Hatırla" çerezlerini kontrol eder.

## Middleware Atama

### Global Middleware
`config/middleware.php` dosyasındaki `global` dizisine eklenen middleware'ler her istekte çalışır.

### Route Bazlı Middleware
Sadece belirli route'lar için middleware atayabilirsiniz:

```php
Route::get('/profile', 'UserController@profile')->middleware('auth');
```

### Gruplandırma
```php
Route::prefix('/admin')->middleware('admin')->group(function () {
    Route::get('/dashboard', 'AdminController@index');
});
```
