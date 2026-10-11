<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

/**
 * User modeli parola mutator testleri.
 * User model password mutator tests.
 *
 * empty() tuzağı: empty('0') true olduğundan "0" parolası mutator'ı sessizce
 * atlıyordu — yeni kayıtta kolon set edilmez, güncellemede eski parola kalırdı.
 * The empty() trap: empty('0') is true, so a password of "0" silently skipped the
 * mutator — never set on insert, and the old password survived updates.
 */
class UserModelTest extends TestCase
{
    public function test_password_mutator_hashes_value(): void
    {
        $user = new User;
        $user->password = 'gizli-sifre';

        $this->assertNotSame('gizli-sifre', $user->getAuthPassword());
        $this->assertTrue(password_verify('gizli-sifre', $user->getAuthPassword()));
    }

    public function test_password_mutator_accepts_literal_zero(): void
    {
        $user = new User;
        $user->password = '0';

        $this->assertTrue(password_verify('0', $user->getAuthPassword()));
    }

    public function test_password_mutator_replaces_old_hash_on_update(): void
    {
        $user = new User;
        $user->password = 'eski-sifre';
        $user->password = '0';

        // Eski parola artık DOĞRULANMAMALI — mutator atlasaydı eski hash kalırdı.
        // The old password must NOT verify any more — a skipped mutator kept the old hash.
        $this->assertFalse(password_verify('eski-sifre', $user->getAuthPassword()));
        $this->assertTrue(password_verify('0', $user->getAuthPassword()));
    }

    public function test_password_mutator_ignores_empty_string(): void
    {
        $user = new User;
        $user->password = '';

        $this->assertSame('', $user->getAuthPassword());
    }
}
