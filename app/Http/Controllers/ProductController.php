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
         $lowStockProducts = Product::whereColumn('total_quantity', '<=', 'low_stock_threshold')->get();
        return view('inventory', compact('products', 'lowStockProducts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'low_stock_threshold' => 'required|integer|min:0',
            'dosage' => 'required|numeric|min:0',
            'prescription'=> 'boolean',
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
        $product->prescription = $request->has('prescription');
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

public function search(Request $request)
{
    $query = $request->input('query');
    $products = Product::where('name', 'like', "%{$query}%")->get();

    // Return only the product cards grid as HTML
    return view('shared.productGrid', compact('products'))->render();
}
}
