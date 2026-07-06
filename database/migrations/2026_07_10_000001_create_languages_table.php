<?php

declare(strict_types=1);

use Core\Migration;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        DB::schema()->create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 10)->unique();
            $table->string('flag', 10)->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->boolean('is_default')->default(false);

            // "Yalnızca bir varsayılan dil" kuralını DB düzeyinde zorlar:
            // is_default=1 → 1, değilse NULL. NULL'lar unique index'te tekrarlanabildiğinden
            // yalnızca tek bir satır is_default=1 olabilir.
            $table->tinyInteger('is_default_flag')
                ->nullable()
                ->virtualAs('CASE WHEN is_default = 1 THEN 1 ELSE NULL END');

            $table->timestamps();

            $table->index(['status', 'is_default']);
            $table->unique('is_default_flag', 'languages_single_default_unique');
        });
    }

    public function down(): void
    {
        DB::schema()->dropIfExists('languages');
    }
};
