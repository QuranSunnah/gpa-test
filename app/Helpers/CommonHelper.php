<?php

declare(strict_types=1);

namespace App\Helpers;

class CommonHelper
{
    public static function decodeJson($value): array
    {
        return $value ? json_decode($value, true) : [];
    }

    public static function splitFullName(string $fullName): array
    {
        $parts = collect(explode(' ', trim($fullName ?? '')))
            ->filter()
            ->values();

        return match ($parts->count()) {
            1 => [$parts[0], null],
            default => [
                $parts->slice(0, -1)->implode(' '),
                $parts->last(),
            ],
        };
    }

    public static function buildCacheKey(string $prefix, array $filters): string
    {
        ksort($filters);
        $filterString = http_build_query($filters);

        return $prefix . ':' . md5($filterString);
    }
}
