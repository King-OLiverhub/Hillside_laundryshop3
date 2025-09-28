<?php

namespace App\Http\Controllers;

use App\Models\PosOrder;
use App\Models\OrderDetail; 
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesReportController extends Controller
{
    public function page() 
    { 
        return view('sales.index'); 
    }

    public function index(Request $request)
    {
        $query = PosOrder::with(['customer', 'service', 'items', 'addons', 'orderDetails']) // Add orderDetails
            ->orderBy('created_at', 'desc');

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        if ($request->filled('service_mode')) {
            $query->where('service_mode', $request->service_mode);
        }
        if ($request->filled('customer_name')) {
            $name = $request->customer_name;
            $query->whereHas('customer', function($q) use ($name) {
                $q->where('first_name', 'like', "%{$name}%")
                  ->orWhere('last_name', 'like', "%{$name}%");
            });
        }

        $orders = $query->get();
        
        $recentOrdersQuery = PosOrder::with(['customer', 'service', 'orderDetails']) // Add orderDetails
            ->orderBy('created_at', 'desc')
            ->take(10);

        if ($request->filled('from_date') && $request->filled('to_date') && $request->get('filter_recent') === 'true') {
            $recentOrdersQuery->whereBetween('created_at', [
                $request->from_date . ' 00:00:00',
                $request->to_date . ' 23:59:59'
            ]);
        }

        $recentOrders = $recentOrdersQuery->get();

        $totalSales = $orders->sum('total');
        $totalOrders = $orders->count();

        if ($request->get('export') === 'pdf') {
            $pdf = Pdf::loadView('reports.sales', compact('orders', 'totalSales', 'totalOrders'));
            return $pdf->download('sales-report-' . now()->format('Y-m-d') . '.pdf');
        }

        return response()->json([
            'data' => $orders,
            'recent_orders' => $recentOrders,
            'total_sales' => $totalSales,
            'total_orders' => $totalOrders
        ]);
    }
}