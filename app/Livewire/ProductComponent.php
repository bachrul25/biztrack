<?php

namespace App\Livewire;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Produk')]
class ProductComponent extends Component
{
    use WithPagination, WithFileUploads;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'kategori')]
    public string $category = '';

    public ?int $productId = null;
    public string $name = '';
    public string $price = '';
    public string $stock = '';
    public string $categoryForm = '';
    public string $status = 'active';
    public $image = null;
    public ?string $existingImage = null;

    public bool $showModal = false;
    public bool $editMode = false;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'categoryForm' => ['required', 'string', 'max:100'],
            'status' => ['required', 'in:active,inactive'],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }

    protected array $messages = [
        'name.required' => 'Nama produk wajib diisi.',
        'price.required' => 'Harga wajib diisi.',
        'price.numeric' => 'Harga harus berupa angka.',
        'stock.required' => 'Stok wajib diisi.',
        'categoryForm.required' => 'Kategori wajib diisi.',
        'status.required' => 'Status wajib dipilih.',
        'image.image' => 'File harus berupa gambar.',
        'image.max' => 'Ukuran gambar maksimal 2MB.',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategory(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $product = Product::findOrFail($id);
        $this->productId = $product->id;
        $this->name = $product->name;
        $this->price = (string) $product->price;
        $this->stock = (string) $product->stock;
        $this->categoryForm = $product->category;
        $this->status = $product->status;
        $this->existingImage = $product->image;
        $this->image = null;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function store(ProductRepository $products): void
    {
        $data = $this->validate();
        $payload = [
            'name' => $data['name'],
            'price' => $data['price'],
            'stock' => $data['stock'],
            'category' => $data['categoryForm'],
            'status' => $data['status'],
        ];

        if ($this->editMode && $this->productId) {
            $product = Product::findOrFail($this->productId);
            $products->update($product, $payload, $this->image ?: null);
            $this->dispatch('swal', icon: 'success', title: 'Produk diperbarui');
        } else {
            $products->create($payload, $this->image ?: null);
            $this->dispatch('swal', icon: 'success', title: 'Produk ditambahkan');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete(int $id, ProductRepository $products): void
    {
        $product = Product::findOrFail($id);
        $products->delete($product);
        $this->dispatch('swal', icon: 'success', title: 'Produk dihapus');
    }

    public function resetForm(): void
    {
        $this->reset(['productId', 'name', 'price', 'stock', 'categoryForm', 'status', 'image', 'existingImage', 'editMode']);
        $this->status = 'active';
        $this->resetErrorBag();
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function render(ProductRepository $products): View
    {
        return view('livewire.product-component', [
            'products' => $products->paginate($this->search, $this->category, 10),
            'categories' => $products->categories(),
        ]);
    }
}
