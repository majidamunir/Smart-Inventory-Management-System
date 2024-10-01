<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->role === 'supplier') {
            $supplier = Supplier::where('user_id', $user->id)->first();
            if ($supplier) {
                $orders = Order::with('product')->where('supplier_id', $supplier->id)->get();
            } else {
                $orders = collect();
            }
        } else {
            $orders = Order::with('product', 'supplier')->get();
        }
        return view('order.index', compact('orders'));
    }

    public function approveOrder(Order $order)
    {
        $order->update(['status' => 'approved']);

//        $this->sendNotification(User::where('role', 'supplier')->get(), $order, 'approved');

        return redirect()->route('orders.index')
            ->with('success', 'Order approved successfully.');
    }

    public function disapproveOrder(Order $order)
    {
        $order->update(['status' => 'disapproved']);

//        $this->sendNotification(User::where('role', 'procurement_officer')->get(), $order, 'disapproved');

        return redirect()->route('orders.index')
            ->with('success', 'Order disapproved successfully.');
    }

    public function supplierShipOrder(Request $request, Order $order)
    {
        $request->validate([
            'tracking_info' => 'nullable|alpha_num|max:255',
        ]);

        if ($order->status !== 'accepted') {
            return redirect()->back()->with('error', 'Order must be accepted before shipping.');
        }

        $trackingInfo = $request->input('tracking_info') ?? $this->generateRandomTrackingInfo();

        $order->update([
            'status' => 'shipped',
            'tracking_info' => $trackingInfo,
        ]);

        // $this->sendNotification(User::where('role', 'admin')->get(), $order, 'shipped');

        return redirect()->route('orders.index')
            ->with('success', 'Order shipped successfully.');
    }

    public function markAsDelivered(Order $order)
    {
        if ($order->status !== 'shipped') {
            return redirect()->route('orders.index')
                ->with('error', 'Only shipped orders can be marked as delivered.');
        }

        $order->update(['status' => 'delivered']);
        $product = $order->product;
        $product->increment('quantity', $order->quantity);
        $product->increment('reorder_level', round($order->quantity * 0.1));

//        $this->sendNotification(User::where('role', 'supplier')->get(), $order, 'delivered');

        return redirect()->route('orders.index')
            ->with('success', 'Order marked as delivered and product stock updated.');
    }

    public function cancelOrder(Order $order)
    {
        $order->update(['status' => 'cancelled']);

//        $this->sendNotification(User::where('role', 'supplier')->get(), $order, 'cancelled');

        return redirect()->route('orders.index')
            ->with('success', 'Order cancelled successfully.');
    }

    public function show($id)
    {
        $order = Order::find($id);
        return view('order.show', compact('order'));
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')
            ->with('success', 'Order deleted successfully.');
    }

    public function acceptOrderBySupplier(Order $order)
    {
        $order->update(['status' => 'accepted']);

        $supplier = $order->supplier;
        if ($supplier && $supplier->status !== 'accepted') {
            $supplier->update(['status' => 'accepted']);
        }

//        $this->sendNotification(User::where('role', 'admin')->get(), $order, 'accepted');

        return redirect()->route('orders.index')
            ->with('success', 'Order accepted by the supplier. They can now proceed to shipment.');
    }

    public function rejectOrderBySupplier(Order $order)
    {
        $order->update(['status' => 'rejected']);

        $supplier = $order->supplier;
        if ($supplier && $supplier->status !== 'rejected') {
            $supplier->update(['status' => 'rejected']);
        }

//        $this->sendNotification(User::where('role', 'admin')->get(), $order, 'rejected');

        return redirect()->route('orders.index')
            ->with('success', 'Order rejected by the supplier.');
    }

    private function sendNotification($users, $order, $status)
    {
        if ($users) {
            Notification::send($users, new OrderStatusUpdated($order, $status));
        }
    }

    private function generateRandomTrackingInfo()
    {
        return strtoupper(bin2hex(random_bytes(4)));
    }
}

