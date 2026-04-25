<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\CategoryRepository;

class CategoryService
{
    public function __construct(private readonly CategoryRepository $repo) {}

    public function create(array $data): Category
    {
        return $this->repo->create([
            'name' => trim($data['name']),
            'description' => $data['description'] ?? null,
        ]);
    }

    public function update(Category $category, array $data): Category
    {
        return $this->repo->update($category, [
            'name' => trim($data['name']),
            'description' => $data['description'] ?? null,
        ]);
    }

    public function delete(Category $category): bool
    {
        if ($category->products()->exists()) {
            throw new \DomainException('Kategori tidak dapat dihapus karena masih digunakan oleh produk.');
        }

        return $this->repo->delete($category);
    }
}
