<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\CertificatePdfData;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Helpers\FileHelper;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateService
{
    public function getAllCertificates()
    {
        $userId = auth()->user->id;

        return Certificate::where(['id' => $userId])
            ->with('course')
            ->get();
    }

    public function getCertificateFile($certId)
    {
        $certificate = Certificate::with(['template.course', 'template.layout'])->findOrFail($certId);

        if (!$certificate) {
            throw new \Exception("Certificate not found.");
        }

        $template = $certificate->template;
        $course = $certificate->course;
        $layout = $template?->layout;


        if (!$template || !$course || !$layout) {
            throw new \Exception("Missing required data for certificate.");
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

    protected function getCertificateTemplate($courseId): CertificateTemplate
    {
        $templateWithLayoutInfo = CertificateTemplate::where("course_id", $courseId)
            ->with('layout')
            ->first();

        return $templateWithLayoutInfo;
    }

    protected function getLayoutDimensions(CertificateTemplate $template): array
    {
        $width = $template?->layout?->width ?? 0;
        $height = $template?->layout?->height ?? 0;

        return [$width, $height];
    }

    protected function createCertificatePdf(CertificatePdfData $pdfData)
    {
        $studentName = auth()->check()
            ? auth()->user()->first_name . ' ' . auth()->user()->last_name
            : 'Guest User';

        $pdf = Pdf::loadView('templates.certificate', [
            'template' => $pdfData->template,
            'layout' => $pdfData->layout,
            'course' => $pdfData->course,
            'base64Image' => $pdfData->base64Image,
            'date' => $pdfData->certificate->created_at,
            'student' => $studentName,
        ])
            ->setPaper([0, 0, $pdfData->height, $pdfData->width], 'landscape')
            ->setOption([
                'fontDir' => public_path('/fonts'),
                'fontCache' => public_path('/fonts'),
            ]);

        return $pdf->stream('preview.pdf');
    }
}
