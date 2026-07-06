<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BdPhone implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $phone = $this->normalize($value);

        if (! preg_match('/^01[3-9]\d{8}$/', $phone)) {
            $fail('The :attribute must be exactly 11 digits and start with a valid Bangladeshi mobile prefix (e.g., 017, 019).');
        }
    }

    private function normalize(string $value): string
    {
        $value = str_replace([' ', '-'], '', $value);

        if (str_starts_with($value, '+880')) {
            $value = '0'.substr($value, 4);
        } elseif (str_starts_with($value, '880')) {
            $value = '0'.substr($value, 3);
        }

        return $value;
    }
}
