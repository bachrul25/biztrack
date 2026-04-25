<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'seller_id',
        'brand',
        'service_type',
        'name',
        'description',
        'price',
        'category',
        'image',
        'status',
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(ServiceBooking::class);
    }
}
