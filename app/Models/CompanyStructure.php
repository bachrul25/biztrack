<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyStructure extends Model
{
    protected $fillable = [
        'name',
        'position',
        'description',
        'photo',
        'sort_order',
        'status',
    ];
}
