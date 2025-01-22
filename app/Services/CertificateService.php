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
    public function getCertificateFile(string $slug)
    {
        $certificate = Certificate::with(['template.course', 'template.layout'])
            ->whereHas('template.course', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })
            ->where('user_id', Auth::id())
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
