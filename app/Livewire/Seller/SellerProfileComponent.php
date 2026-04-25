<?php

namespace App\Livewire\Seller;

use App\Models\SellerProfile;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SellerProfileComponent extends Component
{
    public string $shop_name = '';
    public string $shop_description = '';
    public string $shop_address = '';

    public function mount()
    {
        $profile = Auth::user()->sellerProfile;
        if ($profile) {
            $this->shop_name = $profile->shop_name ?? '';
            $this->shop_description = $profile->shop_description ?? '';
            $this->shop_address = $profile->shop_address ?? '';
        }
    }

    public function save()
    {
        $this->validate([
            'shop_name' => 'required',
            'shop_description' => 'required',
            'shop_address' => 'required',
        ]);

        $isCompleted = !empty($this->shop_name) && !empty($this->shop_description) && !empty($this->shop_address);

        $profile = SellerProfile::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'shop_name' => $this->shop_name,
                'shop_description' => $this->shop_description,
                'shop_address' => $this->shop_address,
                'is_completed' => $isCompleted,
            ]
        );

        session()->flash('success', 'Profil toko berhasil diperbarui!');
    }

    public function render()
    {
        return view('livewire.seller.seller-profile-component', [
            'profile' => Auth::user()->sellerProfile,
        ])->layout('layouts.app', ['title' => 'Seller Profile - PT BOBA']);
    }
}
