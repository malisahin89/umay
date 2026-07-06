<?php

declare(strict_types=1);

namespace Database\Seeders;

use Core\Seeder;

class LanguagesSeeder extends Seeder
{
    public function run(): void
    {
        // Idempotent — skip if languages already exist.
        // Idempotent — dil zaten varsa atla.
        if ($this->count('languages') > 0) {
            return;
        }

        // Default language first (is_default = 1 — DB enforces a single default).
        // Önce varsayılan dil (is_default = 1 — DB tek varsayılanı zorlar).
        $this->insert('languages', [
            'name' => 'Türkçe',
            'slug' => 'tr',
            'flag' => '🇹🇷',
            'status' => 1,
            'is_default' => 1,
        ]);

        $this->insert('languages', [
            'name' => 'English',
            'slug' => 'en',
            'flag' => '🇬🇧',
            'status' => 1,
            'is_default' => 0,
        ]);
    }
}
