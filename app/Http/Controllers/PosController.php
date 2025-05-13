<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Lot;
use App\Models\SaleSession;
use App\Models\ActivityLog;
use App\Models\Prescription;

class PosController extends Controller
{
    public function index()
    {
        $products = Product::with('lots')->get();
        $cart = session('cart', []);
        $isInsuranceSale=false;

        // Retrieve the active sale session for the logged-in user
        $activeSession = SaleSession::where('user_id', auth()->id())
            ->whereNull('end_time') // Ensure the session is not ended
            ->latest()
            ->first();

            

        return view('pos', compact('products', 'cart', 'activeSession',"isInsuranceSale"));
    }

    public function insuranceSale()
    {
        // Set isInsuranceSale in the session
        session(['is_insurance' => true]);

        $cart = session('cart', []);
        $prescriptions = Prescription::where('status', 'pending')->get(); // Only pending prescriptions
        $isInsuranceSale = true;

        return view('pos.insuranceSale', compact('cart', 'prescriptions', 'isInsuranceSale'));
    }

    public function prescriptionSale()
    {
        // Fetch pending prescriptions
        $prescriptions = Prescription::where('status', 'pending')->get();
        $cart = session('cart', []);
        $isInsuranceSale=false;
        
        return view('pos.prescription', compact('prescriptions', 'cart',"isInsuranceSale"));
    }

    public function checkout(Request $request)
    {
        $cart = session('cart', []);
        $prescriptionId = session('prescription_id'); // Retrieve prescription ID from the session

        if (empty($cart)) {
            return redirect()->route('pos.prescription')->with('error', 'Cart is empty.');
        }

        // Get the active sale session
        $activeSession = SaleSession::where('user_id', auth()->id())->whereNull('end_time')->first();
        if (!$activeSession) {
            return redirect()->route('pos.prescription')->with('error', 'No active sale session found.');
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

        // Determine the sale type
        $saleType = $prescriptionId ? 'prescription' : ($isInsuranceSale ? 'insurance' : 'normal');

        // Create a new sale record
        $sale = Sale::create([
            'sale_session_id' => $activeSession->id,
            'type' => $saleType,
            'coverage_type' => $isInsuranceSale ? session('coverage_type') : null,
            'shifa_card_number' => $isInsuranceSale ? session('shifa_card_number') : null,
            'total_amount' => $totalAmount,
            'covered_amount' => $coveredAmount,
            'patient_pays' => $patientPays,
            'status' => 'completed',
        ]);

        // Create sale items and update lot quantities
        foreach ($cart as $item) {
            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $item['product_id'],
                'lot_id' => $item['lot_id'],
                'quantity' => $item['quantity'],
                'original_price' => $item['price'],
                'final_price' => $item['price'] - $item['discount'],
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

        // Mark the prescription as processed if it's a prescription sale
        if ($saleType === 'prescription' && $prescriptionId) {
            $prescription = Prescription::find($prescriptionId);
            if ($prescription) {
                $prescription->status = 'processed';
                $prescription->save();
            }
        }

        // Log the activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => auth()->user()->name . ' completed a ' . $saleType . ' sale with total amount: ' . $totalAmount . '.',
        ]);

        // Clear the cart and prescription ID from the session
        session()->forget(['cart', 'prescription_id', 'current_prescription']);

        return redirect()->route($saleType === 'insurance' ? 'pos.insurance' : ($saleType === 'prescription' ? 'pos.prescription' : 'pos.normal'))
            ->with('success', ucfirst($saleType) . ' sale completed successfully!');
    }

    public function loadPrescriptionToCart(Request $request)
    {
        // Validate the prescription ID
        $request->validate([
            'prescription_id' => 'required|exists:prescriptions,id',
        ]);

        // Fetch the prescription with its medications and related products
        $prescription = Prescription::with('medications.product.lots')->findOrFail($request->prescription_id);

        // Check if the prescription is already processed
        if ($prescription->status === 'processed') {
            return redirect()->back()->with('error', 'This prescription has already been processed.');
        }

        // Clear the current cart
        session()->forget(['cart', 'current_prescription', 'prescription_id']);

        // Add prescription medications to the cart
        $cart = [];
        foreach ($prescription->medications as $medication) {
            $product = $medication->product;
            $lot = $product->lots->first();
            $price = $lot ? $lot->price : 0;

            // Calculate discount based on coverage type and reimbursable status
            $discount = 0;
            if ($product->remboursable && session('is_insurance', false)) {
                $coverageType = session('coverage_type', null);
                if ($coverageType === 'full') {
                    $discount = $price * $medication->quantity; // Full coverage means 100% discount
                } elseif ($coverageType === 'partial') {
                    $discount = ($price * $medication->quantity) * 0.5; // Partial coverage means 50% discount
                }
            }

            $cart[] = [
                'product_name' => $product->name,
                'product_id' => $product->id,
                'lot_id' => $lot ? $lot->id : null,
                'price' => $price,
                'quantity' => $medication->quantity,
                'discount' => $discount,
                'is_reimbursable' => $product->remboursable ?? false,
            ];
        }

        // Save the cart and prescription details in the session
        session([
            'cart' => $cart,
            'current_prescription' => [
                'id' => $prescription->id,
                'doctor_name' => $prescription->doctor_name,
                'patient_name' => $prescription->patient_name,
            ],
            'prescription_id' => $prescription->id, // Save prescription ID for later use
        ]);

        return redirect()->route('pos.prescription')->with('success', 'Prescription loaded into cart successfully!');
    }
}
