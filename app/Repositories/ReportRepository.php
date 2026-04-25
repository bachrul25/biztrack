<?php

namespace App\Repositories;

use App\Models\Finance;
use App\Models\Product;
use App\Models\Sale;
use App\Models\StockMovement;
use Illuminate\Database\Eloquent\Collection;

class ReportRepository
{
    public function salesBetween(string $dateFrom, string $dateTo): Collection
    {
        return Sale::with(['user', 'items.product'])
            ->whereDate('sale_date', '>=', $dateFrom)
            ->whereDate('sale_date', '<=', $dateTo)
            ->orderBy('sale_date')
            ->orderBy('id')
            ->get();
    }

    public function financesBetween(string $dateFrom, string $dateTo, ?string $type = null): Collection
    {
        return Finance::query()
            ->whereDate('transaction_date', '>=', $dateFrom)
            ->whereDate('transaction_date', '<=', $dateTo)
            ->when($type, fn ($q) => $q->where('type', $type))
            ->orderBy('transaction_date')
            ->orderBy('id')
            ->get();
    }

    public function productsSnapshot(): Collection
    {
        return Product::with('category')->orderBy('name')->get();
    }

    public function lowStockProducts(): Collection
    {
        return Product::with('category')
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->orderBy('stock')
            ->get();
    }

    public function stockMovementsBetween(string $dateFrom, string $dateTo): Collection
    {
        return StockMovement::with('product')
            ->whereDate('movement_date', '>=', $dateFrom)
            ->whereDate('movement_date', '<=', $dateTo)
            ->orderBy('movement_date')
            ->orderBy('id')
            ->get();
    }
}
