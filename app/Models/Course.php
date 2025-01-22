<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Filter;

    protected $filterAbleFields = [
        'title',
        'category_id',
        'is_top',
    ];

    protected $casts = [
        'outcomes' => 'array',
        'requirements' => 'array',
        'live_class' => 'array',
        'faq' => 'array',
        'media_info' => 'array',
        'others' => 'array',
    ];

    public function buildSearchQuery(Builder $query, string $searchStr): Builder
    {
        return !empty($searchStr)
            ? $query->where('courses.title', 'like', "%{$searchStr}%")
            : $query;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }

    public function getMediaInfoAttribute($value): array
    {
        return $value ? json_decode($value, true) : [];
    }
}
