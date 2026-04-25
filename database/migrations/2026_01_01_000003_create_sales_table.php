<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('sale_date');
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->decimal('total_cost', 14, 2)->default(0);
            $table->decimal('gross_profit', 14, 2)->default(0);
            $table->enum('payment_method', ['cash', 'transfer', 'qris'])->default('cash');
            $table->decimal('paid_amount', 14, 2)->default(0);
            $table->decimal('change_amount', 14, 2)->default(0);
            $table->timestamps();

            $table->index('sale_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
