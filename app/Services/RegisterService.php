<?php

declare(strict_types=1);

namespace App\Services;

use App\Factories\RegisterFactory;
use App\Http\Requests\RegisterRequest;
use App\Services\Interfaces\RegisterServiceInterface;

class RegisterService implements RegisterServiceInterface
{
    public function register(RegisterRequest $request): array
    {
        $instance = RegisterFactory::create($request->post('provider'));

        return $instance->execute($request);
    }
}
