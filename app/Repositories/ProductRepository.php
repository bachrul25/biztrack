<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository
{
    public function paginate(?string $search = null, ?int $categoryId = null, int $perPage = 10): LengthAwarePaginator
    {
        return Product::query()
            ->with('category')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function allActive(): Collection
    {
        return Product::with('category')->where('status', 'active')->orderBy('name')->get();
    }

    public function searchActive(string $search, int $limit = 20): Collection
    {
        return Product::with('category')
            ->where('status', 'active')
            ->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->limit($limit)
            ->get();
    }

    public function lowStock(int $limit = 10): Collection
    {
        return Product::with('category')
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->where('status', 'active')
            ->orderBy('stock')
            ->limit($limit)
            ->get();
    }

    public function find(int $id): ?Product
    {
        return Product::with('category')->find($id);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);

        return $product->fresh();
    }

    public function delete(Product $product): bool
    {
        return (bool) $product->delete();
    }

    public function countActive(): int
    {
        return Product::where('status', 'active')->count();
    }

    public function totalStock(): int
    {
        return (int) Product::sum('stock');
    }
}
