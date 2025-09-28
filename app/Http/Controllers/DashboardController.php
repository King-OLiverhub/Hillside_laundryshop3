<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Service;
use App\Models\PosOrder;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $fromDate = $request->get('from_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $toDate = $request->get('to_date', Carbon::now()->format('Y-m-d'));
        $serviceMode = $request->get('service_mode', '');
        $customerType = $request->get('customer_type', '');

        $ordersQuery = PosOrder::with('customer');

        if ($fromDate && $toDate) {
            $ordersQuery->whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        }

        if ($serviceMode) {
            $ordersQuery->where('service_mode', $serviceMode);
        }

        if ($customerType) {
            $ordersQuery->whereHas('customer', function($query) use ($customerType) {
                $query->where('customer_type', $customerType);
            });
        }

        $customersCount = Customer::count();
        $productsCount = Product::count();
        $servicesCount = Service::count();
        $totalSales = $ordersQuery->sum('total');
        
        $todaySales = PosOrder::whereDate('created_at', today())->sum('total');
        $monthSales = PosOrder::whereMonth('created_at', now()->month)->sum('total');
        $pendingOrders = PosOrder::where('status', 'pending')->count();
        
        $lowStockProducts = Product::whereColumn('quantity', '<=', 'critical_quantity')->count();

        return view('dashboard.index', compact(
            'customersCount',
            'productsCount', 
            'servicesCount',
            'totalSales',
            'todaySales',
            'monthSales',
            'pendingOrders',
            'lowStockProducts',
            'fromDate',
            'toDate',
            'serviceMode',
            'customerType'
        ));
    }
}