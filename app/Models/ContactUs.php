<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    protected $fillable = [
        'gp_id',
        'full_name',
        'email',
        'phone',
        'message',
    ];
}
