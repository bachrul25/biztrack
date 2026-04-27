<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class StockRepository
{
    public function movements(?int $productId = null, ?string $type = null, ?string $dateFrom = null, ?string $dateTo = null, int $perPage = 15): LengthAwarePaginator
    {
        return StockMovement::query()
            ->with('product')
            ->when($productId, fn ($q) => $q->where('product_id', $productId))
            ->when($type, fn ($q) => $q->where('type', $type))
            ->when($dateFrom, fn ($q) => $q->whereDate('movement_date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('movement_date', '<=', $dateTo))
            ->orderByDesc('movement_date')
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function recordIn(Product $product, int $quantity, ?string $description = null, ?string $date = null): StockMovement
    {
        $movement = StockMovement::create([
            'product_id' => $product->id,
            'type' => 'in',
            'quantity' => $quantity,
            'description' => $description,
            'movement_date' => $date ?? now()->toDateString(),
        ]);
        $product->increment('stock', $quantity);

        return $movement;
    }

    public function recordOut(Product $product, int $quantity, ?string $description = null, ?string $date = null): StockMovement
    {
        $movement = StockMovement::create([
            'product_id' => $product->id,
            'type' => 'out',
            'quantity' => $quantity,
            'description' => $description,
            'movement_date' => $date ?? now()->toDateString(),
        ]);
        $product->decrement('stock', $quantity);

        return $movement;
    }

    public function totalIn(?string $dateFrom = null, ?string $dateTo = null): int
    {
        return (int) StockMovement::where('type', 'in')
            ->when($dateFrom, fn ($q) => $q->whereDate('movement_date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('movement_date', '<=', $dateTo))
            ->sum('quantity');
    }

    public function totalOut(?string $dateFrom = null, ?string $dateTo = null): int
    {
        return (int) StockMovement::where('type', 'out')
            ->when($dateFrom, fn ($q) => $q->whereDate('movement_date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('movement_date', '<=', $dateTo))
            ->sum('quantity');
    }
}
