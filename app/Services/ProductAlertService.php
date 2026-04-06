<?php

namespace App\Services;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ProductAlertService
{
    protected int $expiryThresholdMonths = 6; // configurable if needed

    /**
     * Get all alerts (low stock, near expiry, expired)
     */
    public function getAlerts(): Collection
    {
        $now = Carbon::now();
        $expiryLimit = $now->copy()->addMonths($this->expiryThresholdMonths);

        return Product::with('lots')
            ->get()
            ->flatMap(function ($product) use ($expiryLimit, $now) {
                $alerts = collect();

                // 1. Low stock check
                if ($product->total_quantity <= $product->low_stock_threshold) {
                    $alerts->push([
                        'product_id'   => $product->id,
                        'product_name' => $product->name,
                        'type'         => 'low_stock',
                        'message'      => "⚠️ {$product->name} is low on stock. Remaining: {$product->total_quantity} (Threshold: {$product->low_stock_threshold})"
                    ]);
                }

                // 2. Expired / Near expiry lots
                foreach ($product->lots as $lot) {
                    $expirationDate = Carbon::parse($lot->expiration_date);

                    if ($expirationDate->isPast()) {
                        $alerts->push([
                            'product_id'   => $product->id,
                            'product_name' => $product->name,
                            'lot_number'   => $lot->lot_number,
                            'type'         => 'expired',
                            'message'      => "❌ Lot {$lot->lot_number} of {$product->name} is already expired (Expired on {$expirationDate->format('Y-m-d')})."
                        ]);
                    } elseif ($expirationDate->lt($expiryLimit)) {
                        $alerts->push([
                            'product_id'   => $product->id,
                            'product_name' => $product->name,
                            'lot_number'   => $lot->lot_number,
                            'type'         => 'near_expiry',
                            'message'      => "⚠️ Lot {$lot->lot_number} of {$product->name} is near expiry (Expires on {$expirationDate->format('Y-m-d')})."
                        ]);
                    }
                }

                return $alerts;
            });
    }
}
