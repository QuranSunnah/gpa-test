<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\WebPageRequest;
use App\Models\WebPage;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class WebPageController extends Controller
{
    use ApiResponse;

    public function index(WebPageRequest $request, string $slug): JsonResponse
    {
        try {
            $langMapping = [
                'en' => config('common.language.english'),
                'bn' => config('common.language.bangla'),
            ];

            $webPageInfo = WebPage::where('slug', $slug)
                ->select('settings', 'lang')
                ->where('status', config('common.status.active'))
                ->where('lang', $langMapping[$request->lang] ?? config('common.language.english'))
                ->firstOrFail()
                ->components;

            return $this->response($webPageInfo, __('Web page info'));
        } catch (\Exception $e) {
            throw new \Exception(__('Web page not found'), Response::HTTP_NOT_FOUND);
        }
    }
}
