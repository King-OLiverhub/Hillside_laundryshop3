<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Addon extends Model
{
    use HasFactory;
protected $fillable = ['name','description','unit_price','available'];


public function posAddons()
{
return $this->hasMany(PosOrderAddon::class);
}
}
