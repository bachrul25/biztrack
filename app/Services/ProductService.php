<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function __construct(private readonly ProductRepository $repo) {}

    public function create(array $data, ?UploadedFile $image = null): Product
    {
        $data = $this->normalize($data);
        if (empty($data['code'])) {
            $data['code'] = Product::generateCode();
        }
        if ($image) {
            $data['image'] = $image->store('products', 'public');
        }

        return $this->repo->create($data);
    }

    public function update(Product $product, array $data, ?UploadedFile $image = null): Product
    {
        $data = $this->normalize($data);
        if ($image) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $image->store('products', 'public');
        }

        return $this->repo->update($product, $data);
    }

    public function delete(Product $product): bool
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        return $this->repo->delete($product);
    }

    private function normalize(array $data): array
    {
        return [
            'category_id' => $data['category_id'] ?? null,
            'name' => trim($data['name']),
            'code' => isset($data['code']) ? trim($data['code']) : null,
            'description' => $data['description'] ?? null,
            'cost_price' => (float) ($data['cost_price'] ?? 0),
            'selling_price' => (float) ($data['selling_price'] ?? 0),
            'stock' => (int) ($data['stock'] ?? 0),
            'minimum_stock' => (int) ($data['minimum_stock'] ?? 5),
            'unit' => $data['unit'] ?? 'pcs',
            'status' => $data['status'] ?? 'active',
        ];
    }
}
