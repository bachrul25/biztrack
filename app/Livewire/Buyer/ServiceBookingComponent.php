<?php

namespace App\Livewire\Buyer;

use App\Models\Payment;
use App\Models\Service;
use App\Models\ServiceBooking;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ServiceBookingComponent extends Component
{
    public Service $service;
    public string $requirement = '';
    public string $payment_method = 'transfer_bank';
    public bool $paymentFailed = false;

    public function mount(int $id)
    {
        $this->service = Service::with('seller')->findOrFail($id);
    }

    public function book()
    {
        $this->validate([
            'requirement' => 'required',
            'payment_method' => 'required|in:transfer_bank,e_wallet,cod',
        ]);

        $paymentSuccess = rand(1, 10) <= 8;

        if (!$paymentSuccess) {
            $this->paymentFailed = true;
            session()->flash('error', 'Pembayaran gagal! Silakan coba lagi.');
            return;
        }

        $booking = ServiceBooking::create([
            'buyer_id' => Auth::id(),
            'seller_id' => $this->service->seller_id,
            'service_id' => $this->service->id,
            'requirement' => $this->requirement,
            'payment_method' => $this->payment_method,
            'payment_status' => 'success',
            'booking_status' => 'processing',
        ]);

        Payment::create([
            'user_id' => Auth::id(),
            'service_booking_id' => $booking->id,
            'payment_method' => $this->payment_method,
            'amount' => $this->service->price,
            'status' => 'success',
        ]);

        $this->paymentFailed = false;
        session()->flash('success', 'Booking berhasil! Layanan sedang diproses.');
        return redirect('/buyer/service-tracking/' . $booking->id);
    }

    public function render()
    {
        return view('livewire.buyer.service-booking-component')
            ->layout('layouts.app', ['title' => 'Booking Service - PT BOBA']);
    }
}
