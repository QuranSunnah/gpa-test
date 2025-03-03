<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\CertificatePdfData;
use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CertificateService
{
    public function getCertificateFile($courseSlug)
    {
        $certificate = Certificate::join('courses', 'certificates.course_id', '=', 'courses.id')
            ->join('certificate_templates', 'courses.certificate_template_id', '=', 'certificate_templates.id')
            ->join('certificate_layouts', 'certificate_templates.certificate_layout_id', '=', 'certificate_layouts.id')
            ->where('certificates.user_id', Auth::id())
            ->where('courses.slug', $courseSlug)
            ->where('certificate_templates.status', config('common.status.active'))
            ->select(
                'certificates.created_at',
                'courses.title',
                'certificate_templates.settings',
                'certificate_layouts.height',
                'certificate_layouts.width',
                'certificate_layouts.path'
            )
            ->firstOrFail();

        return $this->createCertificatePdf(new CertificatePdfData($certificate));
    }

    protected function createCertificatePdf(CertificatePdfData $pdfData): Response
    {
        $studentName = Auth::check()
            ? Auth::user()->first_name . ' ' . Auth::user()->last_name
            : 'Guest User';

        $pdf = Pdf::loadView('templates.certificate', [
            'pdfData' => $pdfData,
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
