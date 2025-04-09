<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Helpers\ReportDateHelper;
use App\Services\DashboardReportService;
use Illuminate\Console\Command;

class GenerateDashboardReport extends Command
{
    public function __construct(protected DashboardReportService $reportService)
    {
        parent::__construct();
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashboard:generate-report {--date=* : Optional date range (e.g. 2024-04-06 2024-04-07)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dashboard report generate';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dates = ReportDateHelper::parse($this->option('date'));

        if ($dates === null) {
            $this->error('Date range cannot exceed 2 months.');

            return;
        }

        $this->reportService->generate($dates['start'], $dates['end']);
        $this->info("Reports generated from {$dates['start']} to {$dates['end']}");
    }
}
