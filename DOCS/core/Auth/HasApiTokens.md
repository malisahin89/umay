# Dosya Raporu: core/Auth/HasApiTokens.php

## Amaç
Bir kullanıcı modeline API token oluşturma özelliği kazandıran trait.

## Genel Bakış
Kişisel erişim token'larının (Bearer token'lar) oluşturulması ve yönetilmesi için işlevsellik sağlar. Bu, uygulamanın durumsuz (stateless) API isteklerini kimlik doğrulamasına olanak tanır.

## Dosya Konumu
`core/Auth/HasApiTokens.php`

## Ad Alanı
`Core\Auth`

## Trait'ler
- `trait HasApiTokens`

## Metotlar
- `tokens(): MorphMany`: Kullanıcının kişisel erişim token'larına olan ilişkiyi döndürür.
- `createToken(string $name, array $abilities = ['*'], ?\DateTimeInterface $expiresAt = null): array`: Yeni bir token oluşturur, onu hashler, veritabanına kaydeder ve düz metin token'ı döndürür.
- `withAccessToken(PersonalAccessToken $token): static`: Mevcut istek için belirli bir token'ı model örneğine bağlar.
- `currentAccessToken(): ?PersonalAccessToken`: Mevcut isteği kimlik doğrulamak için kullanılan token'ı döndürür.
- `tokenCan(string $ability): bool`: Mevcut token'ın belirli bir izne sahip olup olmadığını kontrol eder.

## Bağımlılıklar
- `Core\Auth\PersonalAccessToken` (Kullanır)
- `Illuminate\Database\Eloquent\Relations\MorphMany` (Kullanır)

## Kaynak Referansları
- `core/Auth/HasApiTokens.php:1-111`
