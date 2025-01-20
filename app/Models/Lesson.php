<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\Filter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lesson extends Model
{
    use HasFactory;
    use Filter;

    protected $casts = [
        'media_info' => 'array',
        'contentable_type' => 'integer',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    private static $contentableMap = [
        1 => Lesson::class,
        2 => Quiz::class,
        3 => Resource::class,
    ];

    public function getResolvedContentableTypeAttribute()
    {
        return self::$contentableMap[$this->attributes['contentable_type']] ?? null;
    }

    public function contentable()
    {
        return $this->morphTo(null, 'resolved_contentable_type', 'contentable_id');
    }

    public function getMediaInfoAttribute($value): array
    {
        return $value ? json_decode($value, true) : [];
    }
}
