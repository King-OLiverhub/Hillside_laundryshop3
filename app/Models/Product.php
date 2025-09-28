<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'brand', 'unit', 'quantity', 'buy_price', 'price', 
        'critical_quantity', 'available'
    ];

    protected $attributes = [
        'buy_price' => 0,
    ];
}