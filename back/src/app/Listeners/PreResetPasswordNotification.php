<?php

namespace App\Listeners;

use App\Events\PreResetPasswordEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\PreResetPasswordMail;
use Illuminate\Support\Facades\Mail;

class PreResetPasswordNotification
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
     * @param  PreResetPasswordEvent  $event
     * @return void
     */
    public function handle(PreResetPasswordEvent $event)
    {
        Mail::to($event->email)->send(new PreResetPasswordMail($event->token));
    }
}
