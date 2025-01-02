<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonProgress extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'lessons',
        'is_passed',
        'total_marks'
    ];
}
