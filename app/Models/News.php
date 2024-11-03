<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    use Filter;

    protected $filterAbleFields = [
        'is_highlighted',
    ];

    public function buildSearchQuery(Builder $query, string $searchStr): Builder
    {
        if (!empty($searchStr)) {
            $query->where('title', 'like', '%' . $searchStr . '%');
        }

        return $query;
    }
}
