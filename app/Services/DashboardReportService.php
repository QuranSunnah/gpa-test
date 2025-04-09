<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\DashboardReport;
use App\Models\Enroll;
use App\Models\LessonProgress;
use App\Models\User;
use Carbon\Carbon;

class DashboardReportService
{
    public function generate(string $startDate, string $endDate): void
    {
        $report = $this->fetch($startDate, $endDate);
        $this->save($startDate, $endDate, $report);
    }

    private function fetch(string $startDate, string $endDate): array
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        return [
            'enrollments' => Enroll::selectRaw(
                'users.gender, DATE(enrolls.created_at) as date, COUNT(*) as total_enrollments'
            )
                ->join('users', 'users.id', '=', 'enrolls.user_id')
                ->whereBetween('enrolls.created_at', [$start, $end])
                ->whereNull('enrolls.deleted_at')
                ->groupBy(['date', 'gender'])
                ->get()->groupBy([fn ($item) => $item->date, fn ($item) => $item->gender]),

            'completions' => LessonProgress::selectRaw(
                'users.gender, DATE(lesson_progress.created_at) as date, COUNT(*) as total_completions'
            )
                ->join('users', 'users.id', '=', 'lesson_progress.user_id')
                ->whereBetween('lesson_progress.created_at', [$start, $end])
                ->where('lesson_progress.is_passed', config('common.confirmation.yes'))
                ->groupBy(['date', 'gender'])
                ->get()->groupBy([fn ($item) => $item->date, fn ($item) => $item->gender]),

            'students' => User::selectRaw('gender, DATE(created_at) as date, COUNT(*) as total_students')
                ->whereBetween('created_at', [$start, $end])
                ->whereNull('deleted_at')
                ->groupBy(['date', 'gender'])
                ->get()->groupBy([fn ($item) => $item->date, fn ($item) => $item->gender]),
        ];
    }

    private function save(string $startDate, string $endDate, array $reportData): void
    {
        $dateRange = collect(Carbon::parse($startDate)->daysUntil(Carbon::parse($endDate)))
            ->map(fn ($d) => $d->toDateString());

        foreach ($dateRange as $date) {
            foreach (config('common.gender') as $gender) {
                $data = [
                    'total_enrollments' => $reportData['enrollments'][$date][$gender][0]->total_enrollments ?? 0,
                    'total_completions' => $reportData['completions'][$date][$gender][0]->total_completions ?? 0,
                    'total_students' => $reportData['students'][$date][$gender][0]->total_students ?? 0,
                ];

                if (!array_filter($data)) {
                    continue;
                }

                DashboardReport::updateOrCreate(
                    ['date' => $date, 'gender' => $gender],
                    $data
                );
            }
        }
    }
}
