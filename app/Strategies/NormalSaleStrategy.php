<?php
namespace App\Strategies;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Lot;
use App\Models\Product;

class NormalSaleStrategy implements SaleStrategyInterface
{
    public function processSale(array $cart, $request, $activeSession)
    {
        $totalAmount = 0;
        $items = [];
        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
            $items[] = [
                'product_id' => $item['product_id'],
                'lot_id' => $item['lot_id'],
                'quantity' => $item['quantity'],
                'original_price' => $item['price'],
                'final_price' => $item['price'],
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
        return [
            'sale' => [
                'sale_session_id' => $activeSession->id,
                'type' => 'normal',
                'total_amount' => $totalAmount,
                'covered_amount' => 0,
                'patient_pays' => $totalAmount,
                'status' => 'completed',
            ],
            'items' => $items,
        ];
    }
}