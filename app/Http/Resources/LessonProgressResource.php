<?php

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
        $lessons = json_decode($this->lessons, true, 512, JSON_THROW_ON_ERROR);

        $lessons = array_map(function ($lesson) {
            return [
                'id' => $lesson['id'],
                'is_pass' => $lesson['is_pass'],
            ];
        }, $lessons);

        return [
            'id'  => $this->id,
            'is_passed' => $this->is_passed,
            'total_marks' => $this->total_marks,
            'lessons' => $lessons,
        ];
    }
}
