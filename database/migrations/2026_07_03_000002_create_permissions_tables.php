<?php

declare(strict_types=1);

use Core\Migration;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        // Portable schema via the Eloquent Schema builder (mysql + sqlite + pgsql).
        // Taşınabilir şema — Eloquent Schema builder ile (mysql + sqlite + pgsql).
        if (! $this->tableExists('permissions')) {
            DB::schema()->create('permissions', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 100)->unique();  // e.g. posts.publish, users.manage
                $table->string('label');                 // human description // açıklama
                $table->timestamp('created_at')->nullable();
            });
        }

        if (! $this->tableExists('role_permissions')) {
            DB::schema()->create('role_permissions', function (Blueprint $table) {
                $table->increments('id');
                $table->string('role', 20);
                $table->integer('permission_id')->unsigned();
                $table->unique(['role', 'permission_id']);
                $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            });
        }

        // Seed the default permission catalogue (idempotent).
        // Varsayılan izin kataloğunu ekle (idempotent).
        $defaults = [
            ['name' => 'posts.view',    'label' => 'View posts // Postları görüntüle'],
            ['name' => 'posts.create',  'label' => 'Create post // Post oluştur'],
            ['name' => 'posts.edit',    'label' => 'Edit post // Post düzenle'],
            ['name' => 'posts.delete',  'label' => 'Delete post // Post sil'],
            ['name' => 'posts.publish', 'label' => 'Publish post // Post yayınla'],
            ['name' => 'users.view',    'label' => 'View users // Kullanıcıları görüntüle'],
            ['name' => 'users.create',  'label' => 'Create user // Kullanıcı oluştur'],
            ['name' => 'users.edit',    'label' => 'Edit user // Kullanıcı düzenle'],
            ['name' => 'users.delete',  'label' => 'Delete user // Kullanıcı sil'],
        ];

        foreach ($defaults as $perm) {
            $exists = DB::table('permissions')->where('name', $perm['name'])->exists();
            if (! $exists) {
                DB::table('permissions')->insert($perm);
            }
        }
    }

    public function down(): void
    {
        DB::schema()->dropIfExists('role_permissions');
        DB::schema()->dropIfExists('permissions');
    }
};
