<?php

declare(strict_types=1);

namespace App\Factories;

use Symfony\Component\Translation\Exception\NotFoundResourceException;

class LessonProgressFactory
{
    public static function create(int $contentableType)
    {
        $keyValue = array_flip(config('common.contentable_type'))[$contentableType];

        $class = "\\App\Factories\LessonProgress\\" . ucfirst($keyValue);
        if (class_exists($class)) {
            return app($class);
        } else {
            throw new NotFoundResourceException('Invalid Request: Factory not found.');
        }
    }
}
