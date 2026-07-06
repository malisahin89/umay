<?php

declare(strict_types=1);

namespace Database\Seeders;

use Core\Seeder;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        // Idempotent — skip if users already exist.
        // Idempotent — kullanıcı zaten varsa atla.
        if ($this->count('users') > 0) {
            return;
        }

        // First user: Admin / İlk kullanıcı: Admin
        $this->insert('users', [
            'name' => 'Admin',
            'surname' => 'User',
            'username' => 'admin',
            'email' => 'admin@umay.test',
            'password' => password_hash('123456789', PASSWORD_DEFAULT),
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Second user: Member / İkinci kullanıcı: Member
        $this->insert('users', [
            'name' => 'Test',
            'surname' => 'Kullanıcı',
            'username' => 'testuser',
            'email' => 'test@umay.test',
            'password' => password_hash('123456789', PASSWORD_DEFAULT),
            'role' => 'member',
            'status' => 'active',
        ]);
    }
}
