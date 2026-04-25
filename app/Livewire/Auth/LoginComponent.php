<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use App\Models\User;

class LoginComponent extends Component
{
    public string $email = '';
    public string $password = '';

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $this->email)->first();

        if (!$user || !Hash::check($this->password, $user->password)) {
            session()->flash('error', 'Email atau password salah.');
            return;
        }

        Auth::login($user);

        return match ($user->role) {
            'admin' => redirect('/admin/dashboard'),
            'buyer' => redirect('/buyer/dashboard'),
            'seller' => redirect('/seller/dashboard'),
        };
    }

    public function render()
    {
        return view('livewire.auth.login-component')
            ->layout('layouts.app', ['title' => 'Login - PT BOBA']);
    }
}
