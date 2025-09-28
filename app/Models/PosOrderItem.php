<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PosOrderItem extends Model
{
    use HasFactory;
protected $fillable = ['pos_order_id','item_name','quantity','unit_price','amount'];


public function order(){ return $this->belongsTo(PosOrder::class); }
public function inventoryUsages(){ return $this->hasMany(InventoryUsage::class); }
}
