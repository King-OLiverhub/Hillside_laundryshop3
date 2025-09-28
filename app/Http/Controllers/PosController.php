<?php


namespace App\Http\Controllers;

use App\Models\{PosOrder, PosOrderItem, PosOrderAddon, Customer, Product, Addon, InventoryUsage, Service};
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DB;

class PosController extends Controller
{
    public function index()
    {
        $customers = Customer::where('available', true)->get();
        $services = Service::where('available', true)->get();
        $products = Product::where('available', true)->get();
        $addons = Addon::where('available', true)->get();
        
        return view('pos.index', compact('customers', 'services', 'products', 'addons'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            
            $validated = $request->validate([
                'customer_id' => 'nullable|exists:customers,id',
                'service_id' => 'required|exists:services,id',
                'service_mode' => 'required|in:drop_off,self_service,pickup,delivery,walk_in',
                'number_of_loads' => 'required|integer|min:1',
                'items' => 'array',
                'item_details.*.type' => 'sometimes|required|string',
                'items.*.product_id' => 'required_with:items|exists:products,id',
                'items.*.quantity' => 'required_with:items|integer|min:1',
                'item_details.*.notes' => 'nullable|string',
                'addons' => 'array',
                'addons.*.addon_id' => 'required_with:addons|exists:addons,id',
                'addons.*.quantity' => 'required_with:addons|integer|min:1',
            ]);

            $service = Service::find($validated['service_id']);
            $serviceAmount = $validated['number_of_loads'] * $service->price_per_load;

            $order = PosOrder::create([
                'order_number' => 'ORD-' . Str::upper(Str::random(8)),
                'customer_id' => $validated['customer_id'] ?? null,
                'service_id' => $validated['service_id'],
                'service_mode' => $validated['service_mode'],
                'number_of_loads' => $validated['number_of_loads'],
                'unit_price' => $service->price_per_load,
                'subtotal' => $serviceAmount,
                'tax' => 0,
                'total' => $serviceAmount,
                'status' => 'pending'
            ]);

            $subtotal = $serviceAmount;

            foreach ($request->input('items', []) as $itemData) {
                $product = Product::find($itemData['product_id']);
                $amount = $product->price * $itemData['quantity'];
                
                $orderItem = PosOrderItem::create([
                    'pos_order_id' => $order->id,
                    'item_name' => $product->name,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $product->price,
                    'amount' => $amount,
                ]);

                $subtotal += $amount;

                $product->decrement('quantity', $itemData['quantity']);
                if ($product->quantity <= $product->critical_quantity) {
                    $product->update(['available' => false]);
                }

                InventoryUsage::create([
                    'product_id' => $product->id,
                    'pos_order_item_id' => $orderItem->id,
                    'quantity_used' => $itemData['quantity'],
                    'performed_by' => auth()->id(),
                ]);
            }

            foreach ($request->input('addons', []) as $addonData) {
                $addon = Addon::find($addonData['addon_id']);
                $amount = $addon->unit_price * $addonData['quantity'];
                
                PosOrderAddon::create([
                    'pos_order_id' => $order->id,
                    'addon_id' => $addon->id,
                    'quantity' => $addonData['quantity'],
                    'unit_price' => $addon->unit_price,
                    'amount' => $amount,
                ]);

                $subtotal += $amount;
            }

              foreach ($request->input('item_details', []) as $itemDetail) {
                 \App\Models\OrderDetail::create([
                'pos_order_id' => $order->id,
                'item_type' => $itemDetail['type'],
                'quantity' => $itemDetail['quantity'],
                'notes' => $itemDetail['notes'] ?? null,
            ]);
        }

            $tax = $subtotal * 0.12;
            $total = $subtotal + $tax;

            $order->update([
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total
            ]);

            DB::commit();

            return response()->json([
                'success' => true, 
                'order' => $order->load('customer', 'service', 'items', 'addons'),
                'message' => 'Order created successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false, 
                'message' => 'Error creating order: ' . $e->getMessage()
            ], 500);
        }
    }

    // Add checkout method
    public function checkout(Request $request)
    {
        return $this->store($request);
    }
}