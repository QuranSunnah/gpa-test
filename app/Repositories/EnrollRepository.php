<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Enroll;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Exception;
use Illuminate\Support\Facades\DB;

class EnrollRepository
{
    public function enrollStudent($courseId)
    {
        $userId = auth()->user()->id;

        $enroll = Enroll::firstOrNew(['user_id' => $userId, 'course_id' => $courseId]);

        if($enroll->exists){
            if($enroll->status == config('common.status.inactive')){
                $enroll->update([
                    'start_at' => now(),
                    'end_at' => now(),
                    'status' => config('common.status.active'),
                ]);
            }
        }
        else{
            DB::beginTransaction();
            try{    
                $lesson = Lesson::where('course_id', $courseId)
                    ->orderBy('order', 'ASC')
                    ->first();

                $lessons = [
                    [
                        'id'                => $lesson->id,
                        'contentable_id'    => $lesson->contentable_id,
                        'contentable_type'  => $lesson->contentable_type,
                        'running_time'      => 0,
                        'is_pass'           => 0,
                        'start_time'        => now(),
                        'end_time'          => now(),
                        'created_at'        => now()
                    ]
                ];
    
                LessonProgress::create([
                    'user_id'   => $userId,
                    'course_id' => $courseId,
                    'lessons'   => json_encode($lessons),
                    'is_passed' => 0,
                ]);

                $enroll->fill([
                    'user_id'   => $userId,
                    'course_id' => $courseId,
                    'start_at'  => now(),
                    'end_at'    => now(),
                    'status'    => config('common.status.active')
                ]);
                $enroll->save();

                DB::commit();

                return $enroll;
            } catch(Exception $e){
                DB::rollBack();
                throw new Exception($e->getMessage());
            }
        }
    }
}
