<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\Filter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Lesson extends Model
{
    use HasFactory;
    use Filter;

    protected $casts = [
        'media_info' => 'array',
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

    /**
     * Get the actual class name for the contentable_type.
     */
    public function getContentableTypeAttribute($value)
    {
        return self::$contentableMap[$value] ?? null;
    }

    /**
     * Set the numeric value for the contentable_type.
     */
    public function setContentableTypeAttribute($value)
    {
        $this->attributes['contentable_type'] = array_search($value, self::$contentableMap, true);
    }

    public function contentable(): MorphTo
    {
        return $this->morphTo();
    }
}
