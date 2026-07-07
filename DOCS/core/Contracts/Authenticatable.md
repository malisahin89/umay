# Dosya Raporu: core/Contracts/Authenticatable.php

## Amaç
Kimlik doğrulanabilir varlıklar için arayüz.

## Genel Bakış
Bir modelin kimlik doğrulama sistemi tarafından işlenebilmesi için gereken minimum gereksinimleri tanımlar. Kimlik doğrulama guard'ının, modelin somut uygulamasını bilmeden ID'yi, şifreyi ve hatırlama token'ını alabilmesini sağlar.

## Dosya Konumu
`core/Contracts/Authenticatable.php`

## Ad Alanı
`Core\Contracts`

## Arayüzler
- `interface Authenticatable`

## Metotlar
- `getAuthIdentifier(): int|string`: Kullanıcı için benzersiz tanımlayıcıyı döndürür.
- `getAuthPassword(): string`: Hashlenmiş şifreyi döndürür.
- `getRememberToken(): ?string`: Mevcut hatırlama token'ını döndürür.
- `setRememberToken(?string $token): void`: Hatırlama token'ını günceller.

## Kaynak Referansları
- `core/Contracts/Authenticatable.php:1-42`
