<?php

declare(strict_types=1);

namespace App\Models;

use App\Helpers\CommonHelper;
use App\Traits\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Model
{
    use SoftDeletes;
    use Filter;

    protected $filterAbleFields = [];

    public function getImagesAttribute($value): array
    {
        return CommonHelper::decodeJson($value);
    }

    public function buildSearchQuery(Builder $query, string $searchStr): Builder
    {
        if (!empty($searchStr)) {
            $query->where('title', 'like', '%' . $searchStr . '%');
        }

        return $query;
    }
}
