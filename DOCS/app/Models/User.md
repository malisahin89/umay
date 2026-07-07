# Dosya Raporu: app/Models/User.php

## Amaç
Kimlik doğrulama ve kullanıcı veri yönetimi için kullanıcı varlık modeli.

## Genel Bakış
Veritabanındaki `users` tablosunu temsil eder ve çerçeve kimlik doğrulama sisteminin kullanıcı kimlik doğrulamasını yönetebilmesi için `Authenticatable` arayüzünü uygular.

## Dosya Konumu
`app/Models/User.php`

## İsim Uzayı
`App\Models`

## İçe Aktarmalar
- `Core\Auth\HasApiTokens`
- `Core\Contracts\Authenticatable`
- `Core\Model`
- `Illuminate\Support\Carbon`

## Sınıflar
- `class User extends Model implements Authenticatable`

## Trait'ler
- `HasApiTokens` (Bearer-token düzenlemesi için kullanılır)

## Özellikler
- `$table`: `users`
- `$fillable`: `['name', 'email', 'password']`
- `$hidden`: `['password', 'remember_token']`

## Metotlar
- `setPasswordAttribute(mixed $value): void`: Şifre ayarlandığında otomatik olarak `password_hash` kullanarak şifreyi özetleyen mutator.
- `getAuthIdentifier(): int|string`: Kimlik doğrulama tanımlayıcısı olarak kullanıcının ID'sini döndürür.
- `getAuthPassword(): string`: Kimlik doğrulama için özetlenmiş şifreyi döndürür.
- `getRememberToken(): ?string`: Beni hatırla token'ını döndürür.
- `setRememberToken(?string $token): void`: Beni hatırla token'ını ayarlar.

## Bağımlılıklar
- `Core\Model` (Genişletir)
- `Core\Contracts\Authenticatable` (Uygular)

## Çapraz Referanslar
- `Core\Auth\HasApiTokens` (Kullanır)

## Kaynak Referansları
- `app/Models/User.php:1-74`
