<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('short_name', 60)->nullable();
            $table->string('category', 60);
            $table->string('region', 120);
            $table->string('short_desc', 300);
            $table->text('full_story');
            $table->string('prep_time', 40);
            $table->string('cook_time', 40);
            $table->string('servings', 60);
            $table->string('difficulty', 40);
            $table->json('main_ingredients');
            $table->json('spice_blend');
            $table->json('steps');
            $table->text('chef_tips');
            $table->text('cultural_note');
            $table->unsignedTinyInteger('spicy')->default(0);
            $table->unsignedTinyInteger('savory')->default(0);
            $table->unsignedTinyInteger('sweet')->default(0);
            $table->unsignedTinyInteger('aromatic')->default(0);
            $table->string('image')->nullable();
            $table->string('image_position', 30)->default('center');
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
