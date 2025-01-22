<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class FileHelper
{
    public static function fetchBase64Image(string $path): string
    {
        $url = Storage::url($path);
        $response = Http::withoutVerifying()->get($url);

        if ($response->ok()) {
            return base64_encode($response->body());
        }

        return '';
    }
}
