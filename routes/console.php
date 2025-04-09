<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schedule;

Schedule::command('dashboard:generate-report')->dailyAt('00:01');
Schedule::command('cache:dashboard-stats')->dailyAt('00:05');
