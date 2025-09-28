<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function page() { return view('inventory.index'); }

    public function index(Request $r)
    {
        $q = Product::query();
        if ($r->has('available')) $q->where('available', filter_var($r->available, FILTER_VALIDATE_BOOLEAN));
        if ($r->filled('name')) $q->where('name', 'like', '%'.$r->name.'%');
        return response()->json($q->orderBy('name')->get());
    }

    public function useProduct(Request $r)
    {
        $d = $r->validate(['product_id'=>'required|exists:products,id','quantity'=>'required|integer|min:1']);
        $p = Product::find($d['product_id']);
        $p->quantity = max(0, $p->quantity - $d['quantity']);
        if ($p->quantity <= $p->critical_quantity) $p->available = false;
        $p->save();
        return response()->json($p);
    }
}
