<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    public function getWebsiteSettingsAttribute($value): ?array
    {
        return $this->decodeJson($value);
    }

    public function getSystemSettingsAttribute($value): ?array
    {
        return $this->decodeJson($value);
    }

    public function getMediaSettingsAttribute($value): ?array
    {
        return $this->decodeJson($value);
    }

    private function decodeJson($value): ?array
    {
        return $value ? json_decode($value, true) : null;
    }
}
