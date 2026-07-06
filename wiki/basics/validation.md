# Doğrulama (Validation)

Umay Framework, gelen verileri hızlıca doğrulamak için güçlü ve esnek bir `Validator` motoruna sahiptir.

## Temel Kullanım

Verileri doğrulamak için `validate()` yardımcı fonksiyonu kullanılır:

```php
$data = $_POST;
$rules = [
    'email' => 'required|email|unique:users',
    'password' => 'required|min:8',
    'age' => 'numeric|between:18,99',
];

$messages = [
    'email.required' => 'E-posta adresi zorunludur.',
    'email.unique' => 'Bu e-posta zaten kayıtlı.',
];

if (!validate($data, $rules, $messages)) {
    // Hata durumunda yönlendir veya hataları dön
    return redirect('register.show')->withErrors(errors());
}
```

## Desteklenen Kurallar

| Kural | Açıklama |
| :--- | :--- |
| `required` | Alanın mevcut ve dolu olmasını zorunlu kılar. |
| `sometimes` | Alan mevcutsa doğrula, yoksa atla. |
| `email` | Geçerli bir e-posta formatı olmalı. |
| `numeric` | Değer sayısal olmalı. |
| `min:value` | Minimum uzunluk veya değer. |
| `max:value` | Maksimum uzunluk veya değer. |
| `unique:table,column` | Veritabanında benzersiz olmalı. |
| `exists:table,column` | Veritabanında kayıt mevcut olmalı. |
| `confirmed` | `field_confirmation` alanı ile aynı olmalı. |
| `regex:pattern` | Belirtilen düzenli ifadeye uymalı. |

## FormRequest Kullanımı

Daha temiz kontrolcüler için `Core\FormRequest` sınıfından türetilmiş özel istek sınıfları oluşturabilirsiniz. Bu sınıflar, kontrolcü metoduna girmeden önce otomatik olarak doğrulamayı çalıştırır.
