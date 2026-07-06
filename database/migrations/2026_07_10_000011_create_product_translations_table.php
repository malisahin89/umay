<?php

declare(strict_types=1);

use Core\Migration;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        DB::schema()->create('product_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('language_slug', 10);
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->text('short_description')->nullable();
            $table->json('specifications')->nullable();
            $table->longText('content')->nullable();
            $table->longText('features')->nullable();
            $table->longText('attributes')->nullable();
            $table->longText('documents')->nullable();
            $table->string('seo_title', 255)->nullable();
            $table->string('seo_description', 500)->nullable();
            $table->string('seo_keywords', 500)->nullable();
            $table->timestamps();

            $table->unique(['product_id', 'language_slug']);
            $table->unique(['slug', 'language_slug']);
            $table->index(['language_slug', 'slug']);
            $table->index('product_id');
            $table->index(['language_slug', 'title']);
            $table->foreign('language_slug')->references('slug')->on('languages')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        DB::schema()->dropIfExists('product_translations');
    }
};
