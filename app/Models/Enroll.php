<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\Filter;
use Illuminate\Database\Eloquent\Model;

class Enroll extends Model
{
    use Filter;

    protected $fillable = [
        'user_id',
        'course_id',
        'start_at',
        'end_at',
        'status',
    ];
}
