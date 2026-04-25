<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finance extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'category',
        'description',
        'amount',
        'transaction_date',
        'source',
        'reference_id',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public const EXPENSE_CATEGORIES = [
        'Bahan Baku',
        'Listrik',
        'Air',
        'Gaji',
        'Transportasi',
        'Sewa',
        'Promosi',
        'Peralatan',
        'Lain-lain',
    ];

    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }

    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }
}
