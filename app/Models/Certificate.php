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

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function template()
    {
        return $this->hasOne(CertificateTemplate::class, 'course_id', 'course_id');
    }
}
