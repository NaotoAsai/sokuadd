<?php

namespace App\Listeners;

use App\Events\PreEditEmailEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\PreEditEmailMail;
use Illuminate\Support\Facades\Mail;

class PreEditEmailNotification
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
     * @param  PreEditEmailEvent  $event
     * @return void
     */
    public function handle(PreEditEmailEvent $event)
    {
        Mail::to($event->email)->send(new PreEditEmailMail($event->token));
    }
}
