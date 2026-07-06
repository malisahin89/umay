<?php

declare(strict_types=1);

use Core\Migration;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        if ($this->tableExists('users')) {
            return;
        }

        // Portable schema (mysql + sqlite + pgsql) via the Eloquent Schema builder,
        // instead of MySQL-only raw DDL (INT AUTO_INCREMENT / ENGINE=InnoDB) that
        // would fail on the sqlite test database. role/status are plain strings
        // (not ENUM) so the same migration runs everywhere.
        // Taşınabilir şema (mysql + sqlite + pgsql) — Eloquent Schema builder ile;
        // sqlite test veritabanında patlayan MySQL'e özgü ham DDL yerine.
        // role/status ENUM değil düz string; aynı migration her yerde çalışır.
        DB::schema()->create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('surname', 100);
            $table->string('username', 100)->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('profile_image')->nullable();
            $table->text('bio')->nullable();
            $table->string('role', 20)->default('member');
            $table->string('status', 20)->default('active');
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        DB::schema()->dropIfExists('users');
    }
};
