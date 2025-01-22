<?php

declare(strict_types=1);

namespace App\DTO;

use App\Helpers\FileHelper;
use App\Models\Certificate;
use App\Models\CertificateLayout;
use App\Models\CertificateTemplate;
use App\Models\Course;
use Illuminate\Http\Response;

class CertificatePdfData
{
    public ?CertificateTemplate $template;
    public Certificate $certificate;
    public ?Course $course;
    public ?CertificateLayout $layout;
    public string $base64Image;

    public function __construct(Certificate $certificate)
    {
        $this->certificate = $certificate;
        $this->template = $certificate->template;
        $this->course = $certificate->course;
        $this->layout = $this->template?->layout;

        if (!$this->template || !$this->course || !$this->layout) {
            throw new \Exception(__('Missing required data for certificate.'), Response::HTTP_NOT_FOUND);
        }

        $this->base64Image = FileHelper::fetchBase64Image($this->layout->path ?? '');
    }
}
