<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\Product;

class OrderController extends Controller
{
    public function index()
    {
        $bakeryId = session('bakery_id');

        $customers = Customer::where('bakery_id', $bakeryId)->get();
        $products  = Product::where('bakery_id', $bakeryId)->get();

        $orders = Order::with(['customer', 'items', 'payments'])
                    ->where('bakery_id', $bakeryId)
                    ->orderByDesc('id')
                    ->get();

        return view('orders', [
            'customers'    => $customers,
            'products'     => $products,
            'orders'       => $orders,
            'editingOrder' => null,
        ]);
    }

    public function store(Request $request)
    {
        $bakeryId = session('bakery_id');

        $validated = $request->validate([
            'customer_id'            => ['required', 'exists:customers,id'],
            'order_type'             => ['required', 'in:Delivery,Pickup'],
            'delivery_date'          => ['required', 'date'],
            'delivery_time'          => ['nullable'],
            'delivery_city'          => ['nullable', 'string'],
            'delivery_address'       => ['nullable', 'string'],
            'special_instructions'   => ['nullable', 'string'],
            'payment_method'         => ['nullable', 'string'],
            'advance_payment'        => ['nullable', 'numeric', 'min:0'],
            'order_status'           => ['required', 'string'],

            'items'                     => ['required', 'array', 'min:1'],
            'items.*.product_id'       => ['nullable', 'exists:products,id'],
            'items.*.item_name'        => ['required', 'string', 'max:150'],
            'items.*.description'      => ['nullable', 'string'],
            'items.*.quantity'         => ['required', 'integer', 'min:1'],
            'items.*.unit_price'       => ['required', 'numeric', 'min:0'],
        ]);

        // Calculate the total on the server, never trust the client's number
        $totalAmount = 0;
        foreach ($validated['items'] as $item) {
            $totalAmount += $item['quantity'] * $item['unit_price'];
        }

        $advance = $validated['advance_payment'] ?? 0;

        if ($advance > $totalAmount) {
            return back()->withErrors(['advance_payment' => 'Advance payment cannot be more than the total amount.'])->withInput();
        }

        // Work out the payment status automatically
        if ($advance <= 0) {
            $paymentStatus = 'Pending';
        } elseif ($advance < $totalAmount) {
            $paymentStatus = 'Partially Paid';
        } else {
            $paymentStatus = 'Paid';
        }

        DB::transaction(function () use ($validated, $bakeryId, $totalAmount, $advance, $paymentStatus) {
            $order = Order::create([
                'bakery_id'             => $bakeryId,
                'customer_id'           => $validated['customer_id'],
                'order_number'          => 'TEMP',
                'order_type'            => $validated['order_type'],
                'delivery_date'         => $validated['delivery_date'],
                'delivery_time'         => $validated['delivery_time'] ?? null,
                'delivery_city'         => $validated['delivery_city'] ?? null,
                'delivery_address'      => $validated['delivery_address'] ?? null,
                'special_instructions'  => $validated['special_instructions'] ?? null,
                'total_amount'          => $totalAmount,
                'payment_method'        => $validated['payment_method'] ?? null,
                'payment_status'        => $paymentStatus,
                'order_status'          => $validated['order_status'],
            ]);

            // Now that we have the ID, build the real order number
            $order->order_number = 'ORD-' . str_pad($order->id, 4, '0', STR_PAD_LEFT);
            $order->save();

            foreach ($validated['items'] as $item) {
                OrderItem::create([
                    'order_id'    => $order->id,
                    'product_id'  => $item['product_id'] ?? null,
                    'item_name'   => $item['item_name'],
                    'description' => $item['description'] ?? null,
                    'quantity'    => $item['quantity'],
                    'unit_price'  => $item['unit_price'],
                    'amount'      => $item['quantity'] * $item['unit_price'],
                ]);
            }

            if ($advance > 0) {
                Payment::create([
                    'order_id'     => $order->id,
                    'amount'       => $advance,
                    'method'       => $validated['payment_method'] ?? 'Cash',
                    'payment_date' => now()->toDateString(),
                    'status'       => $advance >= $totalAmount ? 'Paid' : 'Partial',
                    'notes'        => 'Advance payment at order creation',
                ]);
            }
        });

        return redirect('/orders');
    }

    public function edit($id)
    {
        $bakeryId = session('bakery_id');

        $order = Order::with('items')
                    ->where('id', $id)
                    ->where('bakery_id', $bakeryId)
                    ->firstOrFail();

        $customers = Customer::where('bakery_id', $bakeryId)->get();
        $products  = Product::where('bakery_id', $bakeryId)->get();
        $orders    = Order::with(['customer', 'items', 'payments'])
                        ->where('bakery_id', $bakeryId)
                        ->orderByDesc('id')
                        ->get();

        return view('orders', [
            'customers'    => $customers,
            'products'     => $products,
            'orders'       => $orders,
            'editingOrder' => $order,
        ]);
    }

