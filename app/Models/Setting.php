<?php

declare(strict_types=1);

namespace App\Models;

use App\Helpers\CommonHelper;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public function getWebsiteSettingsAttribute($value): array
    {
        return CommonHelper::decodeJson($value);
    }

    public function getSystemSettingsAttribute($value): array
    {
        return CommonHelper::decodeJson($value);
    }

    public function getMediaAttribute($value): array
    {
        return CommonHelper::decodeJson($value);
    }
}
