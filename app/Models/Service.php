<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'service_type', 'load_capacity_kg', 'per_load', 'services', 
        'price_per_load', 'available'
    ];

    public function orders()
    {
        return $this->hasMany(PosOrder::class);
    }
}