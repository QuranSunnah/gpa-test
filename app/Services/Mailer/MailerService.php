<?php

declare(strict_types=1);

namespace App\Services\Mailer;

use App\Services\Mailer\Interfaces\MailerServiceInterface;
use Illuminate\Contracts\Mail\Mailable as MailableContract;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailerService implements MailerServiceInterface
{
    public static function send(string $to, MailableContract $mailerClass)
    {
        try {
            Mail::to($to)->send($mailerClass);
        } catch (\Exception $e) {
            Log::error('Email send failed: ' . $e->getMessage(), ['info' => $mailerClass]);
        }
    }
}
