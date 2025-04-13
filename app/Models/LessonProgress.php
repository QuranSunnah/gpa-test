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
        'status',
        'type',
    ];

    protected static function booted(): void
    {
        static::created(function ($enroll) {
            User::where('id', $enroll->user_id)->increment('total_enrollments');

            Course::where('id', $enroll->course_id)->increment('total_enrollments');
        });
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
