@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Inventory Management</h1>

    
    <div class="card mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Services</h5>
            <button class="btn btn-light btn-sm" onclick="openServiceModal()">
                <i class="fas fa-plus"></i> Add Service
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Service Type</th>
                            <th>Load Size</th>
                            <th>Service</th>
                            <th>Services Disc</th>
                            <th>Price/Load</th>
                            <th>Available</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="servicesTable">
                        @foreach($services as $service)
                        <tr>
                            <td>{{ $service->id }}</td>
                            <td>{{ $service->service_type }}</td>
                            <td>{{ $service->load_capacity_kg }} kg</td>
                            <td>{{ $service->per_load }}</td>
                            <td>{{ $service->services }}</td>
                            <td>₱{{ number_format($service->price_per_load, 2) }}</td>
                            <td>
                                <span class="badge {{ $service->available ? 'bg-success' : 'bg-danger' }}">
                                    {{ $service->available ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm me-1" onclick="editService({{ $service->id }})">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="deleteService({{ $service->id }})">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Products</h5>
            <div class="d-flex align-items-center">
                <input type="text" id="productSearch" class="form-control form-control-sm me-2" placeholder="Search products..." style="width: 200px;">
                <button class="btn btn-light btn-sm" onclick="openProductModal()">
                    <i class="fas fa-plus"></i> Add Product
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Product ID</th>
                            <th>Name</th>
                            <th>Brand</th>
                            <th>Unit</th>
                            <th>Quantity</th>
                            <th>Buy Price</th>
                            <th>Sell Price</th>
                            <th>Critical Level</th>
                            <th>Available</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="productsTable">
                        @foreach($products as $product)
                        <tr class="{{ $product->quantity <= $product->critical_quantity ? 'table-warning' : '' }}">
                            <td>{{ 1000 + $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->brand }}</td>
                            <td>{{ $product->unit }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>₱{{ number_format($product->buy_price ?? $product->price * 0.7, 2) }}</td>
                            <td>₱{{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->critical_quantity }}</td>
                            <td>
                                <span class="badge {{ $product->available ? 'bg-success' : 'bg-danger' }}">
                                    {{ $product->available ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm me-1" onclick="editProduct({{ $product->id }})">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="deleteProduct({{ $product->id }})">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="serviceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="serviceModalLabel">Add Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="serviceForm">
                <div class="modal-body">
                    <input type="hidden" id="serviceId" name="id">
                    <div class="mb-3">
                        <label class="form-label">Service ID</label>
                        <input type="text" class="form-control" id="serviceCode" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Service Type *</label>
                        <input type="text" class="form-control" id="serviceType" name="service_type" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Load Size (kg) *</label>
                        <input type="number" class="form-control" id="loadCapacity" name="load_capacity_kg" step="0.1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Service</label>
                        <select class="form-select" id="perLoad" name="per_load">
                            <option value="Dropoff">Dropoff</option>
                            <option value="Self-Service">Self-Service</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Services Description</label>
                        <input type="text" class="form-control" id="services" name="services" placeholder="e.g., Washing, Drying, Folding">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price per Load *</label>
                        <input type="number" class="form-control" id="pricePerLoad" name="price_per_load" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Available</label>
                        <select class="form-select" id="serviceAvailable" name="available">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Service</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Add Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="productForm">
                <div class="modal-body">
                    <input type="hidden" id="productId" name="id">
                    <div class="mb-3">
                        <label class="form-label">Product ID</label>
                        <input type="text" class="form-control" id="productCode" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Name *</label>
                        <input type="text" class="form-control" id="productName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Brand</label>
                        <input type="text" class="form-control" id="productBrand" name="brand">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Unit</label>
                        <input type="text" class="form-control" id="productUnit" name="unit" placeholder="e.g., bottle, pack, gallon">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantity *</label>
                        <input type="number" class="form-control" id="productQuantity" name="quantity" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Buy Price</label>
                        <input type="number" class="form-control" id="productBuyPrice" name="buy_price" step="0.01">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sell Price *</label>
                        <input type="number" class="form-control" id="productPrice" name="price" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Critical Quantity</label>
                        <input type="number" class="form-control" id="productCritical" name="critical_quantity" value="5">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Available</label>
                        <select class="form-select" id="productAvailable" name="available">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const api = path => '/' + path.replace(/^\/+/, '');

    
    let serviceModal = null;
    let productModal = null;

    if (document.getElementById('serviceModal')) {
        serviceModal = new bootstrap.Modal(document.getElementById('serviceModal'));
    }
    if (document.getElementById('productModal')) {
        productModal = new bootstrap.Modal(document.getElementById('productModal'));
    }

    window.openServiceModal = function() {
        document.getElementById('serviceForm').reset();
        document.getElementById('serviceId').value = '';
        document.getElementById('serviceModalLabel').textContent = 'Add Service';
        
        if (!document.getElementById('serviceId').value) {
            document.getElementById('serviceCode').value = 'NEW';
        }
        if (serviceModal) serviceModal.show();
    };

    window.openProductModal = function() {
        document.getElementById('productForm').reset();
        document.getElementById('productId').value = '';
        document.getElementById('productModalLabel').textContent = 'Add Product';
        
        if (!document.getElementById('productId').value) {
            document.getElementById('productCode').value = '100X';
        }
        if (productModal) productModal.show();
    };

    
    document.getElementById('serviceForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const data = Object.fromEntries(formData);
        const id = data.id;

        const method = id ? 'put' : 'post';
        const url = id ? api(`api/services/${id}`) : api('api/services');

        axios[method](url, data)
            .then(response => {
                if (serviceModal) serviceModal.hide();
                
                location.reload();
            })
            .catch(error => {
                alert('Error saving service: ' + (error.response?.data?.message || error.message));
            });
    });

    
    document.getElementById('productForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const data = Object.fromEntries(formData);
        const id = data.id;

        const method = id ? 'put' : 'post';
        const url = id ? api(`api/products/${id}`) : api('api/products');

        axios[method](url, data)
            .then(response => {
                if (productModal) productModal.hide();
                
                location.reload();
            })
            .catch(error => {
                alert('Error saving product: ' + (error.response?.data?.message || error.message));
            });
    });

    
    document.getElementById('productSearch').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#productsTable tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    
    window.editService = function(id) {
        axios.get(api(`api/services/${id}`))
            .then(response => {
                const service = response.data;
                document.getElementById('serviceId').value = service.id;
                document.getElementById('serviceCode').value = service.id;
                document.getElementById('serviceType').value = service.service_type || '';
                document.getElementById('loadCapacity').value = service.load_capacity_kg || '';
                document.getElementById('perLoad').value = service.per_load || '';
                document.getElementById('services').value = service.services || '';
                document.getElementById('pricePerLoad').value = service.price_per_load || '';
                document.getElementById('serviceAvailable').value = service.available ? '1' : '0';
                
                document.getElementById('serviceModalLabel').textContent = 'Edit Service';
                if (serviceModal) serviceModal.show();
            })
            .catch(error => {
                alert('Error loading service: ' + error.message);
            });
    };

    
    window.editProduct = function(id) {
        axios.get(api(`api/products/${id}`))
            .then(response => {
                const product = response.data;
                document.getElementById('productId').value = product.id;
                document.getElementById('productCode').value = 1000 + product.id;
                document.getElementById('productName').value = product.name || '';
                document.getElementById('productBrand').value = product.brand || '';
                document.getElementById('productUnit').value = product.unit || '';
                document.getElementById('productQuantity').value = product.quantity || '';
                document.getElementById('productBuyPrice').value = product.buy_price || '';
                document.getElementById('productPrice').value = product.price || '';
                document.getElementById('productCritical').value = product.critical_quantity || '';
                document.getElementById('productAvailable').value = product.available ? '1' : '0';
                
                document.getElementById('productModalLabel').textContent = 'Edit Product';
                if (productModal) productModal.show();
            })
            .catch(error => {
                alert('Error loading product: ' + error.message);
            });
    };

    
    window.deleteService = function(id) {
        if (confirm('Are you sure you want to delete this service?')) {
            axios.delete(api(`api/services/${id}`))
                .then(() => {
                    location.reload();
                })
                .catch(error => {
                    alert('Error deleting service: ' + error.message);
                });
        }
    };

    
    window.deleteProduct = function(id) {
        if (confirm('Are you sure you want to delete this product?')) {
            axios.delete(api(`api/products/${id}`))
                .then(() => {
                    location.reload();
                })
                .catch(error => {
                    alert('Error deleting product: ' + error.message);
                });
        }
    };

    
    document.querySelectorAll('.btn-close').forEach(button => {
        button.addEventListener('click', function() {
            if (serviceModal) serviceModal.hide();
            if (productModal) productModal.hide();
        });
    });

    
    document.querySelectorAll('.btn-secondary').forEach(button => {
        button.addEventListener('click', function() {
            if (serviceModal) serviceModal.hide();
            if (productModal) productModal.hide();
        });
    });
});
</script>
@endsection