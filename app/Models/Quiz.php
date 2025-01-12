<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\Filter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;


class Quiz extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Filter;

    public function lessons(): MorphMany
    {
        return $this->morphMany(Lesson::class, 'contentable');
    }
}
