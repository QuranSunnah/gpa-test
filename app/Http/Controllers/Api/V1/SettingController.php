<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\SettingsRepository;
use App\Traits\ApiResponse;

class SettingController extends Controller
{
    use ApiResponse;

    public function __construct(private SettingsRepository $repository)
    {
    }

    public function index()
    {
        return $this->response($this->repository->getSettings());
    }
}
