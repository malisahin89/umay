# Dosya Raporu: core/Auth/PersonalAccessToken.php

## Amaç
Hashlenmiş API erişim token'ları için model.

## Genel Bakış
Bir kullanıcıya verilen kişisel erişim token'ını temsil eder. Güvenliği sağlamak için token'lar, istemciye sağlanan düz metin token'ların SHA256 özetleri olarak saklanır.

## Dosya Konumu
`core/Auth/PersonalAccessToken.php`

## Ad Alanı
`Core\Auth`

## Sınıflar
- `class PersonalAccessToken extends Model`

## Özellikler
- `$table`: `personal_access_tokens`
- `$fillable`: `['name', 'token', 'abilities', 'last_used_at', 'expires_at']`
- `$casts`: `abilities`'i diziye, `last_used_at`/`expires_at`'i tarih zaman (datetime) türüne dönüştürür.

## Metotlar
- `tokenable(): MorphTo`: Token sahibine olan polimorfik ilişkiyi döndürür.
- `findToken(string $tokenString): ?self`: ID ile kaydı getirip hash'i doğrulayarak "{id}|{plaintext}" formatındaki token dizesini çözer.
- `can(string $ability): bool`: Token'ın gerekli yeteneğe veya joker karakterli `*` yetkisine sahip olup olmadığını kontrol eder.

## Bağımlılıklar
- `Core\Model` (Genişletir)
- `Illuminate\Database\Eloquent\Relations\MorphTo` (Kullanır)

## Kaynak Referansları
- `core/Auth/PersonalAccessToken.php:1-105`
