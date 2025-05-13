<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\ActivityLog;

use App\Models\SaleSession;
use App\Models\Lot;
 
class SaleController extends Controller
{
  

    public function index()
    {
        // Fetch all sale sessions with their associated user
        $saleSessions = SaleSession::with('user')->latest()->get();
    
        return view('salesHistory', compact('saleSessions'));
    }
    
    public function saleDetails(SaleSession $saleSession)
    {
        // Fetch all sales associated with the given session
        $sales = $saleSession->sales()->with('saleItems')->get();
    
        return view('sales.details', compact('saleSession', 'sales'));
    }
    public function destroy(Sale $sale)
    {
        $sale->delete();
        
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => auth()->user()->name . ' deleted a sale with amount of: ' . $sale->total_amount,
        ]); 
        return redirect()->route('sales.history')->with('success', 'Sale deleted successfully.');
    }
   public function show($id)
    {
        $sale = Sale::with('saleItems.product')->findOrFail($id);

        return view('sales.show', compact('sale'));
    }
}