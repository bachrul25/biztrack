<?php

namespace App\Livewire\Seller;

use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ServiceBooking;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SellerDashboardComponent extends Component
{
    public function render()
    {
        $user = Auth::user();
        $profile = $user->sellerProfile;

        return view('livewire.seller.seller-dashboard-component', [
            'profile' => $profile,
            'totalProducts' => $user->products()->count(),
            'totalServices' => $user->services()->count(),
            'totalOrders' => OrderItem::where('seller_id', $user->id)->distinct('order_id')->count('order_id'),
            'totalBookings' => ServiceBooking::where('seller_id', $user->id)->count(),
            'totalRevenue' => Payment::where('status', 'success')
                ->where(function ($q) use ($user) {
                    $q->whereHas('order.items', fn($qi) => $qi->where('seller_id', $user->id))
                      ->orWhereHas('serviceBooking', fn($qb) => $qb->where('seller_id', $user->id));
                })->sum('amount'),
        ])->layout('layouts.app', ['title' => 'Seller Dashboard - PT BOBA']);
    }
}
