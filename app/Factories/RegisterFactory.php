<?php

declare(strict_types=1);

namespace App\Factories;

use Illuminate\Http\Response as Res;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class RegisterFactory
{
    public static function create(string $providerName)
    {
        $class = "\\App\Factories\\Register\\" . ucfirst($providerName) . 'Registration';
        if (class_exists($class)) {
            return new $class();
        } else {
            throw new ServiceUnavailableHttpException(null, 'Service unavailable', null, Res::HTTP_SERVICE_UNAVAILABLE);
        }
    }
}
