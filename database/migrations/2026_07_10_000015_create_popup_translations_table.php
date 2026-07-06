<?php

declare(strict_types=1);

use Core\Migration;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        DB::schema()->create('popup_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('popup_id')->constrained('popups')->cascadeOnDelete();
            $table->string('language_slug', 10);
            $table->string('title')->nullable();
            $table->longText('content')->nullable();
            $table->string('image')->nullable();
            $table->string('button_text')->nullable();
            $table->string('button_url')->nullable();
            $table->timestamps();

            $table->foreign('language_slug')->references('slug')->on('languages')->cascadeOnDelete();
            $table->unique(['popup_id', 'language_slug']);
        });
    }

    public function down(): void
    {
        DB::schema()->dropIfExists('popup_translations');
    }
};
