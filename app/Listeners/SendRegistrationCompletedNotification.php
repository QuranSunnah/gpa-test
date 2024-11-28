<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\RegistrationCompleted;
use App\Mails\RegistrationConfirmationMail;
use App\Services\Mailer\MailerService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendRegistrationCompletedNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(RegistrationCompleted $event): void
    {
        MailerService::send($event->user->email, new RegistrationConfirmationMail(['userInfo' => $event->user]));
    }
}
