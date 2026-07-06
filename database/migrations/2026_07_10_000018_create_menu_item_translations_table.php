<?php

declare(strict_types=1);

use Core\Migration;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        DB::schema()->create('menu_item_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_item_id')->constrained('menu_items')->cascadeOnDelete();
            $table->string('language_slug', 10);
            $table->string('label');
            $table->timestamps();

            $table->unique(['menu_item_id', 'language_slug']);
            $table->index('language_slug');
            $table->foreign('language_slug')->references('slug')->on('languages')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        DB::schema()->dropIfExists('menu_item_translations');
    }
};
