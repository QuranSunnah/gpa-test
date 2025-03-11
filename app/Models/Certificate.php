<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    protected $fillable = [
        'uuid',
        'user_id',
        'course_id',
        'status',
    ];

    protected static function booted(): void
    {
        static::created(function ($certificate) {
            Course::where('id', $certificate->course_id)->increment('total_completion');

            User::where('id', $certificate->user_id)->increment('total_course_completion');
        });
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function template()
    {
        return $this->hasOne(CertificateTemplate::class, 'course_id', 'course_id');
    }
}
