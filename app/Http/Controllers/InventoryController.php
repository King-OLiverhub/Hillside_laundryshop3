<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Product;

class InventoryController extends Controller
{
    public function page()
    {
        $services = Service::all();
        $products = Product::all();
        

        return view('inventory.index', compact('services', 'products'));
    }
    
}
