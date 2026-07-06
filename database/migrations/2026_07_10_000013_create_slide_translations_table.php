<?php

declare(strict_types=1);

use Core\Migration;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        DB::schema()->create('slide_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('slide_id')->constrained('slides')->cascadeOnDelete();
            $table->string('language_slug', 10);
            $table->string('label', 100)->nullable();
            $table->string('title', 200)->nullable();
            $table->text('subtitle')->nullable();
            $table->string('button_text', 100)->nullable();
            $table->string('button_url', 500)->nullable();
            $table->timestamps();

            $table->unique(['slide_id', 'language_slug']);
            $table->index('language_slug');
            $table->foreign('language_slug')->references('slug')->on('languages')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        DB::schema()->dropIfExists('slide_translations');
    }
};
