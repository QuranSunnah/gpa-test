<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_enrollments',
        'total_completions',
        'total_students',
        'gender',
        'date',
    ];

    public $timestamps = false;
}
