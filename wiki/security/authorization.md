# Yetkilendirme (Authorization)

> [!NOTE]
> Başlangıç iskeleti **hazır bir RBAC (rol/izin) sistemi içermez**. Bu, her uygulamanın yetki ihtiyacı farklı olduğu içindir — Umay size dayatmaz, gerektiği kadarını kendiniz eklersiniz. Aşağıda yetkilendirmeyi kendi projenize nasıl ekleyeceğiniz adım adım gösterilmiştir.

Kimlik doğrulama (kullanıcının kim olduğu) için [Kimlik Doğrulama](security/authentication.md) sayfasına bakın. Bu sayfa, giriş yapmış kullanıcının **ne yapabileceğini** (yetki) kapsar.

## 1) Modele bir rol alanı ekleyin

Bir migration ile `users` tablosuna `role` kolonu ekleyin ve `User` modeline yardımcı bir metot koyun:

```php
// app/Models/User.php
public function isAdmin(): bool
{
    return $this->role === 'admin';
}
```

> `role` gibi hassas alanları toplu atamadan (mass assignment) korumak için `$fillable` dışında tutun; açıkça `$user->role = 'admin'` şeklinde atayın.

## 2) Kendi yetki middleware'inizi oluşturun

```bash
php umay make:middleware Admin
```

```php
// app/Middleware/AdminMiddleware.php
namespace App\Middleware;

use Core\Contracts\MiddlewareInterface;
use Core\Request;
use Core\Response;

class AdminMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, \Closure $next): mixed
    {
        $user = auth();

        if (! $user || ! $user->isAdmin()) {
            Response::forbidden('Bu sayfaya erişim yetkiniz yok.');
            return null;
        }

        return $next($request);
    }
}
```

Router, `admin` adını otomatik olarak `App\Middleware\AdminMiddleware`'e çözer (bkz. `config/middleware.php` → `namespaces`). Artık rotalarda kullanabilirsiniz:

```php
Route::prefix('/admin')->middleware('admin')->group(function () {
    Route::get('/settings', 'AdminSettingsController@index');
});
```

Yetkisiz bir kullanıcı 403 (Forbidden) yanıtı alır.

## 3) Kod içinde yetki kontrolü

```php
$user = auth();

if (! $user || ! $user->isAdmin()) {
    flash('error', 'Bu işlem için yetkiniz yok!');
    redirect('home');
    return;
}

// İşlemi yap...
```

## İzin tabanlı (permission) yetkilendirme

Daha ince taneli izinler (örn. `posts.delete`) istiyorsanız, bir `permissions` / `role_permissions` şeması kurup `User` modeline bir `hasPermission()` metodu ekleyebilir, ardından yukarıdakine benzer bir `permission` middleware'i yazabilirsiniz. Eloquent ORM'nin ilişki (relations) desteği sayesinde bu yapı dakikalar içinde kurulur.

> İpucu: Bu tam teşekküllü RBAC kurulumunun çalışan bir örneğini görmek isterseniz, `demo-app` branch'indeki `App\Models\Permission`, `App\Middleware\PermissionMiddleware` ve ilgili migration'lara bakabilirsiniz.
