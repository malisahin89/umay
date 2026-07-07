# Dosya Raporu: core/Auth.php

## Amaç
İstek tabanlı önbelleğe alınmış kimlik doğrulama koruması (guard).

## Genel Bakış
Kullanıcı kimlik doğrulamasını, oturum tabanlı giriş/çıkış işlemlerini ve "beni hatırla" işlevselliğini yönetir. `UserProvider` ve `Authenticatable` sözleşmeleri aracılığıyla belirli kullanıcı modellerinden bağımsızdır.

## Dosya Konumu
`core/Auth.php`

## Ad Alanı
`Core`

## İçe Aktarmalar
- `Core\Auth\EloquentUserProvider`
- `Core\Contracts\Authenticatable`
- `Core\Contracts\UserProvider`

## Sınıflar
- `class Auth`

## Özellikler
- `?Authenticatable $cachedUser`: Mevcut istek için kimlik doğrulanmış kullanıcıyı önbelleğe alır.
- `?UserProvider $provider`: Yapılandırmadan çözümlenen aktif kullanıcı sağlayıcısı.

## Metotlar
- `provider(): UserProvider`: `config/auth.php` dosyasından aktif `UserProvider`'ı çözer.
- `setProvider(UserProvider $provider): void`: Aktif sağlayıcıyı manuel olarak geçersiz kılar.
- `user(): ?Authenticatable`: Şu an giriş yapmış olan kullanıcıyı döndürür.
- `id(): int|string|null`: Kimlik doğrulanmış kullanıcının tanımlayıcısını döndürür.
- `check(): bool`: Bir kullanıcının giriş yapıp yapmadığını kontrol eder.
- `guest(): bool`: Mevcut ziyaretçinin misafir olup olmadığını kontrol eder.
- `setUser(Authenticatable $user): void`: Mevcut istek için kimlik doğrulanmış kullanıcıyı manuel olarak ayarlar (stateless).
- `login(Authenticatable $user, bool $remember = false): void`: Bir kullanıcıyı oturuma dahil eder ve isteğe bağlı olarak "beni hatırla" çerezi ayarlar.
- `logout(): void`: Oturumu kapatır, oturum ve çerezleri temizler.
- `attempt(array $credentials): bool`: Sağlanan kimlik bilgileriyle bir kullanıcıyı kimlik doğrulamayı dener.
- `clearCache(): void`: İstek yerel kullanıcı önbelleğini temizler.

## Bağımlılıklar
- `Core\Contracts\UserProvider` (Kullanır)
- `Core\Contracts\Authenticatable` (Kullanır)
- `Core\Auth\EloquentUserProvider` (Varsayılan)

## Çapraz Referanslar
- `config/auth.php` (Yapılandırma)

## Kaynak Referansları
- `core/Auth.php:1-277`
