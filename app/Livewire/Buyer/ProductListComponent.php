<?php

namespace App\Livewire\Buyer;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ProductListComponent extends Component
{
    use WithPagination;

    public string $search = '';
    public string $brandFilter = '';
    public string $genderFilter = '';
    public string $categoryFilter = '';

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function addToCart(int $productId)
    {
        $product = Product::findOrFail($productId);

        $cart = Cart::where('buyer_id', Auth::id())
            ->where('product_id', $productId)
            ->first();

        if ($cart) {
            $cart->update([
                'quantity' => $cart->quantity + 1,
                'subtotal' => ($cart->quantity + 1) * $product->price,
            ]);
        } else {
            Cart::create([
                'buyer_id' => Auth::id(),
                'product_id' => $productId,
                'quantity' => 1,
                'subtotal' => $product->price,
            ]);
        }

        session()->flash('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function render()
    {
        $products = Product::where('status', 'active')
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->when($this->brandFilter, fn($q) => $q->where('brand', $this->brandFilter))
            ->when($this->genderFilter, fn($q) => $q->where('gender_category', $this->genderFilter))
            ->when($this->categoryFilter, fn($q) => $q->where('category', $this->categoryFilter))
            ->latest()
            ->paginate(12);

        $categories = Product::where('status', 'active')->distinct()->pluck('category')->filter();

        return view('livewire.buyer.product-list-component', [
            'products' => $products,
            'categories' => $categories,
        ])->layout('layouts.app', ['title' => 'Products - PT BOBA']);
    }
}
