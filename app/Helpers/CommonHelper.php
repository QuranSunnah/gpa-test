<?php

declare(strict_types=1);

namespace App\Helpers;

class CommonHelper
{
    public static function decodeJson($value): array
    {
        return $value ? json_decode($value, true) : [];
    }

    public static function splitFullName(?string $fullName): array
    {
        return collect(explode(' ', trim($fullName ?? '')))
            ->filter()
            ->pipe(fn($parts) => [
                $parts->slice(0, -1)->implode(' ') ?: null,
                $parts->last() ?: null,
            ]);
    }
}
