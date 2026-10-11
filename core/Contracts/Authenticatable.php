<?php

declare(strict_types=1);

namespace Core\Contracts;

/**
 * Authenticatable — user model contract.
 * Authenticatable — kullanıcı modeli sözleşmesi.
 *
 * The core (Core\Auth) depends on this interface, NOT on a concrete
 * App\Models\User class. config/auth.php decides which class is used.
 *
 * Çekirdek (Core\Auth) somut bir App\Models\User sınıfına DEĞİL,
 * bu arayüze bağımlıdır. Hangi sınıfın kullanılacağını config/auth.php söyler.
 */
interface Authenticatable
{
    /**
     * The unique identifier (primary key) value.
     * Kimlik (primary key) değeri.
     */
    public function getAuthIdentifier(): int|string;

    /**
     * The hashed password.
     * Hash'lenmiş şifre.
     */
    public function getAuthPassword(): string;

    /**
     * The "remember me" token (null if none).
     * "Beni hatırla" token'ı (yoksa null).
     */
    public function getRememberToken(): ?string;

    /**
     * Set the "remember me" token.
     * "Beni hatırla" token'ını set et.
     */
    public function setRememberToken(?string $token): void;
}
