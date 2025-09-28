<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;

class ApiCustomerController extends Controller
{
    public function index()
    {
        return Customer::orderBy('first_name')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:20',
            'customer_type' => 'required|in:walkin,regular',
        ]);

        $customer = Customer::create($data);

        return response()->json($customer, 201);
    }
}