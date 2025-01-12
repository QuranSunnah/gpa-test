<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Resource extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function lessons(): MorphMany
    {
        return $this->morphMany(Lesson::class, 'contentable');
    }
}
