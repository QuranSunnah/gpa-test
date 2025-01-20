<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\CertificatePdfData;
use App\Helpers\FileHelper;
use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CertificateService
{
    public function getCertificateFile($certId)
    {
        $certificate = Certificate::with(['template.course', 'template.layout'])
            ->where(['id' => $certId, "user_id" => Auth::id()])
            ->first();

        if (!$certificate) {
            throw new \Exception(__('Certificate Not Found.'), Response::HTTP_NOT_FOUND);
        }

        $template = $certificate->template;
        $course = $certificate->course;
        $layout = $template?->layout ?? null;

        if (!$template || !$course || !$layout) {
            throw new \Exception(__('Missing required data for certificate.'), Response::HTTP_NOT_FOUND);
        }

        [$width, $height] = [$layout->width, $layout->height];

        $base64Image = FileHelper::fetchBase64Image($layout->path ?? '');

        $pdfData = new CertificatePdfData(
            $template,
            $certificate,
            $course,
            $layout,
            $width,
            $height,
            $base64Image
        );

        return $this->createCertificatePdf($pdfData);
    }

    protected function createCertificatePdf(CertificatePdfData $pdfData): Response
    {
        $studentName = Auth::check()
            ? Auth::user()->first_name . ' ' . Auth::user()->last_name
            : 'Guest User';

        $pdf = Pdf::loadView('templates.certificate', [
            'template' => $pdfData->template,
            'layout' => $pdfData->layout,
            'courseTitle' => $pdfData?->course?->title ?? __('No Course Found'),
            'base64Image' => $pdfData->base64Image,
            'date' => $pdfData->certificate->created_at->format('Y-m-d'),
            'studentName' => $studentName,
        ])
            ->setPaper([0, 0, $pdfData->height, $pdfData->width], 'landscape')
            ->setOption([
                'fontDir' => public_path('/fonts'),
                'fontCache' => public_path('/fonts'),
            ]);

        return $pdf->download('certificate.pdf');
    }
}
