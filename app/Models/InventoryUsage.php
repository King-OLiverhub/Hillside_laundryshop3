<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoryUsage extends Model
{
    use HasFactory;
protected $fillable = ['product_id','pos_order_item_id','quantity_used','note','performed_by'];


public function product(){ return $this->belongsTo(Product::class); }
public function posOrderItem(){ return $this->belongsTo(PosOrderItem::class); }
}
