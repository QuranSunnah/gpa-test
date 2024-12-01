<?php

declare(strict_types=1);

namespace App\Factories\Register\Interfaces;

use App\Http\Requests\RegisterRequest;

interface RegisterFactoryInterface
{
    public function execute(RegisterRequest $request): array;
}
