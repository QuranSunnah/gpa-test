<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "website_settings" => $this?->website_settings ?? (object) [],
            "system_settings" => $this?->system_settings ?? (object) [],
            "media" => $this?->media ?? (object) []
        ];
    }
}
