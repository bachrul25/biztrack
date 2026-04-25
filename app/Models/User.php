<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function sellerProfile(): HasOne
    {
        return $this->hasOne(SellerProfile::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'seller_id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'seller_id');
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class, 'buyer_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function serviceBookings(): HasMany
    {
        return $this->hasMany(ServiceBooking::class, 'buyer_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isBuyer(): bool
    {
        return $this->role === 'buyer';
    }

    public function isSeller(): bool
    {
        return $this->role === 'seller';
    }
}
