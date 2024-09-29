<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Order;
use Carbon\Carbon;

class ReorderService
{
    public function checkStockLevels()
    {
        $products = Product::whereColumn('quantity', '<=', 'reorder_level')->get();

        foreach ($products as $product) {
            $existingOrder = Order::where('product_id', $product->id)
                ->where('status', 'pending')
                ->first();

            if (!$existingOrder) {
                $this->createOrder($product);
            }
        }
    }

    private function createOrder(Product $product)
    {
        $orderQuantity = $product->reorder_level * 2;

        $expectedDeliveryDate = Carbon::now()->addDays(7);

        Order::create([
            'product_id' => $product->id,
            'supplier_id' => $product->supplier_id,
            'quantity' => $orderQuantity,
            'expected_delivery_date' => $expectedDeliveryDate,
            'status' => 'pending',
        ]);

        \Log::info("Order created for product {$product->name} from supplier {$product->supplier->name}.");
    }
}
