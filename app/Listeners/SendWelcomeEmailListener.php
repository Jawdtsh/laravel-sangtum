<?php

namespace App\Listeners;

use App\Events\UserRegisteredEvent;
use App\Notifications\WelcomeEmailNotification;
//use http\Env\Response;
//use Illuminate\Contracts\Queue\ShouldQueue;
//use Illuminate\Queue\InteractsWithQueue;
//use Illuminate\Support\Facades\Mail;

class SendWelcomeEmailListener
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

    public function handle(UserRegisteredEvent $event): void
    {
        $event->user->notify(new welcomeEmailNotification());
    }
}
