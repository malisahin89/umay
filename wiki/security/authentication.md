# Kimlik Doğrulama (Authentication)

Umay Framework, **takılabilir (pluggable)** bir kimlik doğrulama altyapısı sunar. Çekirdek (`Core\Auth`) somut bir kullanıcı sınıfına bağlı değildir; bunun yerine iki sözleşmeyle (interface) ve `config/auth.php` ile konuşur:

- **`Core\Contracts\Authenticatable`** — Kullanıcı modelinizin sözleşmesi (kimlik, şifre, remember-token).
- **`Core\Contracts\UserProvider`** — "Login beyni": kullanıcıyı depodan bulma ve şifre doğrulama.

Hangi modelin ve hangi provider'ın kullanılacağı tamamen `config/auth.php`'de belirlenir. Böylece kendi login mantığınızı çekirdeğe dokunmadan yazabilirsiniz.

> [!NOTE]
> Başlangıç iskeleti **hazır bir login ekranı/controller'ı içermez** (tıpkı Laravel'in temel iskeleti gibi). Giriş arayüzünü kendiniz oluşturursunuz; çekirdek size yalnızca güçlü auth motorunu verir.

## Yapılandırma (`config/auth.php`)

```php
return [
    'default' => 'eloquent',

    'providers' => [
        'eloquent' => [
            'driver' => Core\Auth\EloquentUserProvider::class,
            'model'  => App\Models\User::class,
        ],
    ],
];
```

Başlangıçta gelen generic `App\Models\User`, `Core\Contracts\Authenticatable`'ı implemente eder. Şifreler `User` modelindeki `setPasswordAttribute` mutator'ı sayesinde otomatik olarak `bcrypt` ile hash'lenir.

## Oturum Açma (Login)

Bir kullanıcının kimlik bilgilerini doğrulayıp oturum açmak için `Auth::attempt()` kullanılır. Bu metot bir **dizi** alır; kullanıcıyı bulma ve şifre doğrulama işini yapılandırılmış `UserProvider`'a devreder.

```php
use Core\Facades\Auth;

public function authenticate(Request $request)
{
    $credentials = [
        'email'    => $request->post('email'),
        'password' => $request->post('password'),
        'remember' => $request->post('remember') === 'on', // opsiyonel "beni hatırla"
    ];

    if (Auth::attempt($credentials)) {
        // Oturum başarıyla açıldı
        redirect('home');
    } else {
        // E-posta veya şifre yanlış
        flash('error', 'Hatalı giriş yaptınız.');
        back();
    }
}
```

> [!WARNING]
> Şifreleri hiçbir zaman düz metin (plain text) olarak kaydetmeyin. `User` modelindeki `setPasswordAttribute` mutator'ı, modele atadığınız şifreyi otomatik Hash'ten geçirir.

## Oturumu Kapatma (Logout)

```php
use Core\Facades\Auth;

public function logout()
{
    Auth::logout(); // Session'ı güvenle siler + session_regenerate_id ile hijacking koruması
    redirect('home');
}
```

## Kimlik Kontrolü ve `auth()` Helper

```php
// Oturum açan kullanıcıyı döndürür (Authenticatable). Giriş yapılmamışsa null.
$user = auth();

if ($user) {
    echo 'Hoş geldin, ' . $user->name;
}

// Facade ile:
Auth::check();   // bool — giriş yapılmış mı?
Auth::guest();   // bool — misafir mi?
Auth::id();      // ?int — kullanıcı ID'si
Auth::user();    // ?Authenticatable
```

## Rotaları Korumak

Başlangıç iskeletinde hazır bir `auth` middleware'i **gelmez**. İki yolunuz var:

**1) Controller içinde kontrol:**
```php
public function index()
{
    if (! auth()) {
        flash('error', 'Lütfen giriş yapın.');
        redirect('home');
        return;
    }
    // ...
}
```

**2) Kendi auth middleware'inizi oluşturun:**
```bash
php umay make:middleware Auth
```
İçinde `auth()`/`Auth::check()` ile kontrol edip rotaya ekleyin:
```php
Route::get('/profile', 'ProfileController@index')->middleware('auth');
```

## API Kimlik Doğrulama (Bearer Token)

Yukarıdaki akış **session tabanlı** web girişidir. API'ler ise **stateless**'tir: session ve CSRF çalışmaz; her istek `Authorization` başlığındaki bir **Bearer token** ile kendini doğrular. Umay bunu çekirdekte hazır sunar.

Token'lar veritabanında **hash'li** (`sha256`) saklanır; düz metin token yalnızca **oluşturma anında bir kez** görülür ve bir daha geri alınamaz. Her token'a opsiyonel **ability (yetki/scope)** listesi atanabilir.

