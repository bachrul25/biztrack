<?php

namespace App\Livewire;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Stok')]
class StockComponent extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'status')]
    public string $filter = '';

    public bool $showModal = false;
    public ?int $productId = null;
    public string $productName = '';
    public string $stock = '';

    protected function rules(): array
    {
        return [
            'stock' => ['required', 'integer', 'min:0'],
        ];
    }

    protected array $messages = [
        'stock.required' => 'Stok wajib diisi.',
        'stock.integer' => 'Stok harus angka.',
        'stock.min' => 'Stok tidak boleh minus.',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilter(): void
    {
        $this->resetPage();
    }

    public function edit(int $id): void
    {
        $product = Product::findOrFail($id);
        $this->productId = $product->id;
        $this->productName = $product->name;
        $this->stock = (string) $product->stock;
        $this->showModal = true;
    }

    public function update(ProductRepository $products): void
    {
        $this->validate();
        $product = Product::findOrFail($this->productId);
        $products->adjustStock($product, (int) $this->stock);
        $this->dispatch('swal', icon: 'success', title: 'Stok diperbarui');
        $this->showModal = false;
        $this->reset(['productId', 'productName', 'stock']);
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['productId', 'productName', 'stock']);
    }

    public function render(ProductRepository $products): View
    {
        return view('livewire.stock-component', [
            'products' => $products->stockPaginate($this->search, $this->filter ?: null, 10),
        ]);
    }
}
