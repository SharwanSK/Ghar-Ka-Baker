<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Order;

class PaymentController extends Controller
{
    public function index()
    {
        $bakeryId = session('bakery_id');

        $payments = Payment::with('order.customer')
                        ->whereHas('order', function ($query) use ($bakeryId) {
                            $query->where('bakery_id', $bakeryId);
                        })
                        ->orderByDesc('id')
                        ->get();

        $orders = Order::where('bakery_id', $bakeryId)
                        ->orderByDesc('id')
                        ->get();

        return view('payments', [
            'payments' => $payments,
            'orders'   => $orders,
        ]);
    }

    public function store(Request $request)
    {
        $bakeryId = session('bakery_id');

        $validated = $request->validate([
            'order_id'     => ['required', 'exists:orders,id'],
            'amount'       => ['required', 'numeric', 'min:0.01'],
            'method'       => ['required', 'string'],
            'payment_date' => ['required', 'date'],
        ]);

        // Make sure the chosen order actually belongs to this bakery
        $order = Order::where('id', $validated['order_id'])
                    ->where('bakery_id', $bakeryId)
                    ->firstOrFail();

        // Work out whether this payment fully settles the order or not
        $alreadyPaid = $order->payments()->sum('amount');
        $newTotalPaid = $alreadyPaid + $validated['amount'];
        $status = $newTotalPaid >= $order->total_amount ? 'Paid' : 'Partial';

        Payment::create([
            'order_id'     => $order->id,
            'amount'       => $validated['amount'],
            'method'       => $validated['method'],
            'payment_date' => $validated['payment_date'],
            'status'       => $status,
        ]);

        // Keep the order's own payment_status in sync
        $order->update([
            'payment_status' => $newTotalPaid <= 0
                ? 'Pending'
                : ($newTotalPaid < $order->total_amount ? 'Partially Paid' : 'Paid'),
        ]);

        return redirect('/payments');
    }
}