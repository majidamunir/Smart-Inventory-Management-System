<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['suppliers', 'category'])->get();
        $suppliers = Supplier::all();
        $categories = Category::all();
        return view('product.index', compact('products', 'suppliers', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'supplier_ids' => 'required|array',
            'supplier_ids.*' => 'exists:suppliers,id',
            'category_id' => 'required|exists:categories,id',
            'reorder_level' => 'required|integer|min:0'
        ]);

        $sku = $this->generateRandomSKU();
        $product = Product::create(array_merge($request->all(), ['sku' => $sku]));

        $product->suppliers()->attach($request->supplier_ids);
        return redirect()->route('products.index')->with('success', 'Product Added Successfully.');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'reorder_level' => 'required|integer|min:0'
        ]);

        $product->update($request->all());
        return redirect()->route('products.index')->with('success', 'Product Updated Successfully.');
    }

    public function show($product)
    {
        $product = Product::findOrFail($product);
        return view('product.view', compact('product'));
    }

    public function destroy(Product $product)
    {
        $product->suppliers()->detach();
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product Deleted Successfully.');
    }

    private function generateRandomSKU($length = 8)
    {
        return strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, $length));
    }

    public function searchProducts(Request $request)
    {
        $query = $request->input('query');
        $products = Product::where('name', 'LIKE', "%{$query}%")->get();

        return response()->json(['products' => $products]);
    }
}
