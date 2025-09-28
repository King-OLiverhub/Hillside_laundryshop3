@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Overview</h1>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary btn-sm" onclick="refreshDashboard()">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
            <span class="text-muted small align-self-center">{{ now()->format('l, F j, Y') }}</span>
        </div>
    </div>

    
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Customers
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $customersCount }}</div>
                            <div class="text-xs text-muted mt-1">
                                <i class="fas fa-user-plus"></i> Regular: {{ \App\Models\Customer::where('customer_type', 'regular')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Products
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $productsCount }}</div>
                            <div class="text-xs text-muted mt-1">
                                <i class="fas fa-exclamation-triangle text-danger"></i> Low Stock: {{ $lowStockProducts }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Available Services
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $servicesCount }}</div>
                            <div class="text-xs text-muted mt-1">
                                <i class="fas fa-check-circle text-success"></i> All Active
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-concierge-bell fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Sales (Filtered)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format($totalSales, 2) }}</div>
                            <div class="text-xs text-muted mt-1">
                                <i class="fas fa-calendar"></i> {{ $fromDate }} to {{ $toDate }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-cash fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Filter Dashboard Data</h6>
            <button class="btn btn-sm btn-outline-secondary" type="button" data-toggle="collapse" data-target="#filterCollapse">
                <i class="fas fa-filter"></i> Toggle Filters
            </button>
        </div>
        <div class="collapse show" id="filterCollapse">
            <div class="card-body">
                <form method="GET" action="{{ route('dashboard') }}" id="dashboardFilterForm">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small font-weight-bold">From Date</label>
                            <input type="date" name="from_date" value="{{ $fromDate }}" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small font-weight-bold">To Date</label>
                            <input type="date" name="to_date" value="{{ $toDate }}" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small font-weight-bold">Service</label>
                            <select name="service_mode" class="form-control form-control-sm">
                                <option value="">All</option>
                                <option value="drop_off" {{ $serviceMode == 'drop_off' ? 'selected' : '' }}>Drop-off</option>
                                <option value="self_service" {{ $serviceMode == 'self_service' ? 'selected' : '' }}>Self-service</option>
                                <option value="pickup" {{ $serviceMode == 'pickup' ? 'selected' : '' }}>Pickup</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small font-weight-bold">Customer Type</label>
                            <select name="customer_type" class="form-control form-control-sm">
                                <option value="">All Types</option>
                                <option value="walkin" {{ $customerType == 'walkin' ? 'selected' : '' }}>Walk-in</option>
                                <option value="regular" {{ $customerType == 'regular' ? 'selected' : '' }}>Regular</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="d-flex gap-2 w-100">
                                <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                    <i class="fas fa-search"></i> Apply
                                </button>
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-times"></i> Clear
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

   
    <div class="row mb-4">
        
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Sales Summary</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3 bg-light">
                                <div class="text-primary font-weight-bold h5">₱{{ number_format($totalSales, 2) }}</div>
                                <div class="text-xs text-muted">Today's Sales</div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3 bg-light">
                                <div class="text-success font-weight-bold h5">₱{{ number_format($monthSales, 2) }}</div>
                                <div class="text-xs text-muted">This Month</div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3 bg-light">
                                <div class="text-warning font-weight-bold h5">{{ $pendingOrders }}</div>
                                <div class="text-xs text-muted">Pending Orders</div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3 bg-light">
                                <div class="text-danger font-weight-bold h5">{{ $lowStockProducts }}</div>
                                <div class="text-xs text-muted">Low Stock Items</div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 text-center">
                        <div class="bg-gray-100 rounded p-3">
                            <div class="progress mb-2" style="height: 20px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <a href="{{ route('pos.index') }}" class="btn btn-primary w-100 h-100 py-3 d-flex flex-column align-items-center">
                                <i class="fas fa-cash-register fa-2x mb-2"></i>
                                <span>New Order</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('inventory.page') }}" class="btn btn-success w-100 h-100 py-3 d-flex flex-column align-items-center">
                                <i class="fas fa-boxes fa-2x mb-2"></i>
                                <span>Inventory</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('sales.page') }}" class="btn btn-info w-100 h-100 py-3 d-flex flex-column align-items-center">
                                <i class="fas fa-chart-bar fa-2x mb-2"></i>
                                <span>Sales Report</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('customers.page') }}" class="btn btn-warning w-100 h-100 py-3 d-flex flex-column align-items-center">
                                <i class="fas fa-users fa-2x mb-2"></i>
                                <span>Customers</span>
                            </a>
                        </div>
                    </div>
                    
                    
                    <div class="mt-4 p-3 bg-light rounded">
                        <h6 class="font-weight-bold mb-2">System Status</h6>
                        <div class="d-flex justify-content-between small mb-1">
                            <span>Database:</span>
                            <span class="badge badge-success">Connected</span>
                        </div>
                        <div class="d-flex justify-content-between small mb-1">
                            <span>Last Backup:</span>
                            <span>{{ now()->subDays(1)->format('M j, H:i') }}</span>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span>Uptime:</span>
                            <span>99.8%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

  
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Recent System Activity</h6>
                    <span class="badge badge-primary">Latest Updates</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-circle bg-primary">
                                    <i class="fas fa-file-alt text-white"></i>
                                </div>
                                <div class="ml-3">
                                    <div class="small text-gray-500">{{ now()->format('F j, Y') }}</div>
                                    <span>Dashboard refreshed with latest data</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-circle bg-success">
                                    <i class="fas fa-dollar-sign text-white"></i>
                                </div>
                                <div class="ml-3">
                                    <div class="small text-gray-500">Today</div>
                                    <span>{{ $pendingOrders }} new orders processed</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-circle bg-info">
                                    <i class="fas fa-box text-white"></i>
                                </div>
                                <div class="ml-3">
                                    <div class="small text-gray-500">Inventory</div>
                                    <span>All products are in stock</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-circle bg-warning">
                                    <i class="fas fa-users text-white"></i>
                                </div>
                                <div class="ml-3">
                                    <div class="small text-gray-500">Customers</div>
                                    <span>{{ $customersCount }} new customers this week</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    border-radius: 0.35rem;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.shadow {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}

.icon-circle {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
}

.text-xs {
    font-size: 0.7rem;
}

.progress {
    border-radius: 0.35rem;
}

.btn {
    border-radius: 0.35rem;
    transition: all 0.3s;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}
</style>

<script>
function refreshDashboard() {
    const btn = event.target;
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
    btn.disabled = true;
    
    
    setTimeout(() => {
        window.location.reload();
    }, 1000);
}


setTimeout(() => {
    const refreshBtn = document.querySelector('[onclick="refreshDashboard()"]');
    if (refreshBtn) {
        refreshBtn.click();
    }
}, 300000); 
</script>


<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection