<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prediction_results', function (Blueprint $table) {
            $table->id();
            $table->string('method');
            $table->string('period_type');
            $table->date('prediction_date');
            $table->decimal('actual_value', 14, 4)->nullable();
            $table->decimal('predicted_value', 14, 4);
            $table->decimal('error_value', 14, 4)->nullable();
            $table->decimal('mape', 10, 4)->nullable();
            $table->decimal('mad', 14, 4)->nullable();
            $table->decimal('mse', 16, 4)->nullable();
            $table->decimal('rmse', 14, 4)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['method', 'period_type']);
            $table->index('prediction_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prediction_results');
    }
};
