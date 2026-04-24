<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductRepository
{
    public function paginate(?string $search = null, ?string $category = null, int $perPage = 10): LengthAwarePaginator
    {
        return Product::query()
            ->when($search, function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('name', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
                });
            })
            ->when($category, fn ($q) => $q->where('category', $category))
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function lowStock(int $threshold = 5, int $limit = 5)
    {
        return Product::query()
            ->where('stock', '<', $threshold)
            ->where('status', 'active')
            ->orderBy('stock')
            ->limit($limit)
            ->get();
    }

    public function categories(): array
    {
        return Product::query()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->toArray();
    }

    public function create(array $data, ?UploadedFile $image = null): Product
    {
        if ($image) {
            $data['image'] = $image->store('products', 'public');
        }
        return Product::create($data);
    }

    public function update(Product $product, array $data, ?UploadedFile $image = null): Product
    {
        if ($image) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $image->store('products', 'public');
        }
        $product->update($data);
        return $product;
    }

    public function delete(Product $product): void
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
    }

    public function adjustStock(Product $product, int $stock): Product
    {
        $product->update(['stock' => max(0, $stock)]);
        return $product;
    }

    public function stockPaginate(?string $search = null, ?string $filter = null, int $perPage = 10): LengthAwarePaginator
    {
        return Product::query()
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($filter === 'low', fn ($q) => $q->where('stock', '<', 5)->where('stock', '>', 0))
            ->when($filter === 'out', fn ($q) => $q->where('stock', '<=', 0))
            ->when($filter === 'safe', fn ($q) => $q->where('stock', '>=', 5))
            ->orderBy('name')
            ->paginate($perPage);
    }
}
