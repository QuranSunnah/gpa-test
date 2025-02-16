<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory;
    use Filter;
    use SoftDeletes;

    protected $filterAbleFields = [
        'is_highlighted',
    ];

    public function gallery(): BelongsTo
    {
        return $this->belongsTo(Gallery::class);
    }

    public function buildSearchQuery(Builder $query, string $searchStr): Builder
    {
        if (!empty($searchStr)) {
            $query->where('title', 'like', '%' . $searchStr . '%');
        }

        return $query;
    }
}
