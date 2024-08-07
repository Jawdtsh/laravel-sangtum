<?php

namespace App\Listeners;

use App\Events\VerifyUserEvent;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendCodeListener
{

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(VerifyUserEvent $event): void
    {

        $event->user->notify(new VerifyEmailNotification($event->user, $event->code));

    }
}
