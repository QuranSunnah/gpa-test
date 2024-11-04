<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Lesson extends Model
{
    use HasFactory;

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function contentable(): MorphTo
    {
        return $this->morphTo();
    }
}
