<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class RegisterComponent extends Component
{
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $address = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function register()
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required',
            'address' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'password' => Hash::make($this->password),
            'role' => 'buyer',
        ]);

        session()->flash('success', 'Registrasi berhasil! Silakan login.');
        return redirect('/login');
    }

    public function render()
    {
        return view('livewire.auth.register-component')
            ->layout('layouts.app', ['title' => 'Register Buyer - PT BOBA']);
    }
}
