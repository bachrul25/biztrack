<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use App\Models\Payment;
use App\Models\ServiceBooking;
use Livewire\Component;
use Livewire\WithPagination;

class ViewReportsComponent extends Component
{
    use WithPagination;

    public string $dateFrom = '';
    public string $dateTo = '';
    public string $reportType = 'orders';

    protected $paginationTheme = 'bootstrap';

    public function updatingReportType()
    {
        $this->resetPage();
    }

    public function render()
    {
        $orders = collect();
        $bookings = collect();
        $payments = collect();
        $totalRevenue = 0;

        if ($this->reportType === 'orders') {
            $query = Order::with('buyer', 'items.product');
            if ($this->dateFrom) $query->whereDate('created_at', '>=', $this->dateFrom);
            if ($this->dateTo) $query->whereDate('created_at', '<=', $this->dateTo);
            $orders = $query->latest()->paginate(10);
        }

        if ($this->reportType === 'bookings') {
            $query = ServiceBooking::with('buyer', 'seller', 'service');
            if ($this->dateFrom) $query->whereDate('created_at', '>=', $this->dateFrom);
            if ($this->dateTo) $query->whereDate('created_at', '<=', $this->dateTo);
            $bookings = $query->latest()->paginate(10);
        }

        if ($this->reportType === 'payments') {
            $query = Payment::with('user', 'order', 'serviceBooking');
            if ($this->dateFrom) $query->whereDate('created_at', '>=', $this->dateFrom);
            if ($this->dateTo) $query->whereDate('created_at', '<=', $this->dateTo);
            $payments = $query->latest()->paginate(10);
        }

        $revenueQuery = Payment::where('status', 'success');
        if ($this->dateFrom) $revenueQuery->whereDate('created_at', '>=', $this->dateFrom);
        if ($this->dateTo) $revenueQuery->whereDate('created_at', '<=', $this->dateTo);
        $totalRevenue = $revenueQuery->sum('amount');

        return view('livewire.admin.view-reports-component', [
            'orders' => $orders,
            'bookings' => $bookings,
            'payments' => $payments,
            'totalRevenue' => $totalRevenue,
        ])->layout('layouts.app', ['title' => 'View Reports - PT BOBA']);
    }
}
