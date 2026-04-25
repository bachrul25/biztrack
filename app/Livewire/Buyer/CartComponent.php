<?php

namespace App\Livewire\Buyer;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CartComponent extends Component
{
    public function updateQuantity(int $cartId, int $quantity)
    {
        if ($quantity < 1) return;

        $cart = Cart::findOrFail($cartId);
        $cart->update([
            'quantity' => $quantity,
            'subtotal' => $quantity * $cart->product->price,
        ]);
    }

    public function removeItem(int $cartId)
    {
        Cart::findOrFail($cartId)->delete();
        session()->flash('success', 'Item berhasil dihapus dari keranjang.');
    }

    public function render()
    {
        $carts = Cart::with('product')
            ->where('buyer_id', Auth::id())
            ->get();

        $total = $carts->sum('subtotal');

        return view('livewire.buyer.cart-component', [
            'carts' => $carts,
            'total' => $total,
        ])->layout('layouts.app', ['title' => 'Cart - PT BOBA']);
    }
}
