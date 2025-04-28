<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\WebPageRequest;
use App\Repositories\WebPageRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class WebPageController extends Controller
{
    use ApiResponse;

    public function __construct(private WebPageRepository $repository)
    {
    }

    public function show(WebPageRequest $request, string $slug): JsonResponse
    {
        $lang = $request->lang === 'bn' ? config('common.language.bangla') : config('common.language.english');

        return $this->response($this->repository->findDetails($lang, $slug), __('Web page info'));
    }
}
