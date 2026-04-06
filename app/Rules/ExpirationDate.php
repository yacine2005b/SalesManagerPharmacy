<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class ExpirationDate implements Rule
{
    /**
     * Check if the rule passes.
     */
    public function passes($attribute, $value)
    {
        return Carbon::parse($value)->gte(now()->addMonths(6));
    }

    /**
     * Error message.
     */
    public function message()
    {
        return 'The expiration date must be at least 6 months from today.';
    }
}
