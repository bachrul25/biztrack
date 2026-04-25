<?php

namespace App\Livewire\Seller;

use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ServiceBooking;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ViewReportsComponent extends Component
{
    use WithPagination;

    public string $dateFrom = '';
    public string $dateTo = '';

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $userId = Auth::id();

        $ordersQuery = OrderItem::with('order.buyer', 'product')
            ->where('seller_id', $userId);
        if ($this->dateFrom) $ordersQuery->whereDate('created_at', '>=', $this->dateFrom);
        if ($this->dateTo) $ordersQuery->whereDate('created_at', '<=', $this->dateTo);
        $orderItems = $ordersQuery->latest()->paginate(10, ['*'], 'ordersPage');

        $bookingsQuery = ServiceBooking::with('buyer', 'service')
            ->where('seller_id', $userId);
        if ($this->dateFrom) $bookingsQuery->whereDate('created_at', '>=', $this->dateFrom);
        if ($this->dateTo) $bookingsQuery->whereDate('created_at', '<=', $this->dateTo);
        $bookings = $bookingsQuery->latest()->get();

        $totalRevenue = Payment::where('status', 'success')
            ->where(function ($q) use ($userId) {
                $q->whereHas('order.items', fn($qi) => $qi->where('seller_id', $userId))
                  ->orWhereHas('serviceBooking', fn($qb) => $qb->where('seller_id', $userId));
            })
            ->when($this->dateFrom, fn($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn($q) => $q->whereDate('created_at', '<=', $this->dateTo))
            ->sum('amount');

        return view('livewire.seller.view-reports-component', [
            'orderItems' => $orderItems,
            'bookings' => $bookings,
            'totalRevenue' => $totalRevenue,
        ])->layout('layouts.app', ['title' => 'Seller Reports - PT BOBA']);
    }
}
