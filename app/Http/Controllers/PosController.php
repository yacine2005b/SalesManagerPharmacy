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
use App\Models\ShifaCard; // Add at the top if not already imported

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
        $prescriptionId = session('prescription_id'); // Retrieve prescription ID from the session

        if (empty($cart)) {
            return redirect()->route('pos.prescription')->with('error', 'Cart is empty.');
        }

        // Get the active sale session
        $activeSession = SaleSession::where('user_id', auth()->id())->whereNull('end_time')->first();
        if (!$activeSession) {
            return redirect()->route('pos.prescription')->with('error', 'No active sale session found.');
        }

        // --- NEW: Assign nearest-to-expiry lot if not set ---
        foreach ($cart as &$item) {
            if (empty($item['lot_id']) && !empty($item['product_id'])) {
                $nearestLot = \App\Models\Lot::where('product_id', $item['product_id'])
                    ->where('quantity', '>', 0)
                    ->where('expiration_date', '>', now())
                    ->orderBy('expiration_date', 'asc')
                    ->first();
                if ($nearestLot) {
                    $item['lot_id'] = $nearestLot->id;
                    $item['price'] = $nearestLot->price;
                }
            }
        }
        unset($item);
        // Save the updated cart back to the session
        session(['cart' => $cart]);
        // --- END NEW ---

        // Calculate total amounts
        $totalAmount = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $isInsuranceSale = $request->input('is_insurance', false);
        $prescriptionId = session('prescription_id');

        // Correct sale type logic
        if ($isInsuranceSale) {
            $saleType = 'insurance';
        } elseif ($prescriptionId) {
            $saleType = 'prescription';
        } else {
            $saleType = 'normal';
        }

        // --- SHIFA CARD LOGIC ---
        if ($isInsuranceSale && $prescriptionId) {
            $prescription = Prescription::find($prescriptionId);
            if ($prescription && $prescription->patient) {
                $patient = $prescription->patient;
                // Only create if patient does not already have a Shifa Card
                if (!$patient->shifaCard) {
                    $validated = $request->validate([
                        'shifa_card_number' => 'required|string|max:50',
                        'coverage_type' => 'required|in:full,partial',
                        'issue_date' => 'nullable|date',
                        'expiry_date' => 'nullable|date',
                    ]);
                    ShifaCard::create([
                        'patient_id' => $patient->id,
                        'card_number' => $validated['shifa_card_number'],
                        'coverage_type' => $validated['coverage_type'],
                        'issue_date' => $validated['issue_date'] ?? null,
                        'expiry_date' => $validated['expiry_date'] ?? null,
                    ]);
                }
            }
        }
        // --- END SHIFA CARD LOGIC ---

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
            'type' => $saleType,
            'prescription_id' => $prescriptionId,
            'coverage_type' => $isInsuranceSale ? session('coverage_type') : null,
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
                $lot->quantity = max(0, $lot->quantity - $item['quantity']);
                $lot->save();
            }

            // Update the product total quantity
            $product = Product::find($item['product_id']);
            if ($product) {
                $product->total_quantity = max(0, $product->total_quantity - $item['quantity']);
                $product->save();
            }
        }

        // Mark the prescription as processed if there is a prescription
        if ($prescriptionId) {
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
}