    public function update(Request $request, $id)
    {
        $bakeryId = session('bakery_id');

        $order = Order::where('id', $id)
                    ->where('bakery_id', $bakeryId)
                    ->firstOrFail();

        $validated = $request->validate([
            'customer_id'            => ['required', 'exists:customers,id'],
            'order_type'             => ['required', 'in:Delivery,Pickup'],
            'delivery_date'          => ['required', 'date'],
            'delivery_time'          => ['nullable'],
            'delivery_city'          => ['nullable', 'string'],
            'delivery_address'       => ['nullable', 'string'],
            'special_instructions'   => ['nullable', 'string'],
            'payment_method'         => ['nullable', 'string'],
            'order_status'           => ['required', 'string'],

            'items'                     => ['required', 'array', 'min:1'],
            'items.*.product_id'       => ['nullable', 'exists:products,id'],
            'items.*.item_name'        => ['required', 'string', 'max:150'],
            'items.*.description'      => ['nullable', 'string'],
            'items.*.quantity'         => ['required', 'integer', 'min:1'],
            'items.*.unit_price'       => ['required', 'numeric', 'min:0'],
        ]);

        $totalAmount = 0;
        foreach ($validated['items'] as $item) {
            $totalAmount += $item['quantity'] * $item['unit_price'];
        }

        // Recalculate payment status based on payments already made against this order
        $alreadyPaid = $order->payments()->sum('amount');
        if ($alreadyPaid <= 0) {
            $paymentStatus = 'Pending';
        } elseif ($alreadyPaid < $totalAmount) {
            $paymentStatus = 'Partially Paid';
        } else {
            $paymentStatus = 'Paid';
        }

        DB::transaction(function () use ($order, $validated, $totalAmount, $paymentStatus) {
            $order->update([
                'customer_id'           => $validated['customer_id'],
                'order_type'            => $validated['order_type'],
                'delivery_date'         => $validated['delivery_date'],
                'delivery_time'         => $validated['delivery_time'] ?? null,
                'delivery_city'         => $validated['delivery_city'] ?? null,
                'delivery_address'      => $validated['delivery_address'] ?? null,
                'special_instructions'  => $validated['special_instructions'] ?? null,
                'total_amount'          => $totalAmount,
                'payment_method'        => $validated['payment_method'] ?? null,
                'payment_status'        => $paymentStatus,
                'order_status'          => $validated['order_status'],
            ]);

            // Simplest way to keep items in sync: wipe the old ones and insert the new set
            $order->items()->delete();

            foreach ($validated['items'] as $item) {
                OrderItem::create([
                    'order_id'    => $order->id,
                    'product_id'  => $item['product_id'] ?? null,
                    'item_name'   => $item['item_name'],
                    'description' => $item['description'] ?? null,
                    'quantity'    => $item['quantity'],
                    'unit_price'  => $item['unit_price'],
                    'amount'      => $item['quantity'] * $item['unit_price'],
                ]);
            }
        });

        return redirect('/orders');
    }

    public function destroy($id)
    {
        $order = Order::where('id', $id)
                    ->where('bakery_id', session('bakery_id'))
                    ->firstOrFail();

        $order->delete();

        return redirect('/orders');
    }

    public function calendar(Request $request)
    {
        $bakeryId = session('bakery_id');

        $month = (int) ($request->query('month') ?? now()->month);
        $year  = (int) ($request->query('year') ?? now()->year);

        $monthStart = \Carbon\Carbon::create($year, $month, 1)->startOfMonth();
        $monthEnd   = $monthStart->copy()->endOfMonth();

        $orders = Order::with(['customer', 'items'])
                    ->where('bakery_id', $bakeryId)
                    ->whereBetween('delivery_date', [$monthStart->toDateString(), $monthEnd->toDateString()])
                    ->get();

        $nextOrder = Order::with(['customer', 'payments'])
                    ->where('bakery_id', $bakeryId)
                    ->where('delivery_date', '>=', now()->toDateString())
                    ->orderBy('delivery_date')
                    ->first();

        return view('calendar', [
            'orders'     => $orders,
            'month'      => $month,
            'year'       => $year,
            'monthStart' => $monthStart,
            'nextOrder'  => $nextOrder,
        ]);
    }
    public function invoice($id)
    {
        $bakeryId = session('bakery_id');

        $order = Order::with(['customer', 'items', 'payments'])
                    ->where('id', $id)
                    ->where('bakery_id', $bakeryId)
                    ->firstOrFail();

        return view('invoice', [
            'order' => $order,
        ]);
    }
}
