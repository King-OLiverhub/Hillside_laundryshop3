<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = ['pos_order_id', 'item_type', 'quantity', 'notes'];
    
    public function order()
    {
        return $this->belongsTo(PosOrder::class, 'pos_order_id');
    }
    
    public function getDisplayNameAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->item_type));
    }
}