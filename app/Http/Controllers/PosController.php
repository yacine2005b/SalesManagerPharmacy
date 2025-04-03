<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Lot;

class PosController extends Controller
{
    public function index()
    {
        $products = Product::with('lots')->get();
        $cart = session('cart', []); 
        return view('pos', compact('products', 'cart'));
    }

    public function insuranceSale()
    {
        $products = Product::with('lots')->get();
        $cart = session('cart', []); 
        $isInsuranceSale = true; 
        return view('sales.insuranceSale', compact('products', 'cart', 'isInsuranceSale'));
    }

    public function addToCart(Request $request)
    {
        $cart = session('cart', []);
        $productName = $request->input('product_name');
        $productId = $request->input('product_id');
        $lotId = $request->input('lot_id');
        $price = $request->input('price');
        $quantity = $request->input('quantity', 1);

        // Fetch the product to check if it is reimbursable
        $product = Product::find($productId);
        $isReimbursable = $product ? $product->remboursable : false;

        // Save insurance data in the session
        $coverageType = $request->input('coverage_type', session('coverage_type', null));
        $shifaCardNumber = $request->input('shifa_card_number', session('shifa_card_number', null));

        session([
            'coverage_type' => $coverageType,
            'shifa_card_number' => $shifaCardNumber,
        ]);

        // Calculate discount based on coverage type and reimbursable status
        $isInsuranceSale = $request->input('is_insurance', false);
        $discount = 0;
        if ($isReimbursable && $isInsuranceSale) { // Ensure discount is only calculated for insurance sales
            if ($coverageType === 'full') {
                $discount = $price * $quantity; // Full coverage means 100% discount
            } elseif ($coverageType === 'partial') {
                $discount = ($price * $quantity) * 0.5; // Partial coverage means 50% discount
            }
        }

        // Check if the product-lot combination already exists in the cart
        $existingItemKey = collect($cart)->search(function ($item) use ($productId, $lotId) {
            return $item['product_id'] == $productId && $item['lot_id'] == $lotId;
        });

        if ($existingItemKey !== false) {
            // Update the quantity and discount if the item already exists
            $cart[$existingItemKey]['quantity'] += $quantity;
            $cart[$existingItemKey]['discount'] += $discount;
        } else {
            $cart[] = [
                'product_name' => $productName,
                'product_id' => $productId,
                'lot_id' => $lotId,
                'price' => $price,
                'quantity' => $quantity,
                'discount' => $discount, // Always initialize the discount key
                'is_reimbursable' => $isReimbursable, // Save reimbursable status
            ];
        }

        session(['cart' => $cart]);

        // Redirect based on the sale type
        if ($isInsuranceSale) {
            return redirect()->route('insurance.sale')->with('success', 'Product added to cart.');
        }

        return redirect()->route('pos.index')->with('success', 'Product added to cart.');
    }

    public function updateCart(Request $request)
    {
        $cart = session('cart', []);
        $index = $request->input('index');
        $quantity = $request->input('quantity');

        if (isset($cart[$index])) {
            $cart[$index]['quantity'] = $quantity; // Update the quantity

            // Recalculate the discount based on the coverage type and reimbursable status
            $coverageType = session('coverage_type', null);
            $price = $cart[$index]['price'];
            $isReimbursable = $cart[$index]['is_reimbursable'] ?? false; // Default to false if not set
            $isInsuranceSale = $request->input('is_insurance', false);
            $discount = 0;

            if ($isReimbursable && $isInsuranceSale) { // Ensure discount is only calculated for insurance sales
                if ($coverageType === 'full') {
                    $discount = $price * $quantity; // Full coverage means 100% discount
                } elseif ($coverageType === 'partial') {
                    $discount = ($price * $quantity) * 0.5; // Partial coverage means 50% discount
                }
            }

            $cart[$index]['discount'] = $discount; // Update the discount
            session(['cart' => $cart]); // Save the updated cart
        }

        // Redirect based on the sale type
        if ($isInsuranceSale) {
            return redirect()->route('insurance.sale')->with('success', 'Cart updated successfully.');
        }

        return redirect()->route('pos.index')->with('success', 'Cart updated successfully.');
    }

    public function removeFromCart(Request $request)
    {
        $cart = session('cart', []);
        $index = $request->input('index');

        if (isset($cart[$index])) {
            unset($cart[$index]); // Remove the item from the cart
            session(['cart' => array_values($cart)]); // Reindex the cart
        }
        $isInsuranceSale = $request->input('is_insurance', false);
        if ($isInsuranceSale) {
            return redirect()->route('insurance.sale')->with('success', 'Cart updated successfully.');
        }
        return redirect()->route('pos.index')->with('success', 'Item removed from cart.');
    }

    public function checkout(Request $request)
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('pos.index')->with('error', 'Cart is empty.');
        }

        // Validate stock availability
        foreach ($cart as $item) {
            $lot = Lot::find($item['lot_id']);
            if (!$lot || $lot->quantity < $item['quantity']) {
                return redirect()->route('pos.index')->with('error', 'Insufficient stock for one or more items.');
            }
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

        // Log debug information
        \Log::info('Is Insurance Sale: ' . $isInsuranceSale);
        \Log::info('Coverage Type: ' . session('coverage_type'));
        \Log::info('Total Amount: ' . $totalAmount);
        \Log::info('Covered Amount: ' . $coveredAmount);
        \Log::info('Patient Pays: ' . $patientPays);

        // Create a new sale record
        $sale = Sale::create([
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

        // Clear the cart after checkout
        session()->forget('cart');

        return redirect()->route($isInsuranceSale ? 'insurance.sale' : 'pos.index')
            ->with('success', $isInsuranceSale ? 'Insurance sale completed successfully!' : 'Checkout successful!');
    }

    public function updateInsurance(Request $request)
    {
        session([
            'coverage_type' => $request->input('coverage_type'),
            'shifa_card_number' => $request->input('shifa_card_number'),
        ]);

        return redirect()->back()->with('success', 'Insurance details updated successfully.');
    }

    public function saveInsuranceData(Request $request)
    {
        $coverageType = $request->input('coverage_type');
        $shifaCardNumber = $request->input('shifa_card_number');

        // Save insurance data in the session
        session([
            'coverage_type' => $coverageType,
            'shifa_card_number' => $shifaCardNumber,
        ]);

        return redirect()->back()->with('success', 'Insurance data saved successfully.');
    }
}
