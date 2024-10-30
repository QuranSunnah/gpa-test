<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\PartnerRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PartnerController extends Controller
{
    public function __construct(private PartnerRepository $repository)
    {
    }

    public function index(Request $request)
    {
        return response()->json(['data' => $this->repository->paginate($request->query->all())], Response::HTTP_OK);
    }
}
