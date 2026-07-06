# İstekler (Requests)

Umay Framework'te uygulamanıza gelen HTTP istekleri `Core\Request` nesnesi tarafından yakalanır ve yönetilir.

## Request Nesnesine Erişmek

Controller metotlarında `Request` nesnesine Dependency Injection (Bağımlılık Enjeksiyonu) ile ulaşabilirsiniz:

```php
namespace App\Controllers;

use Core\Request;

class UserController
{
    public function store(Request $request)
    {
        $name = $request->input('name');
    }
}
```

## Girdi (Input) Alma

Kullanıcıdan gelen form verileri (POST/GET) için çeşitli metodlar mevcuttur:

```php
// Sadece POST verisini al (Varsayılan değer: 'Misafir')
$name = $request->post('name', 'Misafir');

// Sadece GET (Query String) verisini al
$page = $request->get('page', 1);

// GET veya POST fark etmeksizin veriyi al
$email = $request->input('email');

// Gelen tüm verileri dizi (Array) olarak al
$allData = $request->all();

// Sadece belirli alanları al
$credentials = $request->only(['email', 'password']);

// Belirli alanları hariç tutarak tüm veriyi al
$data = $request->except(['_csrf', 'password_confirmation']);
```

## Dosya Yükleme (File Upload)

Dosya yüklemeleri için `Core\FileUpload` sınıfı kullanılır.

```php
use Core\FileUpload;

public function uploadAvatar(Request $request)
{
    // Dosya gelmiş mi?
    if ($request->hasFile('avatar')) {
        
        $uploader = new FileUpload();
        
        // Sadece JPG ve PNG'ye izin ver (Güvenlik)
        $uploader->setAllowedMimeTypes(['image/jpeg', 'image/png']);
        
        // storage/uploads/avatars dizinine yükle
        $path = $uploader->upload('avatar', 'avatars');
        
        return 'Dosya yüklendi: ' . $path;
    }
}
```

> [!IMPORTANT]  
> Umay Framework `FileUpload` modülü Path Traversal saldırılarına karşı dahili korumaya sahiptir. Null byte (`\0`) ve `../` karakterlerini otomatik temizler.

## Veri Doğrulama (Validation)

Gelen verileri kaydetmeden önce `Core\Validator` sınıfı ile kurallara göre doğrulayabilirsiniz:

```php
use Core\Validator;

$validator = new Validator();

$data = $request->all();
$rules = [
    'username' => 'required|alphanumeric',
    'email'    => 'required|email|unique:users,email',
    'password' => 'required|min:8'
];

if (! $validator->validate($data, $rules)) {
    // Doğrulama başarısız!
    $errors = $validator->errors();
    
    // Hataları session'a yazıp bir önceki forma geri dön
    flash('errors', $errors);
    back();
    return;
}

// Başarılı ise kaydet...
```
