<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\models\ActivityLog;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('inventory', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'low_stock_threshold' => 'required|integer|min:0',
            'dosage' => 'required|numeric|min:0',
            'dosage_unit' => 'required|string|in:mg,ml,g,kg,L,IU,mcg,mg/mL,g/mL',
            'remboursable' => 'boolean',
            'total_quantity' => 'required|integer|min:0',
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->low_stock_threshold = $request->low_stock_threshold;
        $product->dosage = $request->dosage;
        $product->dosage_unit = $request->dosage_unit;
        $product->remboursable = $request->has('remboursable');
        $product->total_quantity = $request->total_quantity;
        $product->save();

        // Log the activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => auth()->user()->name . ' added a new product: ' . $product->name,
        ]);
        
        return redirect()->route('inventory.index')->with('success', 'Product added successfully');
    }

    public function show(Product $product)
    {
        $product->load('lots'); 
    return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'low_stock_threshold' => 'required|integer|min:0',
            'dosage' => 'nullable|string',
            'dosage_unit' => 'nullable|string',
            'remboursable' => 'required|boolean',
        ]);

        $product->update($validated);
        // Log the activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => auth()->user()->name . ' edited product: ' . $product->name,
        ]);
        return redirect()->route('product.show', $product->id)->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete(); 
        // Log the activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => auth()->user()->name . ' deleted product: ' . $product->name,
        ]);
        return redirect()->route('inventory.index')->with('success', 'Product deleted successfully');
    }

    public function searchProducts(Request $request)
    {
        $query = $request->input('query');

        // Fetch products matching the query
        $products = Product::where('name', 'LIKE', "%{$query}%")->get();

        return response()->json($products);
    }
}
