<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('artisan');
            $table->string('village');
            $table->string('district');
            $table->unsignedInteger('price');
            $table->string('unit');
            $table->decimal('rating', 2, 1)->default(0);
            $table->unsignedInteger('reviews')->default(0);
            $table->text('description');
            $table->string('badge')->nullable();
            $table->json('features');
            $table->string('image')->nullable();
            $table->string('image_position', 30)->default('center');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
