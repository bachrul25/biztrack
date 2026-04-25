<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use App\Repositories\StockRepository;

class StockService
{
    public function __construct(private readonly StockRepository $repo) {}

    public function recordIn(Product $product, int $quantity, ?string $description = null, ?string $date = null): StockMovement
    {
        if ($quantity <= 0) {
            throw new \DomainException('Jumlah stok masuk harus lebih dari 0.');
        }

        return $this->repo->recordIn($product, $quantity, $description ?? 'Stok masuk manual', $date);
    }

    public function recordOut(Product $product, int $quantity, ?string $description = null, ?string $date = null): StockMovement
    {
        if ($quantity <= 0) {
            throw new \DomainException('Jumlah stok keluar harus lebih dari 0.');
        }
        $this->assertEnoughStock($product, $quantity);

        return $this->repo->recordOut($product, $quantity, $description ?? 'Stok keluar manual', $date);
    }

    public function assertEnoughStock(Product $product, int $quantity): void
    {
        if ($product->stock < $quantity) {
            throw new \DomainException("Stok produk {$product->name} tidak cukup (tersedia {$product->stock}, diminta {$quantity}).");
        }
    }

    public function hasEnoughStock(Product $product, int $quantity): bool
    {
        return $product->stock >= $quantity;
    }
}
