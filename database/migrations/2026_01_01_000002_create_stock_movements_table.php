<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['in', 'out']);
            $table->integer('quantity');
            $table->string('description')->nullable();
            $table->date('movement_date');
            $table->timestamps();

            $table->index(['product_id', 'movement_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
