<?php

namespace App\Livewire\Buyer;

use App\Models\ServiceBooking;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ServiceTrackingComponent extends Component
{
    public ServiceBooking $booking;

    public function mount(int $id)
    {
        $this->booking = ServiceBooking::with('service', 'seller')
            ->where('buyer_id', Auth::id())
            ->findOrFail($id);
    }

    public function markComplete()
    {
        $this->booking->update(['booking_status' => 'completed']);
        session()->flash('success', 'Layanan telah selesai!');
    }

    public function render()
    {
        $this->booking->refresh();

        return view('livewire.buyer.service-tracking-component')
            ->layout('layouts.app', ['title' => 'Service Tracking - PT BOBA']);
    }
}
