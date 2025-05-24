<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
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

        return redirect()->route('pos.normal')->with('success', 'Product added to cart.');
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
            return redirect()->route('pos.insurance')->with('success', 'Cart updated successfully.');
        }

        return redirect()->route('pos.normal')->with('success', 'Cart updated successfully.');
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
        return redirect()->route('pos.normal')->with('success', 'Item removed from cart.');
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
        // Save insurance data in the session
        session([
            'coverage_type' => $request->input('coverage_type'),
            'shifa_card_number' => $request->input('shifa_card_number'),
        ]);

        // Recalculate discounts for all items in the cart
        $cart = session('cart', []);
        $coverageType = session('coverage_type', null);
        $isInsuranceSale = true; // Assume insurance sale is active

        foreach ($cart as &$item) {
            $product = Product::find($item['product_id']);
            $discount = 0;

            if ($product && $product->remboursable && $isInsuranceSale) {
                if ($coverageType === 'full') {
                    $discount = $item['price'] * $item['quantity']; // Full coverage means 100% discount
                } elseif ($coverageType === 'partial') {
                    $discount = ($item['price'] * $item['quantity']) * 0.5; // Partial coverage means 50% discount
                }
            }

            $item['discount'] = $discount; // Update the discount in the cart
        }

        // Save the updated cart back to the session
        session(['cart' => $cart]);

        return redirect()->back()->with('success', 'Insurance details and discounts updated successfully!');
    }

    public function switchSaleType(Request $request)
    {
        $isInsuranceSale = $request->input('is_insurance', false);
        $coverageType = $request->input('coverage_type', null);

        // Update the session data for the sale type
        session([
            'is_insurance' => $isInsuranceSale,
            'coverage_type' => $coverageType,
        ]);

        // Update the cart items based on the new sale type
        $cart = session('cart', []);
        foreach ($cart as &$item) {
            $product = \App\Models\Product::find($item['product_id']);
            $isReimbursable = $product ? $product->remboursable : false;

            // Recalculate the discount based on the new sale type
            $discount = 0;
            if ($isInsuranceSale && $isReimbursable) {
                if ($coverageType === 'full') {
                    $discount = $item['price'] * $item['quantity']; // Full coverage
                } elseif ($coverageType === 'partial') {
                    $discount = ($item['price'] * $item['quantity']) * 0.5; // Partial coverage
                }
            }

            $item['discount'] = $discount;
        }

        // Save the updated cart back to the session
        

        return redirect()->back()->with('success', 'Sale type updated successfully.');
    }
    public function recalculateDiscounts()
    {
        $cart = session('cart', []);
        $coverageType = session('coverage_type', null);
        $isInsuranceSale = session('is_insurance', false);

        foreach ($cart as &$item) {
            $product = Product::find($item['product_id']);
            $discount = 0;

            if ($product && $product->remboursable && $isInsuranceSale) {
                if ($coverageType === 'full') {
                    $discount = $item['price'] * $item['quantity']; // Full coverage means 100% discount
                } elseif ($coverageType === 'partial') {
                    $discount = ($item['price'] * $item['quantity']) * 0.5; // Partial coverage means 50% discount
                }
            }

            $item['discount'] = $discount; // Update the discount in the cart
        }

        // Save the updated cart back to the session
        session(['cart' => $cart]);
    }
    public function smartAdd(Request $request)
    {
        $query = $request->input('barcode_or_search');

        $lot = \App\Models\Lot::where('barcode', $query)->first();
        if ($lot) {
            $product = $lot->product;
        } else {
            // Only find products that have at least one lot
            $product = \App\Models\Product::where('name', 'ILIKE', "%$query%")
                ->whereHas('lots')
                ->first();
            if (!$product) {
                $lot = \App\Models\Lot::where('batch_number', $query)->first();
                if ($lot) {
                    $product = $lot->product;
                }
            }
        }

        if (empty($product)) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        // If found by lot, use that lot; otherwise, use the first lot of the product (if any)
        if (empty($lot) && isset($product)) {
            $lot = $product->lots()->first();
        }

        // Add to cart logic with duplicate check
        $cart = session('cart', []);
        $found = false;
        foreach ($cart as &$item) {
            if ($item['product_id'] == $product->id && $item['lot_id'] == ($lot ? $lot->id : null)) {
                $item['quantity'] += 1;
                $found = true;
                break;
            }
        }
        unset($item);

        if (!$found) {
            $cart[] = [
                'product_name' => $product->name,
                'product_id' => $product->id,
                'lot_id' => $lot ? $lot->id : null,
                'price' => $lot ? $lot->price : ($product->price ?? 0),
                'quantity' => 1,
                'discount' => 0,
                'is_reimbursable' => $product->remboursable,
            ];
        }

        session(['cart' => $cart]);

        return redirect()->back()->with('success', 'Product added to cart.');
    }
}
