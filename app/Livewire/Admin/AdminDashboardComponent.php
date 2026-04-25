<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Service;
use App\Models\ServiceBooking;
use App\Models\User;
use App\Models\CompanyStructure;
use Livewire\Component;

class AdminDashboardComponent extends Component
{
    public function render()
    {
        return view('livewire.admin.admin-dashboard-component', [
            'totalSellers' => User::where('role', 'seller')->count(),
            'totalBuyers' => User::where('role', 'buyer')->count(),
            'totalProducts' => Product::count(),
            'totalServices' => Service::count(),
            'totalStructures' => CompanyStructure::where('status', 'active')->count(),
            'totalOrders' => Order::count(),
            'totalBookings' => ServiceBooking::count(),
            'totalPaymentsSuccess' => Payment::where('status', 'success')->count(),
            'totalRevenue' => Payment::where('status', 'success')->sum('amount'),
        ])->layout('layouts.app', ['title' => 'Admin Dashboard - PT BOBA']);
    }
}
