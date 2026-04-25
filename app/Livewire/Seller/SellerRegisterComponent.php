<?php

namespace App\Livewire\Seller;

use App\Models\SellerProfile;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class SellerRegisterComponent extends Component
{
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $address = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $shop_name = '';
    public string $shop_description = '';
    public string $shop_address = '';

    public function register()
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required',
            'address' => 'required',
            'password' => 'required|min:6|confirmed',
            'shop_name' => 'required',
            'shop_description' => 'required',
            'shop_address' => 'required',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'password' => Hash::make($this->password),
            'role' => 'seller',
        ]);

        $isCompleted = !empty($this->shop_name) && !empty($this->shop_description) && !empty($this->shop_address);

        SellerProfile::create([
            'user_id' => $user->id,
            'shop_name' => $this->shop_name,
            'shop_description' => $this->shop_description,
            'shop_address' => $this->shop_address,
            'status' => 'pending',
            'is_completed' => $isCompleted,
        ]);

        session()->flash('success', 'Registrasi seller berhasil! Silakan login dan tunggu approval admin.');
        return redirect('/login');
    }

    public function render()
    {
        return view('livewire.seller.seller-register-component')
            ->layout('layouts.app', ['title' => 'Register Seller - PT BOBA']);
    }
}
