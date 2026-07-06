# Umay Framework - Routing (Yönlendirme)

Umay Framework'te rotalar, gelen URL'leri uygun Controller metodlarına veya anonim fonksiyonlara (Closure) bağlamak için kullanılır. Tüm rota tanımlamaları `routes/` dizininde yapılır.

## Rota Dosyaları
- **`web.php`**: Tarayıcı üzerinden gelen ve genellikle View (HTML) döndüren istekler içindir. `web` middleware grubu otomatik uygulanır: `RememberMe`, `SecurityHeaders`, `VerifyCsrfToken` (bkz. `config/middleware.php`). Session, `public/index.php` içinde başlatılır.
- **`api.php`**: Mobil cihazlar veya frontend framework'lerinden (React/Vue) gelen API istekleri içindir. `api_prefix` (varsayılan `/api`) altında yüklenir; `api` middleware grubu (`Cors`, `throttle`) uygulanır. Session/CSRF yoktur (stateless). Token doğrulamasını route seviyesinde ekleyin.

## Eylem (action) biçimleri

Bir rotanın eylemi ya bir **Closure** ya da **`'Controller@method'` string'idir**. Array-callable (`[Class::class, 'method']`) desteklenmez. Controller'lar, `config/app.php` → `controller_namespace` (varsayılan `App\Controllers\`) altında çözülür.

## Örnek Kullanımlar

```php
use Core\Route;

// View rotası — controller olmadan doğrudan şablon döndürür
Route::view('/', 'welcome')->name('home');

// Closure rotası
Route::get('/ping', fn () => 'pong');

// Controller rotası (string action: 'Controller@method')
Route::get('/posts', 'PostController@index')->name('posts.index');

// Parametreli rota
Route::get('/posts/{id}', 'PostController@show');

// Opsiyonel parametre
Route::get('/posts/{id?}', 'PostController@show');

// Rate limiting — iskelette hazır gelen tek route-seviyesi middleware
Route::post('/posts', 'PostController@store')->middleware('throttle:10,60');

// Prefix + grup
Route::prefix('/admin')->middleware('throttle:60,60')->group(function () {
    Route::get('/', 'AdminController@index')->name('admin.dashboard');
    Route::get('/users', 'AdminController@users')->name('admin.users');
});

// RESTful resource (index/create/store/show/edit/update/destroy)
Route::resource('posts', 'PostController');
```

> `auth` / `admin` gibi middleware'ler iskelette **gelmez**. Kendi middleware'inizi `php umay make:middleware Auth` ile oluşturup `->middleware('auth')` şeklinde kullanın; router, `auth` adını `App\Middleware\AuthMiddleware`'e otomatik çözer (bkz. `config/middleware.php` → `namespaces`).
