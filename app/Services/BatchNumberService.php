<?php

namespace App\Services;

use Illuminate\Support\Str;
use Carbon\Carbon;

class BatchNumberService
{
    /**
     * Generate a unique, meaningful batch number and barcode.
     *
     * @param string $productName
     * @return string
     */
    public function generateBatchNumber($productName)
    {
        // Sanitize product name: uppercase, underscores, no special chars
        $sanitized = strtoupper(Str::slug($productName, '_'));
        $date = Carbon::now()->format('Ymd'); // e.g. 20240525
        $random = strtoupper(Str::random(4)); // e.g. AB12
        return "{$sanitized}-{$date}-{$random}";
    }
}