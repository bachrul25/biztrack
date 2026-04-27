<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'code',
        'description',
        'image',
        'cost_price',
        'selling_price',
        'stock',
        'minimum_stock',
        'unit',
        'status',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'stock' => 'integer',
        'minimum_stock' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->stock <= 0) {
            return 'habis';
        }
        if ($this->stock <= $this->minimum_stock) {
            return 'menipis';
        }

        return 'aman';
    }

    public function getStockBadgeAttribute(): string
    {
        return match ($this->stock_status) {
            'habis' => 'danger',
            'menipis' => 'warning',
            default => 'success',
        };
    }

    public static function generateCode(): string
    {
        $prefix = 'PRD-';
        $last = static::where('code', 'like', $prefix.'%')->orderByDesc('id')->first();
        $next = 1;
        if ($last) {
            $parts = explode('-', $last->code);
            $next = (int) end($parts) + 1;
        }

        return $prefix.str_pad((string) $next, 5, '0', STR_PAD_LEFT);
    }
}
