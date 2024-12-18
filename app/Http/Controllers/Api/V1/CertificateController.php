<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CertificateResouce;
use App\Services\CertificateService;
use App\Traits\ApiResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request;

class CertificateController extends Controller
{
    use ApiResponse;

    protected $certificateService;

    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    public function getCertificateList(Request $request)
    {
        $certificates = $this->certificateService->getAllCertificates();

        return $this->response(CertificateResouce::collection($certificates));
    }

    public function downloadCertificate($certId)
    {
        $filePath = $this->certificateService->getCertificateFile($certId);

        return $filePath;
        // return response()->download($filePath);
    }
}
