<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('products.create', compact('products'));
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

        return redirect()->route('product.index')->with('success', 'Product added successfully');
    }
}
