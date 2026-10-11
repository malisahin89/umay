<?php

declare(strict_types=1);

use Core\Migration;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;

/**
 * Backing store for the api-auth (Bearer token) guard. Tokens are stored hashed
 * (sha256); the plaintext is shown to the client exactly once at creation time.
 *
 * api-auth (Bearer token) guard'ının veri deposu. Token'lar hash'li (sha256)
 * saklanır; düz metin, istemciye yalnızca oluşturma anında bir kez gösterilir.
 */
return new class extends Migration
{
    public function up(): void
    {
        if ($this->tableExists('personal_access_tokens')) {
            return;
        }

        DB::schema()->create('personal_access_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('tokenable');          // tokenable_type + tokenable_id (+ index)
            $table->string('name');
            $table->string('token', 64)->unique(); // sha256 hex digest
            $table->text('abilities')->nullable(); // JSON array of granted abilities
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable(); // null = never expires // null = süresiz
            $table->timestamps();
        });
    }

    public function down(): void
    {
        DB::schema()->dropIfExists('personal_access_tokens');
    }
};