### 1) Kurulum

Token deposu için migration'ı çalıştırın:

```bash
php umay migrate   # personal_access_tokens tablosunu oluşturur
```

Token üretecek modele `Core\Auth\HasApiTokens` trait'ini ekleyin. Başlangıçta gelen `App\Models\User` bunu **zaten kullanır**:

```php
use Core\Auth\HasApiTokens;

class User extends Model implements Authenticatable
{
    use HasApiTokens;
    // ...
}
```

### 2) Token Üretme

```php
// Tüm yetkilerle (varsayılan ['*'])
$result = $user->createToken('mobil-uygulama');

// Belirli yetkilerle (scope)
$result = $user->createToken('rapor-servisi', ['posts.read', 'posts.write']);

// İstemciye SADECE bunu verin — bir daha gösterilmez:
$plainTextToken = $result['plainTextToken']; // örn: "12|9f3c...e1"
$model          = $result['token'];          // PersonalAccessToken (DB kaydı)
```

> [!WARNING]
> `plainTextToken` yalnızca bu anda elde edilebilir; veritabanında yalnızca hash'i tutulur. Kullanıcıya gösterin/güvenli aktarın, saklamayın.

### 3) Rotaları Koruma

`api-auth` middleware'i geçerli bir token ister. İki nokta üst üste ile bir **ability** zorunlu kılabilirsiniz:

```php
// routes/api.php
use Core\Route;

// Geçerli herhangi bir token yeterli
Route::get('/me', 'Api\\UserController@me')->middleware('api-auth');

// Token'ın 'posts.write' yetkisine sahip olması şart
Route::post('/posts', 'Api\\PostController@store')->middleware('api-auth:posts.write');
```

`'*'` yetkisine sahip bir token, her ability kontrolünü geçer.

### 4) İstemci Tarafı (İstek)

İstemci token'ı `Authorization` başlığında gönderir:

```http
GET /api/me HTTP/1.1
Host: example.com
Authorization: Bearer 12|9f3c...e1
Accept: application/json
```

Eksik/geçersiz token `401`, yetkisi yetmeyen token `403` döner (her ikisi de JSON).

### 5) Doğrulanmış Kullanıcıya Erişim

`api-auth` başarılı olduğunda kullanıcı, **o istek için** kimliği doğrulanmış işaretlenir (session yazılmaz). Aynı `auth()` / `Auth` API'si çalışır:

```php
public function me()
{
    $user = auth();              // ?Authenticatable — token sahibi
    // veya: Auth::user(), Auth::check(), Auth::id()

    // Geçerli isteğin token'ı ve yetki kontrolü:
    if ($user->tokenCan('posts.write')) { /* ... */ }
    $token = $user->currentAccessToken(); // PersonalAccessToken|null

    return ['id' => $user->getAuthIdentifier(), 'name' => $user->name];
}
```

### 6) Token İptali (Revoke)

```php
// Tek bir token'ı sil
$user->tokens()->where('id', $tokenId)->delete();

// Kullanıcının tüm token'larını iptal et (her yerden çıkış)
$user->tokens()->delete();
```

> [!NOTE]
> API rotaları `config/middleware.php` içindeki `api` grubunu kullanır (Cors + throttle). Session ve CSRF bu rotalarda **çalışmaz** — kimlik doğrulama tamamen token iledir. CORS davranışı `cors_origin`, `cors_credentials` vb. ayarlarıyla kontrol edilir.

## Kendi Login Mantığınızı Yazmak (UserProvider)

Kullanıcıyı farklı bir kaynaktan (harici API, LDAP, ayrı bir tablo...) doğrulamak isterseniz, `Core\Contracts\UserProvider`'ı implemente edip `config/auth.php`'de driver olarak gösterin — çekirdeğe hiç dokunmadan:

```php
// app/Auth/ApiUserProvider.php
namespace App\Auth;

use Core\Contracts\Authenticatable;
use Core\Contracts\UserProvider;

class ApiUserProvider implements UserProvider
{
    public function retrieveById(int|string $id): ?Authenticatable { /* ... */ }
    public function retrieveByCredentials(array $credentials): ?Authenticatable { /* ... */ }
    public function validateCredentials(Authenticatable $user, array $credentials): bool { /* ... */ }
    public function retrieveByToken(int|string $id, string $token): ?Authenticatable { /* ... */ }
    public function updateRememberToken(Authenticatable $user, ?string $token): void { /* ... */ }
}
```

```php
// config/auth.php
'default' => 'api',
'providers' => [
    'api' => ['driver' => App\Auth\ApiUserProvider::class],
],
```
