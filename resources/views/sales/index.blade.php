@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Sales Report</h1>
    
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Filter Sales Report</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">From Date</label>
                    <input type="date" id="fromDate" class="form-control" value="{{ date('Y-m-01') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">To Date</label>
                    <input type="date" id="toDate" class="form-control" value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Type of Service</label>
                    <select id="serviceMode" class="form-select">
                        <option value="">All</option>
                        <option value="drop_off">Drop Off</option>
                        <option value="self_service">Self Service</option>
                        <option value="pickup">Pickup</option>
                        <option value="delivery">Delivery</option>
                        <option value="walk_in">Walk-in</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Customer Name</label>
                    <input type="text" id="customerName" class="form-control" placeholder="Search customer...">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="d-flex gap-2 w-100">
                        <button id="filterBtn" class="btn btn-primary flex-fill">Apply Filters</button>
                        <a id="pdfLink" class="btn btn-secondary" href="#" target="_blank" title="Export PDF">
                            <i class="fas fa-file-pdf"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Recent Orders (Last 10 Orders)</h5>
            <small>Automatically shows latest orders</small>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>Date & Time</th>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Service</th>
                            <th>Service Type</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="recentOrdersTable">
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                Loading recent orders...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Sales Report</h5>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-light text-dark fs-6">
                    Total Sales: <strong>₱<span id="totalSales">0.00</span></strong>
                </span>
                <span class="badge bg-light text-dark fs-6">
                    Total Orders: <strong><span id="totalOrders">0</span></strong>
                </span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Date & Time</th>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Services</th>
                            <th>Service Type</th>
                            <th>Loads</th>
                            <th>Subtotal</th>
                            <th>Tax</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="salesReportTable">
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">
                                <i class="fas fa-filter fa-2x mb-2 d-block"></i>
                                Apply filters above to load sales data
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div id="noResults" class="text-center py-4 d-none">
                <i class="fas fa-search fa-2x text-muted mb-2"></i>
                <p class="text-muted">No orders found for the selected filters</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const api = path => '/' + path.replace(/^\/+/, '');
    
    
    loadRecentOrders();
    
    
    document.getElementById('filterBtn').addEventListener('click', function() {
        loadSalesReport();
    });
    
    document.getElementById('customerName').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            loadSalesReport();
        }
    });
    
    function loadRecentOrders() {
        const tbody = document.getElementById('recentOrdersTable');
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-3">
                    <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                    Loading recent orders...
                </td>
            </tr>
        `;
        
        axios.get(api('sales/report') + '?from_date=' + getDateDaysAgo(30) + '&to_date=' + new Date().toISOString().split('T')[0])
            .then(response => {
                const recentOrders = response.data.recent_orders || [];
                
                if (recentOrders.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                <div>No recent orders found</div>
                            </td>
                        </tr>
                    `;
                    return;
                }
                
                tbody.innerHTML = recentOrders.map(order => `
                    <tr>
                        <td>${formatDateTime(order.created_at)}</td>
                        <td><strong>${order.order_number}</strong></td>
                        <td>${order.customer ? order.customer.first_name + ' ' + order.customer.last_name : '<span class="text-muted">Walk-in</span>'}</td>
                        <td>${order.service ? order.service.service_type : 'N/A'}</td>
                        <td><span class="badge bg-secondary">${order.service_mode ? order.service_mode.replace('_', ' ').toUpperCase() : 'N/A'}</span></td>
                        <td>₱${parseFloat(order.total).toFixed(2)}</td>
                        <td><span class="badge ${getStatusBadgeClass(order.status)}">${order.status.toUpperCase()}</span></td>
                    </tr>
                `).join('');
            })
            .catch(error => {
                console.error('Error loading recent orders:', error);
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center py-4 text-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Error loading recent orders
                        </td>
                    </tr>
                `;
            });
    }
    
    function loadSalesReport() {
        const params = new URLSearchParams();
        const fromDate = document.getElementById('fromDate').value;
        const toDate = document.getElementById('toDate').value;
        
        if (fromDate) params.append('from_date', fromDate);
        if (toDate) params.append('to_date', toDate);
        
        const serviceMode = document.getElementById('serviceMode').value;
        if (serviceMode) params.append('service_mode', serviceMode);
        
        const customerName = document.getElementById('customerName').value;
        if (customerName) params.append('customer_name', customerName);
        
        const tbody = document.getElementById('salesReportTable');
        const noResults = document.getElementById('noResults');
        tbody.innerHTML = `
            <tr>
                <td colspan="10" class="text-center py-3">
                    <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                    Loading sales data...
                </td>
            </tr>
        `;
        noResults.classList.add('d-none');
        
        axios.get(api('sales/report') + '?' + params.toString())
            .then(response => {
                const orders = response.data.data || [];
                
                document.getElementById('totalSales').textContent = parseFloat(response.data.total_sales || 0).toFixed(2);
                document.getElementById('totalOrders').textContent = response.data.total_orders || 0;
                
                if (orders.length === 0) {
                    tbody.innerHTML = '';
                    noResults.classList.remove('d-none');
                    return;
                }
                
                noResults.classList.add('d-none');
                tbody.innerHTML = orders.map(order => `
                    <tr>
                        <td>${formatDateTime(order.created_at)}</td>
                        <td><strong>${order.order_number}</strong></td>
                        <td>${order.customer ? order.customer.first_name + ' ' + order.customer.last_name : '<span class="text-muted">Walk-in</span>'}</td>
                        <td><span class="badge bg-secondary">${order.service_mode ? order.service_mode.replace('_', ' ').toUpperCase() : 'N/A'}</span></td>
                        <td>${order.service ? order.service.service_type : 'N/A'}</td>
                        <td>${order.number_of_loads || 1}</td>
                        <td>₱${parseFloat(order.subtotal || 0).toFixed(2)}</td>
                        <td>₱${parseFloat(order.tax || 0).toFixed(2)}</td>
                        <td><strong>₱${parseFloat(order.total).toFixed(2)}</strong></td>
                        <td><span class="badge ${getStatusBadgeClass(order.status)}">${order.status.toUpperCase()}</span></td>
                    </tr>
                `).join('');
                
                document.getElementById('pdfLink').href = api('sales/report') + '?' + params.toString() + '&export=pdf';
            })
            .catch(error => {
                console.error('Error loading sales report:', error);
                tbody.innerHTML = `
                    <tr>
                        <td colspan="10" class="text-center py-4 text-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Error loading sales data
                        </td>
                    </tr>
                `;
                noResults.classList.add('d-none');
            });
    }
    
    function formatDateTime(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
    }
    
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString();
    }
    
    function getDateDaysAgo(days) {
        const date = new Date();
        date.setDate(date.getDate() - days);
        return date.toISOString().split('T')[0];
    }
    
    function getStatusBadgeClass(status) {
        const classes = {
            'pending': 'bg-warning text-dark',
            'in_progress': 'bg-info',
            'completed': 'bg-success',
            'cancelled': 'bg-danger'
        };
        return classes[status] || 'bg-secondary';
    }
    
    loadSalesReport();
});
</script>

<style>
.badge {
    font-size: 0.75em;
    padding: 0.35em 0.65em;
}
.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.075);
}
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}
</style>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
@endsection