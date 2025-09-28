<?php
namespace App\Http\Controllers;

use App\Models\Addon;

class AddonController extends Controller
{
    public function index()
    {
        return response()->json(Addon::where('available', true)->orderBy('name')->get());
    }
}
