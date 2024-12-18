<?php

declare(strict_types=1);

namespace App\Helpers;

class CommonHelper
{
    public static function decodeJson($value): array
    {
        return $value ? json_decode($value, true) : [];
    }
}
