<?php

declare(strict_types=1);

namespace App\Factories\Interfaces;

use App\DTO\LessonProgressResource;

interface LessonProgressInterface
{
    public function process(LessonProgressResource $progressData);
}
