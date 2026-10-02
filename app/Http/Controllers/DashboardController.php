<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bakery;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        $bakeryId = session('bakery_id');

        $bakery = Bakery::find($bakeryId);

        $today = now()->toDateString();
        $weekEnd = now()->addDays(7)->toDateString();

        $todaysOrdersCount = Order::where('bakery_id', $bakeryId)
                                ->whereDate('delivery_date', $today)
                                ->count();

        $upcomingOrdersCount = Order::where('bakery_id', $bakeryId)
                                ->whereBetween('delivery_date', [$today, $weekEnd])
                                ->count();

        $allOrders = Order::with(['customer', 'items', 'payments'])
                        ->where('bakery_id', $bakeryId)
                        ->get();

        $pendingOrders = $allOrders->filter(function ($order) {
            return ($order->total_amount - $order->payments->sum('amount')) > 0;
        });

        $pendingAmount = $pendingOrders->sum(function ($order) {
            return $order->total_amount - $order->payments->sum('amount');
        });

        $totalRevenue = Payment::whereHas('order', function ($query) use ($bakeryId) {
                            $query->where('bakery_id', $bakeryId);
                        })->sum('amount');

        $totalCustomers = Customer::where('bakery_id', $bakeryId)->count();

        $recentOrders = Order::with(['customer', 'items'])
                            ->where('bakery_id', $bakeryId)
                            ->orderByDesc('id')
                            ->take(4)
                            ->get();

        $upcomingDeliveries = Order::with(['customer', 'items', 'payments'])
                            ->where('bakery_id', $bakeryId)
                            ->where('delivery_date', '>=', $today)
                            ->orderBy('delivery_date')
                            ->take(3)
                            ->get();

        return view('dashboard', [
            'bakery'              => $bakery,
            'todaysOrdersCount'   => $todaysOrdersCount,
            'upcomingOrdersCount' => $upcomingOrdersCount,
            'pendingAmount'       => $pendingAmount,
            'pendingOrdersCount'  => $pendingOrders->count(),
            'totalRevenue'        => $totalRevenue,
            'totalCustomers'      => $totalCustomers,
            'recentOrders'        => $recentOrders,
            'upcomingDeliveries'  => $upcomingDeliveries,
        ]);
    }
}