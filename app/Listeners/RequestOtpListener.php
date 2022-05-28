<?php

namespace App\Listeners;

use App\Events\RequestOtpEvent;
use App\Notifications\OtpVerificationNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RequestOtpListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param RequestOtpEvent $event
     * @return void
     */
    public function handle(RequestOtpEvent $event): void
    {
        $event->user->notify(new OtpVerificationNotification($event->otpVerification));
    }
}
