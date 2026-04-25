<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ManageProductsComponent extends Component
{
    use WithPagination;

    public string $search = '';
    public string $brandFilter = '';
    public string $genderFilter = '';
    public string $statusFilter = '';

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleStatus(int $id)
    {
        $product = Product::findOrFail($id);
        $product->update([
            'status' => $product->status === 'active' ? 'inactive' : 'active',
        ]);
        session()->flash('success', 'Status produk berhasil diubah.');
    }

    public function deleteProduct(int $id)
    {
        Product::findOrFail($id)->delete();
        session()->flash('success', 'Produk berhasil dihapus.');
    }

    public function render()
    {
        $products = Product::with('seller')
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->when($this->brandFilter, fn($q) => $q->where('brand', $this->brandFilter))
            ->when($this->genderFilter, fn($q) => $q->where('gender_category', $this->genderFilter))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.manage-products-component', [
            'products' => $products,
        ])->layout('layouts.app', ['title' => 'Manage Products - PT BOBA']);
    }
}
