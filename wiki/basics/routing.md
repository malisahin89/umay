# Yönlendirme (Routing)

Umay Framework, son derece hızlı okunaklı bir yönlendirme (routing) altyapısına sahiptir. Tüm rotalarınız `routes/` klasörü içinde tanımlanır.

## Temel Rotalama

En basit Umay rotaları, bir URI ve bir Closure (anonim fonksiyon) kabul eder. Rotalar `routes/web.php` içinde tanımlanır.

```php
use Core\Route;

Route::get('/', function () {
    return 'Merhaba Dünya!';
});

Route::post('/iletisim', function () {
    return 'Mesaj alındı!';
});
```

Aşağıdaki HTTP metodları desteklenmektedir:
`Route::get()`, `Route::post()`, `Route::put()`, `Route::patch()`, `Route::delete()`, `Route::options()`.

## Controller'a Yönlendirme

Rotalarınızı doğrudan Closure yerine Controller sınıflarına bağlamak en iyi yöntemdir:

```php
// HomeController içerisindeki index metodunu çağırır
Route::get('/hakkimizda', 'HomeController@index');

// Alt klasördeki (sub-namespace) controller'ı çağırır → App\Controllers\Admin\ReportController
Route::get('/panel/rapor', 'Admin\\ReportController@index');
```

## Rota Parametreleri

URL üzerinden dinamik değerler (ID, slug vb.) almak için süslü parantez `{}` kullanılır.

```php
Route::get('/kullanici/{id}', function (Core\Request $request, string $id) {
    return 'Kullanıcı ID: ' . $id;
});

Route::get('/yazi/{slug}', 'PostController@show');
```

## İsimlendirilmiş Rotalar (Named Routes)

Rotalara isim vermek, uygulamanın geri kalanında (özellikle View'larda veya Redirect işlemlerinde) URL'leri dinamik olarak oluşturmanızı sağlar.

```php
Route::get('/profilim', 'ProfileController@index')->name('profile.index');
```

Başka bir yerde bu rotaya gitmek için `route()` helper fonksiyonunu kullanabilirsiniz:

```php
$url = route('profile.index');
// Çıktı: /profilim

// Parametreli rota üretimi
$url = route('user.show', ['id' => 5]);
```

## Rota Grupları ve Middleware

Aynı özellikleri (Önek, Middleware vb.) paylaşan rotaları gruplayabilirsiniz:

```php
Route::prefix('/admin')->middleware('throttle:60,60')->group(function () {
    
    Route::get('/', 'AdminController@index')->name('admin.dashboard');
    Route::get('/users', 'AdminController@users')->name('admin.users');
    
});
```
Bu örnekte `/admin` ve `/admin/users` rotaları oluşur ve her ikisi de `throttle` middleware'inden geçer. Başlangıç iskeletinde hazır gelen tek route-seviyesi middleware `throttle`'dır; kendi middleware'inizi `php umay make:middleware Auth` ile oluşturup `->middleware('auth')` şeklinde kullanabilirsiniz.

## Özel Rota Tanımları

### View Rotaları
Eğer bir rota sadece bir şablon (view) döndürecekse `Route::view` kullanabilirsiniz:

```php
Route::view('/hakkimizda', 'pages.about', ['title' => 'Hakkımızda']);
```

### Resource Rotaları
RESTful yapıda bir Controller oluşturmak için tek satır yeterlidir:

```php
Route::resource('users', 'UserController');
```
Bu satır otomatik olarak `index`, `create`, `store`, `show`, `edit`, `update`, `destroy` rotalarını oluşturur.
