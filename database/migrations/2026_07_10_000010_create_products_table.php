<?php

declare(strict_types=1);

use Core\Migration;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        DB::schema()->create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('order')->default(0);
            $table->string('cover_image', 500)->nullable();
            $table->json('gallery_images')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedTinyInteger('status')->default(0);

            $table->string('brand', 100)->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('model', 100)->nullable();
            $table->string('type', 100)->nullable();
            $table->string('fuel_type', 100)->nullable();
            $table->string('heating_type', 100)->nullable();
            $table->string('product_url', 500)->nullable();
            $table->timestamp('published_at')->nullable();

            $table->timestamps();

            // Optimized indexes
            $table->index(['status', 'published_at']);
            $table->index(['is_featured', 'status']);
            $table->index(['brand', 'status']);
            $table->index(['price', 'status']);
            $table->index('order');
            $table->index(['status', 'is_featured', 'published_at']);
        });
    }

    public function down(): void
    {
        DB::schema()->dropIfExists('products');
    }
};
