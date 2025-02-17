<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResouce;
use App\Models\Event;
use App\Repositories\EventsRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    use ApiResponse;

    public function __construct(private EventsRepository $repository)
    {
    }

    public function index(Request $request): JsonResponse
    {
        return $this->paginateResponse($this->repository->paginate($request->query->all()));
    }

    public function show(string $slug): JsonResponse
    {
        $event = Event::where('slug', $slug)->with('gallery')->firstOrFail();

        return $this->response(new EventResouce($event), __('Events details'));
    }
}
