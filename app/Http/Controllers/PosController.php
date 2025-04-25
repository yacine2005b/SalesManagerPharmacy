<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Lot;
use App\Models\SaleSession;
use App\Models\ActivityLog;

class PosController extends Controller
{
    public function index()
    {
        $products = Product::with('lots')->get();
        $cart = session('cart', []);

        // Retrieve the active sale session for the logged-in user
        $activeSession = SaleSession::where('user_id', auth()->id())
            ->whereNull('end_time') // Ensure the session is not ended
            ->latest()
            ->first();

        return view('pos', compact('products', 'cart', 'activeSession'));
    }

    public function insuranceSale()
    {
        $products = Product::with('lots')->get();
        $cart = session('cart', []); 
        $isInsuranceSale = true; 
        return view('pos.insuranceSale', compact('products', 'cart', 'isInsuranceSale'));
    }

   
    public function checkout(Request $request)
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('pos.index')->with('error', 'Cart is empty.');
        }

        // Get the active session
        $activeSession = SaleSession::where('user_id', auth()->id())->whereNull('end_time')->first();
        if (!$activeSession) {
            return redirect()->route('pos.index')->with('error', 'No active sale session found.');
        }

        // Calculate total amounts
        $totalAmount = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $isInsuranceSale = $request->input('is_insurance', false);

        $coveredAmount = 0;
        if ($isInsuranceSale) {
            // Calculate covered amount only for reimbursable items
            $coveredAmount = collect($cart)->sum(function ($item) {
                if ($item['is_reimbursable'] ?? false) {
                    return $item['discount'] ?? 0;
                }
                return 0;
            });
        }

        $patientPays = $totalAmount - $coveredAmount;

       
        // Create a new sale record
        $sale = Sale::create([
            'sale_session_id' => $activeSession->id, 
            'type' => $isInsuranceSale ? 'insurance' : 'normal',
            'coverage_type' => $isInsuranceSale ? session('coverage_type') : null,
            'shifa_card_number' => $isInsuranceSale ? session('shifa_card_number') : null,
            'total_amount' => $totalAmount,
            'covered_amount' => $coveredAmount,
            'patient_pays' => $patientPays,
        ]);

        // Create sale items and update lot quantities
        foreach ($cart as $item) {
            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $item['product_id'],
                'lot_id' => $item['lot_id'],
                'quantity' => $item['quantity'],
                'original_price' => $item['price'],
                'final_price' => $item['price'],
            ]);

            // Update the lot quantity
            $lot = Lot::find($item['lot_id']);
            if ($lot) {
                $lot->quantity -= $item['quantity'];
                $lot->save();
            }

            // Update the product quantity
            $product = Product::find($item['product_id']);
            if ($product) {
                $product->total_quantity -= $item['quantity'];
                $product->save();
            }
        }
        // Log the activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => auth()->user()->name .' Completed a sale with total amount: ' . $totalAmount . '.'
            
        ]);
        // Clear the cart after checkout
        session()->forget('cart');

        return redirect()->route($isInsuranceSale ? 'insurance.sale' : 'pos.index')
            ->with('success', $isInsuranceSale ? 'Insurance sale completed successfully!' : 'Checkout successful!');
    }


}
