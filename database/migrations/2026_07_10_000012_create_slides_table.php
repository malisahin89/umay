<?php

declare(strict_types=1);

use Core\Migration;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        DB::schema()->create('slides', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('image');
            $table->string('media_file')->nullable();
            $table->string('text_position')->default('left');
            $table->unsignedSmallInteger('label_size')->default(12);
            $table->unsignedSmallInteger('title_size')->default(64);
            $table->unsignedSmallInteger('subtitle_size')->default(14);
            $table->unsignedInteger('order')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        DB::schema()->dropIfExists('slides');
    }
};
