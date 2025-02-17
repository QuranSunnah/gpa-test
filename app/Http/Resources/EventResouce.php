<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResouce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->slug,
            'button_title' => $this->button_title,
            'button_url' => $this->button_url,
            'description' => $this->description,
            'banner' => $this->banner,
            'date' => $this->date,
            'gallery' => $this->gallery ? $this->gallery?->images : [],
        ];
    }
}
