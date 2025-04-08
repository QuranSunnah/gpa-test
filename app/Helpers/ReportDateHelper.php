<?php

declare(strict_types=1);

namespace App\Helpers;

use Carbon\Carbon;

class ReportDateHelper
{
    public static function parse(array $dates): ?array
    {
        $dates = collect($dates)->flatMap(fn ($d) => explode(' ', $d))
            ->filter()->values();

        $start = $end = Carbon::yesterday();

        if ($dates->count() === 1) {
            $start = $end = Carbon::parse($dates[0]);
        } elseif ($dates->count() === 2) {
            [$start, $end] = $dates->map(fn ($d) => Carbon::parse($d))->sort()->values();

            if ($start->diffInMonths($end) > 2) {
                return null;
            }
        }

        return [
            'start' => $start->toDateString(),
            'end' => $end->toDateString(),
        ];
    }
}
