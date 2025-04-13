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
        'status',
        'type',
        'lesson_progress',
        'is_passed',
        'total_marks',
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
