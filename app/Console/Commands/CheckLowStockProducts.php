<?php
//
//namespace App\Console\Commands;
//
//use App\Models\Product;
//use App\Models\Order;
//use App\Models\Supplier;
//use App\Models\User; // Import User model
//use Illuminate\Console\Command;
//use Illuminate\Support\Facades\Notification;
//use App\Notifications\OrderStatusUpdated;
//
//class CheckLowStockProducts extends Command
//{
//    protected $signature = 'stock:check';
//    protected $description = 'Check product stock levels and generate orders if necessary';
//
//    public function handle()
//    {
//        $products = Product::whereColumn('quantity', '<=', 'reorder_level')->get();
//
//        if ($products->isEmpty()) {
//            $this->info('No low stock products found.');
//            return;
//        }
//
//        foreach ($products as $product) {
//            $this->createOrder($product);
//        }
//
//        $this->info('Stock levels checked and orders created if necessary.');
//    }
//
//    protected function createOrder($product)
//    {
//        $supplier = Supplier::first();
//        if (!$supplier) {
//            $this->error('No supplier found for product: ' . $product->name);
//            return;
//        }
//
//        $orderQuantity = $this->calculateOrderQuantity($product);
//
//        $order = Order::create([
//            'product_id' => $product->id,
//            'supplier_id' => $supplier->id,
//            'quantity' => $orderQuantity,
//            'total_price' => $product->price * $orderQuantity,
//            'status' => 'pending',
//        ]);
//
//        $warehouseManager = User::where('role', 'warehouse_manager')->first();
//        if ($warehouseManager) {
//            Notification::send($warehouseManager, new OrderStatusUpdated($order, 'created'));
//        }
//
//        $this->info('Order created for product: ' . $product->name . ' with quantity: ' . $orderQuantity);
//    }
//
//    protected function calculateOrderQuantity($product)
//    {
//        return max(10, $product->reorder_level - $product->quantity);
//    }
//}

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Order;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderStatusUpdated;

class CheckLowStockProducts extends Command
{
    protected $signature = 'stock:check';
    protected $description = 'Check product stock levels and generate orders if necessary';

    public function handle()
    {
        $products = Product::whereColumn('quantity', '<=', 'reorder_level')->get();

        if ($products->isEmpty()) {
            $this->info('No low stock products found.');
            return;
        }

        foreach ($products as $product) {
            $this->createOrder($product);
        }

        $this->info('Stock levels checked and orders created if necessary.');
    }

    protected function createOrder($product)
    {
        $supplier = $product->suppliers()->first();

        if (!$supplier) {
            $this->error('No supplier found for product: ' . $product->name);
            return;
        }

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
            $this->info('Notification sent to warehouse manager for order of product: ' . $product->name);
        }
        $this->info('Order Created for Product: ' . $product->name . ' with quantity: ' . $orderQuantity);
    }

    protected function calculateOrderQuantity($product)
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

        $this->info('Product stock and reorder level updated for product: ' . $product->name);
    }
}
