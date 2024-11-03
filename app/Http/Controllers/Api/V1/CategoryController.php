<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\CategoryRepository;
use App\Traits\ApiResponse;

class CategoryController extends Controller
{
    use ApiResponse;

    public function __construct(private CategoryRepository $repository)
    {
    }

    public function topList()
    {
        return $this->response($this->repository->getTopList());
    }

    public function report()
    {
        return $this->response($this->repository->getReport());
    }
}
