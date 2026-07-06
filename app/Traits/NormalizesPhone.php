<?php

namespace App\Traits;

use App\Support\PhoneNormalizer;

trait NormalizesPhone
{
    /**
     * Normalize the 'phone' input field before validation.
     */
    protected function normalizePhoneField(): void
    {
        if ($this->has('phone') && is_string($this->phone)) {
            $this->merge([
                'phone' => PhoneNormalizer::normalize($this->phone),
            ]);
        }
    }
}
