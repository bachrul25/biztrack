<?php

namespace App\Livewire\Buyer;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CheckoutComponent extends Component
{
    public string $shipping_address = '';
    public string $payment_method = 'transfer_bank';
    public bool $paymentFailed = false;

    public function checkout()
    {
        $this->validate([
            'shipping_address' => 'required',
            'payment_method' => 'required|in:transfer_bank,e_wallet,cod',
        ]);

        $carts = Cart::with('product')
            ->where('buyer_id', Auth::id())
            ->get();

        if ($carts->isEmpty()) {
            session()->flash('error', 'Keranjang kosong!');
            return;
        }

        $totalPrice = $carts->sum('subtotal');

        // Simulate payment (80% success rate)
        $paymentSuccess = rand(1, 10) <= 8;

        if (!$paymentSuccess) {
            $this->paymentFailed = true;
            session()->flash('error', 'Pembayaran gagal! Silakan coba lagi.');
            return;
        }

        DB::transaction(function () use ($carts, $totalPrice) {
            $order = Order::create([
                'buyer_id' => Auth::id(),
                'total_price' => $totalPrice,
                'shipping_address' => $this->shipping_address,
                'payment_method' => $this->payment_method,
                'payment_status' => 'success',
                'order_status' => 'processing',
            ]);

            foreach ($carts as $cart) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'seller_id' => $cart->product->seller_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->product->price,
                    'subtotal' => $cart->subtotal,
                ]);

                $cart->product->decrement('stock', $cart->quantity);
            }

            Payment::create([
                'user_id' => Auth::id(),
                'order_id' => $order->id,
                'payment_method' => $this->payment_method,
                'amount' => $totalPrice,
                'status' => 'success',
            ]);

            Cart::where('buyer_id', Auth::id())->delete();
        });

        $this->paymentFailed = false;
        session()->flash('success', 'Pembayaran berhasil! Pesanan Anda sedang diproses.');
        return redirect('/buyer/dashboard');
    }

    public function render()
    {
        $carts = Cart::with('product')
            ->where('buyer_id', Auth::id())
            ->get();

        return view('livewire.buyer.checkout-component', [
            'carts' => $carts,
            'total' => $carts->sum('subtotal'),
        ])->layout('layouts.app', ['title' => 'Checkout - PT BOBA']);
    }
}
