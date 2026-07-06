<?php

declare(strict_types=1);

use Core\Migration;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        DB::schema()->create('post_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->string('language_slug', 10);
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->text('short_description')->nullable();
            $table->longText('content')->nullable();
            $table->string('seo_title', 255)->nullable();
            $table->string('seo_description', 500)->nullable();
            $table->string('seo_keywords', 500)->nullable();
            $table->timestamps();

            $table->unique(['post_id', 'language_slug'], 'post_translations_post_lang_unique');
            $table->unique(['slug', 'language_slug'], 'post_translations_slug_lang_unique');
            $table->index(['language_slug', 'slug']);
            $table->index('post_id');
            $table->index(['language_slug', 'post_id']);
            $table->foreign('language_slug')->references('slug')->on('languages')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        DB::schema()->dropIfExists('post_translations');
    }
};
