<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PosOrder extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_number', 'customer_id', 'service_id', 'service_mode', 
        'number_of_loads', 'unit_price', 'subtotal', 'tax', 'total', 'status'
    ];

    public function customer() 
    { 
        return $this->belongsTo(Customer::class); 
    }
    
    public function service() 
    { 
        return $this->belongsTo(Service::class); 
    }
    
    public function items() 
    { 
        return $this->hasMany(PosOrderItem::class); 
    }
    
    public function addons() 
    { 
        return $this->hasMany(PosOrderAddon::class); 
    }
    
    public function orderDetails() 
    { 
        return $this->hasMany(OrderDetail::class); 
    }
}