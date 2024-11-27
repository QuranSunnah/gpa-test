<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\RegistrationProcessed;
use App\Helpers\OtpHelper;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOtpNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(RegistrationProcessed $event): void
    {
        OtpHelper::sendOtp($event->email, $event->phone, $event->otp);
    }
}
