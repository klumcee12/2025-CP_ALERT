<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SimNumberFormat implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // SIM number should be digits only, optionally with + prefix
        // Length: 10-15 digits (international format)
        $cleaned = preg_replace('/[^0-9+]/', '', $value);
        
        if (empty($cleaned)) {
            $fail('The :attribute must contain at least 10 digits.');
            return;
        }
        
        // Remove + if present for length check
        $digitsOnly = str_replace('+', '', $cleaned);
        
        if (strlen($digitsOnly) < 10 || strlen($digitsOnly) > 15) {
            $fail('The :attribute must be between 10 and 15 digits.');
        }
        
        // Must start with + or 0-9
        if (!preg_match('/^(\+?[0-9]{10,15})$/', $cleaned)) {
            $fail('The :attribute format is invalid. Use digits only (e.g., 09123456789 or +639123456789).');
        }
    }
}

