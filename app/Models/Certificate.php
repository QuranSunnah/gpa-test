<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{

    protected $fillable = [
        'uuid',
        'user_id',
        'course_id'
    ];
}
