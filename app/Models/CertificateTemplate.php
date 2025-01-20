<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CertificateTemplate extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function layout(): BelongsTo
    {
        return $this->belongsTo(CertificateLayout::class, 'certificate_layout_id');
    }

    public function getSettingsAttribute($value): array
    {
        return $value ? json_decode($value, true) : [];
    }
}
