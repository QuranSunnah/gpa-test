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
        
        $lang = $request->lang === 'bn'
            ? config('common.language.bangla')
            : config('common.language.english');

        $webPageInfo = WebPage::where([
            ['slug', $slug],
            ['status', config('common.status.active')],
            ['lang', $lang],
        ])
            ->select('components', 'lang')
            ->firstOrFail()
            ->components;

        return $this->response($webPageInfo, __('Web page info'));
        
    }
}
