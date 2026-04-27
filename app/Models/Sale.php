<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'user_id',
        'sale_date',
        'total_amount',
        'total_cost',
        'gross_profit',
        'payment_method',
        'paid_amount',
        'change_amount',
    ];

    protected $casts = [
        'sale_date' => 'date',
        'total_amount' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'gross_profit' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public static function generateInvoiceNumber(?\DateTimeInterface $date = null): string
    {
        $date = $date ? Carbon::instance($date) : now();
        $prefix = 'INV-'.$date->format('Ymd');
        $last = static::where('invoice_number', 'like', $prefix.'%')
            ->orderByDesc('id')
            ->first();

        $next = 1;
        if ($last) {
            $parts = explode('-', $last->invoice_number);
            $next = (int) end($parts) + 1;
        }

        return $prefix.'-'.str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }
}
