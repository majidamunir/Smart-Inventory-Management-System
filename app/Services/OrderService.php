<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderStatusUpdated;

class OrderService
{
    public function checkLowStockAndCreateOrders()
    {
        $products = Product::whereColumn('quantity', '<=', 'reorder_level')->get();

        if ($products->isEmpty()) {
            return 'No low stock products found.';
        }

        foreach ($products as $product) {
            $order = $this->createOrder($product);
            $this->updateProductStockAndReorderLevel($order);
        }

        return 'Stock levels checked and orders created if necessary.';
    }

    public function createOrder(Product $product)
    {
        $supplier = $product->suppliers()->first();

        $orderQuantity = $this->calculateOrderQuantity($product);

        $order = Order::create([
            'product_id' => $product->id,
            'supplier_id' => $supplier->id,
            'quantity' => $orderQuantity,
            'total_price' => $product->price * $orderQuantity,
            'status' => 'pending',
        ]);

        $warehouseManager = User::where('role', 'warehouse_manager')->first();
        if ($warehouseManager) {
            Notification::send($warehouseManager, new OrderStatusUpdated($order, 'created'));
        }
        return $order;
    }

    public function calculateOrderQuantity(Product $product)
    {
        $orderQuantity = $product->reorder_level - $product->quantity;
        return max(20, $orderQuantity);
    }

    public function updateProductStockAndReorderLevel(Order $order)
    {
        $product = $order->product;
        $product->quantity += $order->quantity;
        $product->reorder_level += round($order->quantity * 0.1);
        $product->save();

        return $product;
    }
}
