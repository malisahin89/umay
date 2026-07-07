# Dosya Raporu: core/Auth/EloquentUserProvider.php

## Amaç
`UserProvider` kontratının Eloquent tabanlı uygulaması.

## Genel Bakış
Framework için varsayılan kullanıcı sağlayıcıdır. Kullanıcıları Eloquent kullanarak veritabanından getirmeyi, şifreleri `password_verify` ile doğrulamayı ve "beni hatırla" token'larını yönetmeyi sağlar.

## Dosya Konumu
`core/Auth/EloquentUserProvider.php`

## Ad Alanı
`Core\Auth`

## Sınıflar
- `class EloquentUserProvider implements UserProvider`

## Metotlar
- `__construct(array $config)`: Sağlayıcıyı, model sınıfını içeren bir yapılandırma dizisi ile başlatır.
- `retrieveById(int|string $id): ?Authenticatable`: Kullanıcıyı birincil anahtarıyla bulur.
- `retrieveByCredentials(array $credentials): ?Authenticatable`: Kullanıcıyı e-posta adresiyle bulur.
- `validateCredentials(Authenticatable $user, array $credentials): bool`: Sağlanan şifreyi kullanıcının hashlenmiş şifresiyle doğrular.
- `retrieveByToken(int|string $id, string $token): ?Authenticatable`: `hash_equals` kullanarak bir "beni hatırla" token'ını doğrular.
- `updateRememberToken(Authenticatable $user, ?string $token): void`: Kullanıcının hatırlama token'ını veritabanında günceller.
- `model(): string`: Yapılandırılmış kullanıcı modelinin FQCN'ini döndürür.

## Bağımlılıklar
- `Core\Contracts\UserProvider` (Uygular)
- `Core\Contracts\Authenticatable` (Kullanır)

## Kaynak Referansları
- `core/Auth/EloquentUserProvider.php:1-99`
