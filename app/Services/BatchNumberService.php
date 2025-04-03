<?php

namespace App\Services;

use Illuminate\Support\Str;
use Carbon\Carbon;

class BatchNumberService
{
    /**
     * Generate a unique batch number.
     *
     * @return string
     */
    public function generateBatchNumber()
    {
        $prefix = 'BATCH'; // Custom prefix
        $timestamp = Carbon::now()->format('YmdHis'); // Current timestamp
        $random = Str::random(4); // Random string for uniqueness
        return "{$prefix}-{$timestamp}-{$random}";
    }
}