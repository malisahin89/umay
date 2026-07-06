# Controller Sınıfları

Controller'lar (Denetleyiciler), HTTP isteklerini işleyen ve iş mantığını Model'ler ile View'lar arasında koordine eden sınıflardır. `app/Controllers/` dizininde yer alırlar.

## Temel Controller Oluşturma

Umay CLI kullanarak saniyeler içinde yeni bir Controller oluşturabilirsiniz:

```bash
php umay make:controller PostController
```

Oluşan Controller dosyası şu şekilde görünür:

```php
<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Request;
use Core\View;

class PostController
{
    public function index(Request $request)
    {
        return 'Post listesi';
    }
}
```

## Bağımlılık Enjeksiyonu (Dependency Injection)

Umay Framework, Service Container aracılığıyla Controller metotlarınıza parametreleri otomatik olarak enjekte eder (Auto-wiring).

Örneğin, `Request` nesnesine ulaşmak için metot imzasına eklemeniz yeterlidir:

```php
public function store(Request $request, string $id)
{
    $isim = $request->post('name');
    
    // İşlemler...
}
```

## RESTful Resource Controller

Eğer CRUD (Oluşturma, Okuma, Güncelleme, Silme) işlemlerini standartlaştırmak istiyorsanız Resource Controller oluşturabilirsiniz:

```bash
php umay make:controller ProductController --resource
```

Bu komut, içerisinde `index`, `create`, `store`, `show`, `edit`, `update` ve `destroy` metotları hazır bulunan bir sınıf oluşturur. Ardından `routes/web.php` içerisinde tek satırla bağlayabilirsiniz:

```php
use Core\Route;

Route::resource('products', 'ProductController');
```
