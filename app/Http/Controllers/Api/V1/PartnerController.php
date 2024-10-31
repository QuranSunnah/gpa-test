<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\PartnerRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ApiResponse;

class PartnerController extends Controller
{
    use ApiResponse;

    public function __construct(private PartnerRepository $repository) {}

    public function index(Request $request)
    {
        return $this->paginateResponse($this->repository->paginate($request->query->all()));
    }
}
