<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function page() 
    { 
        return view('customers.index'); 
    }

    public function index()
    {
        return response()->json(Customer::orderBy('first_name')->get());
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
        return response()->json($customer);
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:20',
            'customer_type' => 'required|in:walkin,regular',
        ]);
        
        $customer->update($data);
        return response()->json($customer);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return response()->json(['message' => 'Customer deleted successfully']);
    }
}