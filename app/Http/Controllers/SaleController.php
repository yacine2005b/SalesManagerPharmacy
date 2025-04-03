<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Lot;
 
class SaleController extends Controller
{
  

    public function index()
    {
        // Fetch all sales with their associated sale items, products, and lots
        $sales = Sale::with(['saleItems.product', 'saleItems.lot'])->get();

        return view('salesHistory', compact('sales'));
    }
    public function show(Sale $sale)
    {
        // Eager load sale items with their associated products and lots
        $sale->load(['saleItems.product', 'saleItems.lot']);

        return view('sales.details', compact('sale'));
    }
    public function destroy(Sale $sale)
    {
        $sale->delete();

        return redirect()->route('sales.history')->with('success', 'Sale deleted successfully.');
    }
   
}