<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailerService
{
    public static function send(string $to, Mailable $mailerClass)
    {
        try {
            Mail::to($to)->send($mailerClass);
        } catch (\Exception $e) {
            Log::error('Email send failed: ' . $e->getMessage(), ['info' => $mailerClass]);
        }
    }
}
