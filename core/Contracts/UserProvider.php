<?php

declare(strict_types=1);

namespace Core\Contracts;

/**
 * UserProvider — the "login brain" contract.
 * UserProvider — "login beyni" sözleşmesi.
 *
 * Defines how users are fetched from storage (DB, API, LDAP...) and how
 * credentials are validated. To write your OWN login logic, implement this
 * interface and reference it as a 'driver' in config/auth.php — without touching core.
 *
 * Kullanıcıları depodan (DB, API, LDAP...) çekmenin ve kimlik bilgilerini
 * doğrulamanın yolunu tanımlar. KENDİ login mantığınızı yazmak için bu arayüzü
 * implemente edip config/auth.php'de 'driver' olarak gösterin — çekirdeğe dokunmadan.
 */
interface UserProvider
{
    /**
     * Retrieve a user by their unique identifier.
     * ID ile kullanıcı getir.
     */
    public function retrieveById(int|string $id): ?Authenticatable;

    /**
     * Retrieve a user by credentials (e.g. email) — does NOT check the password.
     * Kimlik bilgileriyle (örn. email) kullanıcı getir — şifre KONTROL ETMEZ.
     *
     * @param  array<string, mixed>  $credentials
     */
    public function retrieveByCredentials(array $credentials): ?Authenticatable;

    /**
     * Validate the user's password against the given credentials.
     * Kullanıcının şifresini verilen kimlik bilgilerine göre doğrula.
     *
     * @param  array<string, mixed>  $credentials
     */
    public function validateCredentials(Authenticatable $user, array $credentials): bool;

    /**
     * Retrieve a user by the "remember me" cookie token (timing-safe).
     * "Beni hatırla" cookie'sindeki token ile kullanıcı getir (timing-safe).
     */
    public function retrieveByToken(int|string $id, string $token): ?Authenticatable;

    /**
     * Update/clear the user's "remember me" token and persist it.
     * Kullanıcının "beni hatırla" token'ını güncelle/temizle ve kalıcı kaydet.
     */
    public function updateRememberToken(Authenticatable $user, ?string $token): void;
}
