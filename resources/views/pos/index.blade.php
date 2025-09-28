@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">POS</h1>
    
    <div class="row">
        <div class="col-md-8">
            
          <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Item Details (For Tracking Only - No Charge)</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select id="itemType" class="form-select">
                                <option value="">Select Item Type</option>
                                <option value="t_shirt">T-Shirt</option>
                                <option value="pants">Pants</option>
                                <option value="jacket">Jacket</option>
                                <option value="shorts">Shorts</option>
                                <option value="dress">Dress</option>
                                <option value="undergarments">Undergarments</option>
                                <option value="socks">Socks</option>
                                <option value="blanket">Blanket</option>
                                <option value="pillow_case">Pillow Case</option>
                                <option value="curtains">Curtains</option>
                                <option value="towel">Towel</option>
                                <option value="uniform">Uniform</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="number" id="itemQty" class="form-control" value="1" min="1" placeholder="Qty">
                        </div>
                        <div class="col-md-4">
                            <input type="text" id="itemNotes" class="form-control" placeholder="Notes (optional)">
                        </div>
                        <div class="col-md-2">
                            <button type="button" id="addItem" class="btn btn-warning btn-sm">Add Item</button>
                        </div>
                    </div>

                    <table class="table table-bordered table-sm" id="itemsTable">
                        <thead>
                            <tr>
                                <th>Item Type</th>
                                <th>Quantity</th>
                                <th>Notes</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="itemsTableBody">
                            
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Customer & Service</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Cust ID</label>
                            <select id="customerSelect" class="form-select">
                                <option value="">-- Select Customer --</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->id }} - {{ $customer->first_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Name</label>
                            <input type="text" id="customerName" class="form-control" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Type</label>
                            <input type="text" id="customerType" class="form-control" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">No. of Load</label>
                            <input type="number" id="numberOfLoads" class="form-control" value="1" min="1">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Unit Price</label>
                            <input type="number" id="unitPrice" class="form-control" readonly>
                        </div>
                    </div>

                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <h6>Services</h6>
                            <div class="row" id="servicesGrid">
                                @foreach($services as $service)
                                <div class="col-md-3 mb-2">
                                    <div class="card service-card" data-id="{{ $service->id }}" data-price="{{ $service->price_per_load }}">
                                        <div class="card-body p-2">
                                            <small class="d-block fw-bold">ID: {{ $service->id }} - {{ $service->service_type }}</small>
                                            <small class="d-block">Load: {{ $service->load_capacity_kg }} kg</small>
                                            <small class="d-block">Type: {{ $service->per_load }}</small>
                                            <small class="d-block">₱{{ number_format($service->price_per_load, 2) }}/load</small>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Addons (Paid Items)</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-sm" id="addonsTable">
                        <thead>
                            <tr>
                                <th>Product ID</th>
                                <th>Name</th>
                                <th>Brand</th>
                                <th>Unit</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="addonsTableBody">
                            
                        </tbody>
                    </table>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <select id="addonSelect" class="form-select">
                                <option value="">Select Addon</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" 
                                            data-name="{{ $product->name }}"
                                            data-brand="{{ $product->brand }}"
                                            data-unit="{{ $product->unit }}"
                                            data-price="{{ $product->price }}">
                                        {{ $product->id }} - {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="number" id="addonQty" class="form-control" value="1" min="1" placeholder="Qty">
                        </div>
                        <div class="col-md-2">
                            <button type="button" id="addAddon" class="btn btn-success btn-sm">Add</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-md-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    
                    <div class="mb-3" id="itemDetailsSummary">
                        <h6 class="text-muted">Item Details:</h6>
                        <div id="itemSummaryList" class="small text-muted">
                            No items added
                        </div>
                    </div>

                    <div class="mb-3">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Subtotal:</strong></td>
                                <td id="subtotal" class="text-end">₱0.00</td>
                            </tr>
                            <tr>
                                <td><strong>Tax (12%):</strong></td>
                                <td id="tax" class="text-end">₱0.00</td>
                            </tr>
                            <tr class="table-primary">
                                <td><strong>Total:</strong></td>
                                <td id="total" class="text-end fw-bold">₱0.00</td>
                            </tr>
                        </table>
                    </div>
                    
                    <button type="button" id="placeOrder" class="btn btn-success btn-lg w-100">
                        Place Order
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.service-card {
    cursor: pointer;
    transition: all 0.3s;
}
.service-card:hover {
    background-color: #f8f9fa;
    border-color: #007bff;
}
.service-card.selected {
    background-color: #007bff;
    color: white;
}
.item-details {
    background-color: #fff3cd;
    border-left: 4px solid #ffc107;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentService = null;
    let addons = [];
    let itemDetails = []; 

    
    document.getElementById('customerSelect').addEventListener('change', function() {
        const customerId = this.value;
        if (customerId) {
            const customer = @json($customers->keyBy('id')->toArray())[customerId];
            if (customer) {
                document.getElementById('customerName').value = customer.first_name + ' ' + customer.last_name;
                document.getElementById('customerType').value = customer.customer_type;
            }
        } else {
            document.getElementById('customerName').value = '';
            document.getElementById('customerType').value = '';
        }
    });

    
    document.querySelectorAll('.service-card').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.service-card').forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            
            currentService = {
                id: this.dataset.id,
                price: parseFloat(this.dataset.price),
                name: this.querySelector('small').textContent
            };
            
            document.getElementById('unitPrice').value = currentService.price;
            calculateTotals();
            updateItemSummary();
        });
    });

    document.getElementById('addItem').addEventListener('click', function() {
        const itemType = document.getElementById('itemType').value;
        const quantity = parseInt(document.getElementById('itemQty').value);
        const notes = document.getElementById('itemNotes').value;

        if (!itemType) {
            alert('Please select an item type');
            return;
        }

        if (quantity < 1) {
            alert('Please enter a valid quantity');
            return;
        }

        const item = {
            type: itemType,
            quantity: quantity,
            notes: notes,
            displayName: document.getElementById('itemType').options[document.getElementById('itemType').selectedIndex].text
        };

        itemDetails.push(item);
        updateItemsTable();
        updateItemSummary();

        document.getElementById('itemType').value = '';
        document.getElementById('itemQty').value = 1;
        document.getElementById('itemNotes').value = '';
    });

    function updateItemsTable() {
        const tbody = document.getElementById('itemsTableBody');
        tbody.innerHTML = itemDetails.map((item, index) => `
            <tr class="item-details">
                <td>${item.displayName}</td>
                <td>${item.quantity}</td>
                <td>${item.notes || '-'}</td>
                <td>
                    <button class="btn btn-danger btn-sm" onclick="removeItem(${index})">
                        Remove
                    </button>
                </td>
            </tr>
        `).join('');
    }

    window.removeItem = function(index) {
        itemDetails.splice(index, 1);
        updateItemsTable();
        updateItemSummary();
    };

    function updateItemSummary() {
        const summaryList = document.getElementById('itemSummaryList');
        
        if (itemDetails.length === 0) {
            summaryList.innerHTML = 'No items added';
            return;
        }

        let summaryHTML = '';
        itemDetails.forEach(item => {
            summaryHTML += `<div>${item.displayName}: ${item.quantity} pcs</div>`;
            if (item.notes) {
                summaryHTML += `<small class="text-muted">Notes: ${item.notes}</small><br>`;
            }
        });
        
        summaryList.innerHTML = summaryHTML;
    }

    document.getElementById('addAddon').addEventListener('click', function() {
        const addonSelect = document.getElementById('addonSelect');
        const selectedOption = addonSelect.options[addonSelect.selectedIndex];
        const quantity = parseInt(document.getElementById('addonQty').value);

        if (!selectedOption.value) {
            alert('Please select an addon');
            return;
        }

        const addon = {
            id: selectedOption.value,
            name: selectedOption.dataset.name,
            brand: selectedOption.dataset.brand,
            unit: selectedOption.dataset.unit,
            quantity: quantity,
            unitPrice: parseFloat(selectedOption.dataset.price),
            amount: parseFloat(selectedOption.dataset.price) * quantity
        };

        addons.push(addon);
        updateAddonsTable();
        calculateTotals();

        addonSelect.value = '';
        document.getElementById('addonQty').value = 1;
    });

    function updateAddonsTable() {
        const tbody = document.getElementById('addonsTableBody');
        tbody.innerHTML = addons.map((addon, index) => `
            <tr>
                <td>${addon.id}</td>
                <td>${addon.name}</td>
                <td>${addon.brand}</td>
                <td>${addon.unit}</td>
                <td>${addon.quantity}</td>
                <td>₱${addon.unitPrice.toFixed(2)}</td>
                <td>₱${addon.amount.toFixed(2)}</td>
                <td>
                    <button class="btn btn-danger btn-sm" onclick="removeAddon(${index})">
                        Remove
                    </button>
                </td>
            </tr>
        `).join('');
    }

    window.removeAddon = function(index) {
        addons.splice(index, 1);
        updateAddonsTable();
        calculateTotals();
    };

    function calculateTotals() {
        let subtotal = 0;

        if (currentService) {
            const loads = parseInt(document.getElementById('numberOfLoads').value) || 1;
            subtotal += currentService.price * loads;
        }

        addons.forEach(addon => {
            subtotal += addon.amount;
        });

        

        const tax = subtotal * 0.12;
        const total = subtotal + tax;

        document.getElementById('subtotal').textContent = '₱' + subtotal.toFixed(2);
        document.getElementById('tax').textContent = '₱' + tax.toFixed(2);
        document.getElementById('total').textContent = '₱' + total.toFixed(2);
    }

    
    document.getElementById('numberOfLoads').addEventListener('input', function() {
        calculateTotals();
        updateItemSummary();
    });

    
    document.getElementById('placeOrder').addEventListener('click', function() {
        if (!currentService) {
            alert('Please select a service');
            return;
        }

        const orderData = {
            customer_id: document.getElementById('customerSelect').value || null,
            service_id: currentService.id,
            service_mode: 'drop_off',
            number_of_loads: parseInt(document.getElementById('numberOfLoads').value) || 1,
            item_details: itemDetails, 
            items: addons.map(addon => ({
                product_id: addon.id,
                quantity: addon.quantity
            }))
        };

        
        const button = this;
        button.disabled = true;
        button.textContent = 'Processing...';

        axios.post('{{ route("pos.checkout") }}', orderData)
            .then(response => {
                if (response.data.success) {
                    alert('Order placed successfully!');
                    
                    currentService = null;
                    addons = [];
                    itemDetails = []; 
                    document.querySelectorAll('.service-card').forEach(c => c.classList.remove('selected'));
                    document.getElementById('customerSelect').value = '';
                    document.getElementById('customerName').value = '';
                    document.getElementById('customerType').value = '';
                    document.getElementById('numberOfLoads').value = 1;
                    document.getElementById('unitPrice').value = '';
                    document.getElementById('addonsTableBody').innerHTML = '';
                    document.getElementById('itemsTableBody').innerHTML = ''; 
                    document.getElementById('itemSummaryList').innerHTML = 'No items added'; 
                    calculateTotals();
                } else {
                    alert('Error: ' + response.data.message);
                }
            })
            .catch(error => {
                alert('Error placing order: ' + (error.response?.data?.message || error.message));
            })
            .finally(() => {
                button.disabled = false;
                button.textContent = 'Place Order';
            });
    });

    
    updateItemSummary();
});
</script>
@endsection