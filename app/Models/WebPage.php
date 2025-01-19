<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WebPage extends Model
{
    use SoftDeletes;

    public function getComponentsAttribute($value): array
    {
        return $value ? json_decode($value, true) : [];
    }
}
