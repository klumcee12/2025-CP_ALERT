<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class DeviceIdFormat implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Device ID should be alphanumeric, dashes, underscores only
        // Length: 3-50 characters
        if (!preg_match('/^[A-Za-z0-9_-]{3,50}$/', $value)) {
            $fail('The :attribute must be 3-50 characters and contain only letters, numbers, dashes, and underscores.');
        }
    }
}

