<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Traits\ApiResponse;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    use ApiResponse;

    public function generateCertificate(Certificate $certificate)
    {
        $template = CertificateTemplate::where('course_id', $certificate->course_id)
            ->with(['layout'])
            ->first();
        
        if($template){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, Storage::url($template?->layout->path));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $imageData = curl_exec($ch);
            curl_close($ch);

            $base64Image = base64_encode($imageData);

            $html = view('certificate.certificate', ['template' => $template, 'base64Image' => $base64Image])->render();
     
            $widht = $template->layout?->width ?? 0;
            $height = $template->layout?->height ?? 0;

            $pdf = Pdf::loadHTML($html)
                ->setPaper([0, 0, $height, $widht], 'landscape')
                ->setOption([
                    'fontDir' => public_path('/fonts'),
                    'fontCache' => public_path('/fonts'),
                ]);

            return $pdf->download();
        }

        return $this->msgResponse(
            __("Certificate Not Found"),
            Response::HTTP_NOT_FOUND
        );
    }
}
