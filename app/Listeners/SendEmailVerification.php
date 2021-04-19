<?php

namespace App\Listeners;

use App\Mail\RegistrationConfirmation;

class SendEmailVerification
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $event->user->sendEmailVerificationNotification();
    }
}
