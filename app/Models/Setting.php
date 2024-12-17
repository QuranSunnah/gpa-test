<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    public function getWebsiteSettings($value): ?array
    {
        return $this->decodeJson($value);
    }

    public function getSystemSettings($value): ?array
    {
        return $this->decodeJson($value);
    }

    public function getMediaSettings($value): ?array
    {
        return $this->decodeJson($value);
    }

    private function decodeJson(?string $value): ?array
    {
        \Log::info("hwo");
        return ($decoded = json_decode($value, true)) && json_last_error() === JSON_ERROR_NONE ? $decoded : null;
    }
}
