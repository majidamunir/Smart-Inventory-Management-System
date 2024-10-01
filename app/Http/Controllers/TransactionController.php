<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LowStockNotification;
use App\Models\User;

class TransactionController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $transactions = Transaction::with('details.product')->get();
        return view('transaction.index', compact('products', 'transactions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*' => 'required|integer|exists:products,id',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
        ]);

        $transaction = Transaction::create([
            'total_amount' => 0,
        ]);

        $totalAmount = 0;

        foreach ($request->products as $key => $productId) {
            $product = Product::find($productId);
            $quantity = $request->quantity[$key];

            if ($product->quantity < $quantity) {
                return redirect()->back()->with('error', 'Not enough stock for ' . $product->name);
            }

            $subtotal = $product->price * $quantity;

            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price_at_sale' => $product->price,
                'subtotal' => $subtotal,
            ]);

            $product->quantity -= $quantity;
            $product->save();

            $totalAmount += $subtotal;

            if ($product->quantity <= $product->reorder_level) {
                $this->triggerLowStockNotification($product);
            }
        }

        $transaction->total_amount = $totalAmount;
        $transaction->save();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction Completed Successfully!');
    }

    protected function triggerLowStockNotification(Product $product)
    {
//        $managers = User::where('role', 'procurement_officer')->get();
//        Notification::send($managers, new LowStockNotification($product));

        $orderService = app()->make('App\Services\OrderService');
        $orderService->createOrder($product);
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->details()->delete();
        $transaction->delete();
        return redirect()->route('transactions.index')
            ->with('success', 'Transaction Deleted Successfully!');
    }
}
