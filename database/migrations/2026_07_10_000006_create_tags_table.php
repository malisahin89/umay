<?php

declare(strict_types=1);

use Core\Migration;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        DB::schema()->create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('color', 7)->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->integer('usage_count')->default(0);
            $table->timestamps();

            $table->index(['status', 'usage_count']);
        });
    }

    public function down(): void
    {
        DB::schema()->dropIfExists('tags');
    }
};
