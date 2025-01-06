<?php

declare(strict_types=1);

namespace App\Factories;

use Symfony\Component\Translation\Exception\NotFoundResourceException;

class LessonProgressFactory
{
    public static function create(string $contentableType)
    {
        $explodeInfo = explode('\\', $contentableType);

        $class = "\\App\Factories\LessonProgress\\" . end($explodeInfo);
        if (class_exists($class)) {
            return app($class);
        } else {
            throw new NotFoundResourceException("Invalid Request: Factory not found.");
        }
    }
}
