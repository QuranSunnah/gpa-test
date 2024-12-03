<?php

declare(strict_types=1);

namespace App\Factories;

use App\AuthProvider\Interfaces\Authenticable;
use Illuminate\Http\Response as Res;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException as UnavailableException;

class AuthFactory
{
    public static function create(string $providerName): Authenticable
    {
        $class = "\\App\AuthProvider\\" . ucfirst($providerName) . 'AuthProvider';
        if (class_exists($class)) {
            return new $class();
        } else {
            throw new UnavailableException(null, 'Auth provider unavailable', null, Res::HTTP_SERVICE_UNAVAILABLE);
        }
    }
}
