<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonProgressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $lessons = collect(json_decode($this->lessons, true, 512, JSON_THROW_ON_ERROR))
            ->map(function ($lesson) {
                return [
                    'id' => $lesson['id'],
                    'is_pass' => $lesson['is_pass'],
                ];
            });

        return [
            'id' => $this->id,
            'is_passed' => $this->is_passed,
            'total_marks' => $this->total_marks,
            'lessons' => $lessons,
        ];
    }
}
