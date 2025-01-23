<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CertificateResouce;
use App\Models\Certificate;
use App\Services\CertificateService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class CertificateController extends Controller
{
    use ApiResponse;

    public function __construct(private CertificateService $service)
    {
    }

    public function getCertificateList(Request $request)
    {
        $certificates = Certificate::where('user_id', Auth::id())
            ->with('course')
            ->get();

        return $this->response(
            CertificateResouce::collection($certificates),
            __('List of certificates')
        );
    }

    public function downloadCertificate($slug)
    {
        return $this->service->getCertificateFile($slug);
    }
}
