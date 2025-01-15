<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Lesson;
use App\Models\Section;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    public function run()
    {
        Lesson::truncate();

        $lessons = [];
        $sections = Section::all();

        foreach ($sections as $section) {
            for ($i = 1; $i <= rand(3, 7); ++$i) {
                $isQuiz = rand(0, 1);
                $contentableType = $isQuiz
                    ? config('common.contentable_type.quiz') : config('common.contentable_type.lesson');
                $contentableId = $isQuiz ? rand(1, 3) : null;

                $lessons[] = [
                    'title' => "Lesson $i for Section $section->id",
                    'section_id' => $section->id,
                    'course_id' => $section->course_id,
                    'contentable_type' => $contentableType,
                    'contentable_id' => $contentableId,
                    'duration' => rand(300, 900),
                    'media_info' => json_encode([
                        'type' => 'video',
                        'url' => 'https://www.youtube.com/watch?v=PYFXrbOXSV4',
                    ]),
                    'order' => $i,
                    'summary' => "Summary for Lesson $i in Section $section->id",
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Lesson::insert($lessons);
    }
}
