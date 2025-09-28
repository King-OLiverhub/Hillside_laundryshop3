<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PosOrderAddon extends Model
{
    use HasFactory;
protected $fillable = ['pos_order_id','addon_id','quantity','unit_price','amount'];


public function order(){ return $this->belongsTo(PosOrder::class); }
public function addon(){ return $this->belongsTo(Addon::class); }
}
