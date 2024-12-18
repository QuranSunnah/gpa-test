<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\CommonHelper;

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
