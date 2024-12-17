<?php

namespace App\DTO;

use App\Models\CertificateTemplate;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\CertificateLayout;

class CertificatePdfData
{
    public CertificateTemplate $template;
    public Certificate $certificate;
    public Course $course;
    public CertificateLayout $layout;
    public int $width;
    public int $height;
    public string $base64Image;

    public function __construct(
        CertificateTemplate $template,
        Certificate $certificate,
        Course $course,
        CertificateLayout $layout,
        int $width,
        int $height,
        string $base64Image
    ) {
        $this->template = $template;
        $this->certificate = $certificate;
        $this->course = $course;
        $this->layout = $layout;
        $this->width = $width;
        $this->height = $height;
        $this->base64Image = $base64Image;
    }
}
