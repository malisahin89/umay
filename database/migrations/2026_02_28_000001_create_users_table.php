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
        // would fail on the sqlite test database.
        // Taşınabilir şema (mysql + sqlite + pgsql) — Eloquent Schema builder ile;
        // sqlite test veritabanında patlayan MySQL'e özgü ham DDL yerine.
        DB::schema()->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        DB::schema()->dropIfExists('users');
    }
};
