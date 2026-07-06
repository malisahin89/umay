<?php

declare(strict_types=1);

use Core\Migration;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        DB::schema()->create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('template')->default('default');
            $table->unsignedTinyInteger('status')->default(1);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->index(['status', 'sort_order']);
            $table->index('template');
        });
    }

    public function down(): void
    {
        DB::schema()->dropIfExists('pages');
    }
};
