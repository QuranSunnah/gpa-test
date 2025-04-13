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
        $lessons = collect(json_decode($this->lesson_progress, true, 512, JSON_THROW_ON_ERROR))
            ->map(function ($lesson) {
                return [
                    'id' => $lesson['id'],
                    'is_passed' => $lesson['is_passed'],
                ];
            });

        return [
            'id' => $this->id,
            'is_passed' => $this->is_passed,
            'total_marks' => (int) $this->total_marks,
            'lessons' => $lessons,
        ];
    }
}
