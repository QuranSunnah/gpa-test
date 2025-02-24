<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\Filter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;
    use SoftDeletes;
    use Filter;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'gender',
        'fathers_name',
        'mothers_name',
        'blood_group',
        'dob',
        'religion',
        'images',
        'address',
        'nationality',
        'academic_status',
        'institute_id',
        'institute_name',
        'identification_type',
        'identification_number',
        'social_links',
        'designation',
        'about_yourself',
        'biography',
        'last_login',
        'settings',
        'last_otp',
        'otp_created_at',
        'is_verified',
        'verified_by',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $appends = ['full_name', 'gp_id'];

    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function getGpIdAttribute(): string
    {
        return 'GP' . str_pad((string) $this->id, 6, '0', STR_PAD_LEFT);
    }

    public function getSocialLinksAttribute($value): array
    {
        return $value ? json_decode($value, true) : [];
    }

    public function getImagesAttribute($value): array
    {
        return $value ? json_decode($value, true) : [];
    }
}
