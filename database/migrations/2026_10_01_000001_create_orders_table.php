<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code', 24)->unique();
            $table->string('name');
            $table->string('phone', 32);
            $table->string('city');
            $table->text('address');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('total');
            $table->string('status', 24)->default('baru');
            $table->string('ip', 45)->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('product_id');
            $table->string('title');
            $table->string('unit')->nullable();
            $table->unsignedInteger('price');
            $table->unsignedSmallInteger('qty');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
