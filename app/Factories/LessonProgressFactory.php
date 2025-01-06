<?php

declare(strict_types=1);

namespace App\Factories;

use Symfony\Component\Translation\Exception\NotFoundResourceException;

class LessonProgressFactory
{
    public static function create(string $contentableType)
    {
        $class = "\\App\Factories\LessonProgress\\" . self::getLastPartOfString($contentableType);
        if (class_exists($class)) {
            return app($class);
        } else {
            throw new NotFoundResourceException("Invalid Request: Factory not found.");
        }
    }

    private static function getLastPartOfString(string $string): string
    {
        $parts = explode('\\', $string);
        return end($parts);
    }
}
