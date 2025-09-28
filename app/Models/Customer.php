<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'first_name', 'middle_name', 'last_name', 
        'contact', 'customer_type', 'available'
    ];

    public function orders()
    {
        return $this->hasMany(PosOrder::class);
    }

    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . 
               ($this->middle_name ? $this->middle_name . ' ' : '') . 
               $this->last_name);
    }
}   