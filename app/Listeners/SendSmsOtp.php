<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\RegistrationProcessed;
use App\GatewayProviders\Sms\GP;
use App\Helpers\OtpHelper;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSmsOtp implements ShouldQueue
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
    public function handle(RegistrationProcessed $event): void
    {
        OtpHelper::sendSmsOtp(new GP(), $event->phone, $event->otp);
    }
}
