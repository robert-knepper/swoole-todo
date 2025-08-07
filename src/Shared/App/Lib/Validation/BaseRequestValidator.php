<?php

namespace App\Shared\App\Lib\Validation;

abstract class BaseRequestValidator
{
    protected static function isValidStr($value, int $from, int $to): bool
    {
        if (is_string($value)) {
            $len = strlen($value);
            return $len > $from && $len < $to;
        }
        return false;
    }

    protected static function isValidUnsignedInt($value): bool
    {
        return is_numeric($value) && $value > 0 && $value <= 4_294_967_295;
    }

    protected static function isValidBool($value): bool
    {
        return is_bool($value);
    }


}