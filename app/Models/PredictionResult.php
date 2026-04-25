<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PredictionResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'method',
        'period_type',
        'prediction_date',
        'actual_value',
        'predicted_value',
        'error_value',
        'mape',
        'mad',
        'mse',
        'rmse',
        'description',
    ];

    protected $casts = [
        'prediction_date' => 'date',
        'actual_value' => 'decimal:4',
        'predicted_value' => 'decimal:4',
        'error_value' => 'decimal:4',
        'mape' => 'decimal:4',
        'mad' => 'decimal:4',
        'mse' => 'decimal:4',
        'rmse' => 'decimal:4',
    ];
}
