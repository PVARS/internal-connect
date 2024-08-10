<?php

namespace App\Rules;

use App\Utils\Constants;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmailRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL) || strlen($value) > Constants::EMAIL_MAX_LENGTH) {
            $fail('The :attribute field must be a valid email address.');
        }
    }
}
