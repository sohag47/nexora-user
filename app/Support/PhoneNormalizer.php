<?php

namespace App\Support;

class PhoneNormalizer
{
    public static function normalize(string $value): string
    {
        $value = str_replace([' ', '-'], '', $value);

        if (str_starts_with($value, '+880')) {
            return '0'.substr($value, 4);
        }

        if (str_starts_with($value, '880')) {
            return '0'.substr($value, 3);
        }

        return $value;
    }
}
