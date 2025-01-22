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
    public function getCertificateFile($id)
    {
        $certificate = Certificate::with(['template.course', 'template.layout'])
            ->where(['id' => $id, 'user_id' => Auth::id()])
            ->firstOrFail();


        $pdfData = new CertificatePdfData($certificate);

        return $this->createCertificatePdf($pdfData);
    }

    protected function createCertificatePdf(CertificatePdfData $pdfData): Response
    {
        try {
            $studentName = Auth::check()
                ? Auth::user()->first_name . ' ' . Auth::user()->last_name
                : 'Guest User';

            $pdf = Pdf::loadView('templates.certificate', [
                'pdfData' => $pdfData,
                'studentName' => $studentName,
            ])
                ->setPaper([0, 0, $pdfData->layout->height, $pdfData->layout->width], 'landscape')
                ->setOption([
                    'fontDir' => public_path('/fonts'),
                    'fontCache' => public_path('/fonts'),
                ]);

            return $pdf->download('certificate.pdf');
        } catch (\Exception $e) {
            throw new \Exception(__("Something went wrong."));
        }
    }
}
