<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Sales Report - Hillside Laundry</title>
  <style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 10px;
        margin: 15px;
        line-height: 1.2;
    }
    .header {
        text-align: center;
        margin-bottom: 15px;
        border-bottom: 2px solid #333;
        padding-bottom: 8px;
    }
    .header h2 {
        margin: 5px 0;
        font-size: 16px;
    }
    .header p {
        margin: 3px 0;
        font-size: 10px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 15px;
        font-size: 9px;
    }
    th, td {
        border: 1px solid #ccc;
        padding: 4px;
        text-align: left;
        vertical-align: top;
    }
    th {
        background-color: #f0f0f0;
        font-weight: bold;
        font-size: 9px;
    }
    .summary {
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 3px;
        margin-bottom: 15px;
        font-size: 10px;
    }
    .footer {
        margin-top: 20px;
        text-align: center;
        font-size: 8px;
        color: #666;
    }
    .status-pending { background-color: #fff3cd; color: #856404; }
    .status-completed { background-color: #d1edff; color: #004085; }
    .status-in_progress { background-color: #d4edda; color: #155724; }
    .status-cancelled { background-color: #f8d7da; color: #721c24; }
    .item-details {
        font-size: 8px;
        line-height: 1.1;
    }
    .nowrap {
        white-space: nowrap;
    }
    .text-center { text-align: center; }
    .text-right { text-align: right; }
  </style>
</head>
<body>
  <div class="header">
    <h2>Hillside Laundry - Sales Report</h2>
    <p>Generated: {{ now()->format('Y-m-d H:i') }}</p>
    @if(isset($orders) && $orders->count() > 0)
        <p>Period: {{ $orders->first()->created_at->format('Y-m-d') }} to {{ $orders->last()->created_at->format('Y-m-d') }}</p>
    @endif
  </div>

  <div class="summary">
    <strong>Report Summary:</strong><br>
    Total Orders: {{ $totalOrders ?? 0 }} | 
    Total Sales: ₱{{ number_format($totalSales ?? 0, 2) }}
  </div>

  <table>
    <thead>
      <tr>
        <th width="12%">Date & Time</th>
        <th width="10%">Order #</th>
        <th width="12%">Customer</th>
        <th width="8%">Mode</th>
        <th width="10%">Service</th>
        <th width="18%">Item Details</th>
        <th width="5%" class="text-center">Loads</th>
        <th width="8%" class="text-right">Subtotal</th>
        <th width="8%" class="text-right">Tax</th>
        <th width="10%" class="text-right">Total</th>
        <th width="9%" class="text-center">Status</th>
      </tr>
    </thead>
    <tbody>
      @forelse($orders as $order)
        <tr>
          <td class="nowrap">{{ $order->created_at->format('m/d/y H:i') }}</td>
          <td><strong>{{ $order->order_number }}</strong></td>
          <td>{{ $order->customer ? $order->customer->first_name . ' ' . $order->customer->last_name : 'Walk-in' }}</td>
          <td>{{ $order->service_mode ? ucfirst(str_replace('_', ' ', $order->service_mode)) : 'N/A' }}</td>
          <td>{{ $order->service ? $order->service->service_type : 'N/A' }}</td>
          <td class="item-details">
            @if($order->orderDetails && $order->orderDetails->count() > 0)
                @foreach($order->orderDetails as $detail)
                    • {{ ucfirst(str_replace('_', ' ', $detail->item_type)) }}: {{ $detail->quantity }} pcs
                    @if($detail->notes)
                        ({{ \Illuminate\Support\Str::limit($detail->notes, 20) }})
                    @endif
                    @if(!$loop->last)<br>@endif
                @endforeach
            @else
                -
            @endif
          </td>
          <td class="text-center">{{ $order->number_of_loads }}</td>
          <td class="text-right">₱{{ number_format($order->subtotal, 2) }}</td>
          <td class="text-right">₱{{ number_format($order->tax, 2) }}</td>
          <td class="text-right"><strong>₱{{ number_format($order->total, 2) }}</strong></td>
          <td class="text-center">
            <span class="status-{{ $order->status }}">
                {{ strtoupper($order->status) }}
            </span>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="11" style="text-align: center;">No orders found for the selected period</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  @if($orders->count() > 0)
    <div style="page-break-before: always; margin-top: 20px;">
        <h3 style="font-size: 12px; margin-bottom: 10px;">Detailed Item Summary</h3>
        <table>
            <thead>
                <tr>
                    <th width="40%">Item Type</th>
                    <th width="30%" class="text-center">Total Quantity</th>
                    <th width="30%" class="text-center">Number of Orders</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $itemSummary = [];
                    foreach($orders as $order) {
                        if ($order->orderDetails) {
                            foreach($order->orderDetails as $detail) {
                                if (!isset($itemSummary[$detail->item_type])) {
                                    $itemSummary[$detail->item_type] = [
                                        'total_quantity' => 0,
                                        'order_count' => 0,
                                        'orders' => []
                                    ];
                                }
                                $itemSummary[$detail->item_type]['total_quantity'] += $detail->quantity;
                                if (!in_array($order->id, $itemSummary[$detail->item_type]['orders'])) {
                                    $itemSummary[$detail->item_type]['orders'][] = $order->id;
                                    $itemSummary[$detail->item_type]['order_count']++;
                                }
                            }
                        }
                    }
                @endphp
                
                @foreach($itemSummary as $itemType => $data)
                <tr>
                    <td>{{ ucfirst(str_replace('_', ' ', $itemType)) }}</td>
                    <td class="text-center">{{ $data['total_quantity'] }} pcs</td>
                    <td class="text-center">{{ $data['order_count'] }}</td>
                </tr>
                @endforeach
                
               
                <tr style="border-top: 2px solid #333;">
                    <td><strong>Total</strong></td>
                    <td class="text-center"><strong>{{ array_sum(array_column($itemSummary, 'total_quantity')) }} pcs</strong></td>
                    <td class="text-center"><strong>{{ $totalOrders }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    
    <div style="margin-top: 15px;">
        <h3 style="font-size: 12px; margin-bottom: 10px;">Order Status Summary</h3>
        <table>
            <thead>
                <tr>
                    <th width="50%">Status</th>
                    <th width="25%" class="text-center">Count</th>
                    <th width="25%" class="text-center">Percentage</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $statusCounts = $orders->groupBy('status')->map->count();
                    $totalOrdersCount = $orders->count();
                @endphp
                
                @foreach($statusCounts as $status => $count)
                <tr>
                    <td>
                        <span class="status-{{ $status }}">
                            {{ strtoupper($status) }}
                        </span>
                    </td>
                    <td class="text-center">{{ $count }}</td>
                    <td class="text-center">{{ number_format(($count / $totalOrdersCount) * 100, 1) }}%</td>
                </tr>
                @endforeach
                
                <tr style="border-top: 2px solid #333;">
                    <td><strong>Total</strong></td>
                    <td class="text-center"><strong>{{ $totalOrdersCount }}</strong></td>
                    <td class="text-center"><strong>100%</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
  @endif

  <div class="footer">
    Hillside Laundry Shop Sales Report | Generated automatically by the system
  </div>
</body>
</html>