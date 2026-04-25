<?php

namespace App\Livewire\Buyer;

use App\Models\Cart;
use App\Models\Order;
use App\Models\ServiceBooking;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BuyerDashboardComponent extends Component
{
    public function render()
    {
        $userId = Auth::id();

        return view('livewire.buyer.buyer-dashboard-component', [
            'cartCount' => Cart::where('buyer_id', $userId)->count(),
            'orderCount' => Order::where('buyer_id', $userId)->count(),
            'bookingCount' => ServiceBooking::where('buyer_id', $userId)->count(),
        ])->layout('layouts.app', ['title' => 'Buyer Dashboard - PT BOBA']);
    }
}
