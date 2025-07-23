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
use App\Models\ShifaCard;
use App\Strategies\SaleContext;
use App\Strategies\NormalSaleStrategy;
use App\Strategies\PrescriptionSaleStrategy;
use App\Strategies\InsuranceSaleStrategy;

class PosController extends Controller
{
    public function index()
    {
      

        $products = Product::with('lots')->get();
        $cart = session('cart', []);
        $isInsuranceSale = false;

        // Get the active session for the current user
        $activeSession = SaleSession::where('user_id', auth()->id())
            ->whereNull('end_time')
            ->latest()
            ->first();

        return view('pos', compact('products', 'cart', 'activeSession', 'isInsuranceSale'));
    }

    public function insuranceSale()
    {
       

        $cart = session('cart', []);
        $prescriptions = Prescription::where('status', 'pending')->get();
        $isInsuranceSale = true;
        $activeSession = SaleSession::where('user_id', auth()->id())
            ->whereNull('end_time')
            ->latest()
            ->first();

        // Get the current prescription from session
        $currentPrescription = null;
        $shifaCard = null;
        if (session('current_prescription')) {
            $currentPrescription = Prescription::with('patient.shifaCard')
                ->find(session('current_prescription')['id']);
            if ($currentPrescription && $currentPrescription->patient) {
                $shifaCard = $currentPrescription->patient->shifaCard;
            }
        }

        return view('pos.insuranceSale', compact(
            'cart',
            'prescriptions',
            'isInsuranceSale',
            'activeSession',
            'shifaCard'
        ));
    }

    public function prescriptionSale()
    {
      

        $prescriptions = Prescription::where('status', 'pending')->get();
        $cart = session('cart', []);
        $isInsuranceSale = false;
        $activeSession = SaleSession::where('user_id', auth()->id())
            ->whereNull('end_time')
            ->latest()
            ->first();

        return view('pos.prescription', compact('prescriptions', 'cart', 'isInsuranceSale', 'activeSession'));
    }

    public function checkout(Request $request)
    {
        $cart = session('cart', []);
        $prescriptionId = session('prescription_id');
        $activeSession = SaleSession::where('user_id', auth()->id())->whereNull('end_time')->first();

        // Choose strategy
        $isInsuranceSale = $request->input('is_insurance', false);
        if ($isInsuranceSale) {
            $strategy = new InsuranceSaleStrategy();
        } elseif ($prescriptionId) {
            $strategy = new PrescriptionSaleStrategy();
        } else {
            $strategy = new NormalSaleStrategy();
        }

        // Delegate all sale logic to the strategy
        $saleData = $strategy->processSale($cart, $request, $activeSession);

        // Create sale and sale items from $saleData
        $sale = Sale::create($saleData['sale']);
        foreach ($saleData['items'] as $item) {
            SaleItem::create($item + ['sale_id' => $sale->id]);
        }

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => auth()->user()->name . ' completed a ' . $saleData['sale']['type'] . ' sale with total amount: ' . $saleData['sale']['total_amount'] . '.',
        ]);

        // Clear the cart and prescription session data
        session()->forget(['cart', 'prescription_id', 'current_prescription']);

        // Redirect based on sale type
        if ($saleData['sale']['type'] === 'insurance') {
            return redirect()->route('pos.insurance')->with('success', 'Insurance sale completed successfully!');
        } elseif ($saleData['sale']['type'] === 'prescription') {
            return redirect()->route('pos.prescription')->with('success', 'Prescription sale completed successfully!');
        } else {
            return redirect()->route('pos.normal')->with('success', 'Normal sale completed successfully!');
        }
    }

    public function loadPrescriptionToCart(Request $request)
    {
        $request->validate([
            'prescription_id' => 'required|exists:prescriptions,id',
            'sale_type' => 'required|in:insurance,prescription',
        ]);

        // Fetch the prescription with its medications and related products
        $prescription = Prescription::with('medications.product.lots', 'patient.shifaCard')->findOrFail($request->prescription_id);

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
            // Choose the closest to expiration (soonest) valid lot
            $lot = $product->lots
                ->where('quantity', '>', 0)
                ->where('expiration_date', '>', now())
                ->sortBy('expiration_date')
                ->first();
            $price = $lot ? $lot->price : 0;

            // Calculate discount based on coverage type and reimbursable status
            $discount = 0;
            if ($product->remboursable && session('is_insurance', false)) {
                $coverageType = session('coverage_type', null);
                if ($coverageType === 'full') {
                    $discount = $price * $medication->quantity;
                } elseif ($coverageType === 'partial') {
                    $discount = ($price * $medication->quantity) * 0.8;
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
                'doctor_name' => $prescription->doctor->name,
                'patient_name' => $prescription->patient->name,
            ],
            'prescription_id' => $prescription->id,
        ]);

        // Always redirect back to the same sale type page
        if ($request->sale_type === 'insurance') {
            return redirect()->route('pos.insurance')->with('success', 'Prescription loaded.');
        } else {
            return redirect()->route('pos.prescription')->with('success', 'Prescription loaded.');
        }
    }

    public function switchSaleType(Request $request)
{
    // Clear cart and prescription session data
    session()->forget(['cart', 'prescription_id', 'current_prescription']);

    $saleType = $request->input('sale_type'); // 'normal', 'insurance', or 'prescription'
    if ($saleType === 'insurance') {
        return redirect()->route('pos.insurance');
    } elseif ($saleType === 'prescription') {
        return redirect()->route('pos.prescription');
    } else {
        return redirect()->route('pos.normal');
    }
}
}
