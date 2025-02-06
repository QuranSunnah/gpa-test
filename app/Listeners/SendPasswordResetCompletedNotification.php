<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\PasswordResetCompleted;
use App\Mails\PasswordResetConfirmationMail;
use App\Services\Mailer\MailerService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPasswordResetCompletedNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(PasswordResetCompleted $event): void
    {
        MailerService::send($event->user->email, new PasswordResetConfirmationMail(['userInfo' => $event->user]));
    }
}
