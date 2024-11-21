<?php

declare(strict_types=1);

namespace App\Services\Mailer\Interfaces;

use Illuminate\Contracts\Mail\Mailable as MailableContract;

interface MailerServiceInterface
{
    public static function send(string $to, MailableContract $mailerClass);
}
