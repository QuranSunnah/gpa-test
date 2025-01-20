<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonProgress extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'lessons',
        'is_passed',
        'total_marks',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
