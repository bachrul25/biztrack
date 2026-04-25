<?php

namespace App\Livewire\Buyer;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProductDetailComponent extends Component
{
    public Product $product;

    public function mount(int $id)
    {
        $this->product = Product::with('seller')->findOrFail($id);
    }

    public function addToCart()
    {
        $cart = Cart::where('buyer_id', Auth::id())
            ->where('product_id', $this->product->id)
            ->first();

        if ($cart) {
            $cart->update([
                'quantity' => $cart->quantity + 1,
                'subtotal' => ($cart->quantity + 1) * $this->product->price,
            ]);
        } else {
            Cart::create([
                'buyer_id' => Auth::id(),
                'product_id' => $this->product->id,
                'quantity' => 1,
                'subtotal' => $this->product->price,
            ]);
        }

        session()->flash('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function render()
    {
        return view('livewire.buyer.product-detail-component')
            ->layout('layouts.app', ['title' => $this->product->name . ' - PT BOBA']);
    }
}
