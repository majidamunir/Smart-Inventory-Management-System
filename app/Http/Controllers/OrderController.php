<?php

namespace App\Http\Controllers;

use App\Services\ReorderService;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['product', 'supplier'])->get();
        $user = auth()->user();
        $notifications = $user->notifications()->whereNull('read_at')->get();
        return view('order.index', compact('orders', 'notifications'));
    }
    public function checkReorderLevels()
    {
        app(ReorderService::class)->checkStockLevels();
        return redirect()->back()->with('success', 'Stock levels checked and orders placed if necessary.');
    }
}
