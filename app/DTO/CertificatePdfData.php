<?php

declare(strict_types=1);

namespace App\DTO;

use App\Helpers\FileHelper;
use App\Models\Certificate;
use Illuminate\Http\Response;

class CertificatePdfData
{
    public Certificate $certificate;
    public array $settings;
    public int $height;
    public int $width;
    public string $base64Image;
    public string $date;

    public function __construct(Certificate $certificate)
    {
        $this->certificate = $certificate;

        if (!$certificate->settings) {
            throw new \Exception(__('Missing required data for certificate.'), Response::HTTP_NOT_FOUND);
        }
        $this->settings = json_decode($certificate->settings, true);

        $this->height = $certificate?->height ?? 0;
        $this->width = $certificate?->width ?? 0;
        $this->base64Image = FileHelper::fetchBase64Image($certificate?->path ?? '');
    }
}
