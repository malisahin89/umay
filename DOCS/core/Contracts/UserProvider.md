# Dosya Raporu: core/Contracts/UserProvider.php

## Amaç
Kullanıcı getirimi ve kimlik doğrulama mantığı için arayüz.

## Genel Bakış
"Kullanıcı Sağlayıcıları" için kontratı tanımlar. Kimlik doğrulama guard'ını veri kaynağından ayırarak, çekirdek kimlik doğrulama mantığını değiştirmeden kullanıcıların bir veritabanından, harici bir API'den veya LDAP'dan getirilmesine olanak tanır.

## Dosya Konumu
`core/Contracts/UserProvider.php`

## Ad Alanı
`Core\Contracts`

## Arayüzler
- `interface UserProvider`

## Metotlar
- `retrieveById(int|string $id): ?Authenticatable`: Kullanıcıyı ID'si ile getirir.
- `retrieveByCredentials(array $credentials): ?Authenticatable`: Kimlik bilgilerine (örneğin e-posta) göre kullanıcıyı getirir.
- `validateCredentials(Authenticatable $user, array $credentials): bool`: Kullanıcının şifresini doğrular.
- `retrieveByToken(int|string $id, string $token): ?Authenticatable`: Hatırlama token'ı aracılığıyla kullanıcıyı getirir.
- `updateRememberToken(Authenticatable $user, ?string $token): void`: Hatırlama token'ını kaydeder.

## Bağımlılıklar
- `Core\Contracts\Authenticatable` (Kullanır)

## Kaynak Referansları
- `core/Contracts/UserProvider.php:1-54`
