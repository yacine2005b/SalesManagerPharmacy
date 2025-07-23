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
    $sanitized = strtoupper(Str::slug($productName, '_'));

    $dateTime = Carbon::now()->format('Ymd_His'); 
    $random = strtoupper(Str::random(4));
    return "{$sanitized}-{$dateTime}-{$random}";
}
}