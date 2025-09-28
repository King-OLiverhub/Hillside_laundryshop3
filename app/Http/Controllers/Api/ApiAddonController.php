<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Addon;

class ApiAddonController extends Controller
{
    public function index()
    {
        return Addon::all();
    }
}
