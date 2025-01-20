<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\Filter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enroll extends Model
{
    use Filter;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'course_id',
        'start_at',
        'end_at',
        'status',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
