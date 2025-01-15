<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    public function run()
    {
        Course::truncate();

        $courses = [
            [
                'instructor_id' => 1,
                'category_id' => 1,
                'title' => 'Introduction to Programming',
                'slug' => Str::slug('Introduction to Programming'),
                'type' => 1,
                'short_description' => 'Learn the basics of programming with hands-on examples.',
                'full_description' => 'This course introduces the fundamental concepts of programming',
                'duration' => 36000,
                'outcomes' => json_encode(['Understand programming basics', 'Write simple programs']),
                'requirements' => json_encode(['Basic computer knowledge', 'Eagerness to learn']),
                'live_class' => null,
                'faq' => json_encode([
                    [
                        'question' => 'Is prior programming experience required?',
                        'answer' => 'No, this course is designed for beginners.',
                    ],
                    [
                        'question' => 'What programming language is used?',
                        'answer' => 'The course uses Python for demonstrations.',
                    ],
                ]),
                'language' => 1,
                'discount' => 10.00,
                'level' => 1,
                'pass_marks' => 50,
                'is_certification_final_exam_required' => 1,
                'media_info' => json_encode(['thumbnail' => 'intro-to-programming.jpg', 'promo_video' => 'promo.mp4']),
                'is_top' => 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'instructor_id' => 2,
                'category_id' => 2,
                'title' => 'Web Development Bootcamp',
                'slug' => Str::slug('Web Development Bootcamp'),
                'type' => 1,
                'short_description' => 'A comprehensive guide to building modern web applications.',
                'full_description' => 'Master the art of web development with this extensive bootcamp. Learn HTML, CSS',
                'duration' => 144000,
                'outcomes' => json_encode([
                    'Build dynamic websites',
                    'Understand MVC architecture',
                    'Work with databases',
                ]),
                'requirements' => json_encode(['Basic programming knowledge', 'A computer with internet access']),
                'live_class' => json_encode(['platform' => 'Zoom', 'schedule' => 'Every Monday at 7 PM']),
                'faq' => json_encode([
                    [
                        'question' => 'Do I need prior coding experience?',
                        'answer' => 'Some basic knowledge is helpful, but not required.',
                    ],
                    [
                        'question' => 'What backend language is taught?',
                        'answer' => 'We focus on PHP and Node.js.',
                    ],
                ]),
                'language' => 1,
                'discount' => 20.00,
                'level' => 2,
                'pass_marks' => 60,
                'is_certification_final_exam_required' => 1,
                'media_info' => json_encode([
                    'thumbnail' => 'web-dev-bootcamp.jpg',
                    'promo_video' => 'web-dev-promo.mp4',
                ]),
                'is_top' => 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Course::insert($courses);
    }
}
