<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\DashboardReport;
use App\Models\Lesson;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class DashboardStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:dashboard-stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache dashboard statistics in Redis';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dashboardStats = DashboardReport::selectRaw(
            'SUM(total_enrollments) as total_enrollments, SUM(total_students) as total_students'
        )->first();

        Cache::set('dashboard_stats', [
            'total_courses' => Course::whereNull('deleted_at')->count(),
            'total_lessons' => Lesson::count(),
            'total_enrollments' => $dashboardStats->total_enrollments,
            'total_students' => $dashboardStats->total_students,
        ]);

        $this->info('Dashboard statistics cached successfully.');
    }
}
