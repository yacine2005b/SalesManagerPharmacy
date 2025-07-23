<?php
namespace App\Strategies;

use App\Models\Lot;
use App\Models\Product;
use App\Models\Prescription;

class InsuranceSaleStrategy implements SaleStrategyInterface
{
    public function processSale(array $cart, $request, $activeSession)
    {
        $prescriptionId = session('prescription_id');
        $coverageType = session('coverage_type', 'partial'); // or get from $request
        $totalAmount = 0;
        $coveredAmount = 0;
        $items = [];

        foreach ($cart as $item) {
            $lineTotal = $item['price'] * $item['quantity'];
            $discount = 0;
            if (($item['is_reimbursable'] ?? false) && $coverageType) {
                if ($coverageType === 'full') {
                    $discount = $lineTotal;
                } elseif ($coverageType === 'partial') {
                    $discount = $lineTotal * 0.8;
                }
            }
            $coveredAmount += $discount;
            $totalAmount += $lineTotal;

            $items[] = [
                'product_id' => $item['product_id'],
                'lot_id' => $item['lot_id'],
                'quantity' => $item['quantity'],
                'original_price' => $item['price'],
                'final_price' => $item['price'] - ($discount / max(1, $item['quantity'])), // per unit
            ];

            // Update stock
            $lot = Lot::find($item['lot_id']);
            if ($lot) {
                $lot->quantity = max(0, $lot->quantity - $item['quantity']);
                $lot->save();
            }
            $product = Product::find($item['product_id']);
            if ($product) {
                $product->total_quantity = max(0, $product->total_quantity - $item['quantity']);
                $product->save();
            }
        }

        // Mark prescription as processed if exists
        if ($prescriptionId) {
            $prescription = Prescription::find($prescriptionId);
            if ($prescription) {
                $prescription->status = 'processed';
                $prescription->save();
            }
        }

        return [
            'sale' => [
                'sale_session_id' => $activeSession->id,
                'type' => 'insurance',
                'prescription_id' => $prescriptionId,
                'coverage_type' => $coverageType,
                'total_amount' => $totalAmount,
                'covered_amount' => $coveredAmount,
                'patient_pays' => $totalAmount - $coveredAmount,
                'status' => 'completed',
            ],
            'items' => $items,
        ];
    }
}