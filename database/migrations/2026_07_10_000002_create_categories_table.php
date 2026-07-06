<?php

declare(strict_types=1);

use Core\Migration;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        DB::schema()->create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->unsignedTinyInteger('level')->default(0);
            $table->string('path', 500)->nullable(); // e.g. "/1/5/12"
            $table->string('color', 7)->nullable();
            $table->string('icon')->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->boolean('show_in_nav')->default(false);
            $table->unsignedTinyInteger('nav_order')->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['status', 'sort_order']);
            $table->index(['parent_id', 'status', 'sort_order']);
            $table->index(['level', 'status']);
            $table->index(['path']);
            $table->index(['parent_id', 'level']);
        });
    }

    public function down(): void
    {
        DB::schema()->dropIfExists('categories');
    }
};
