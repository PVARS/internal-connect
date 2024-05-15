<?php

namespace App\Listeners;

use App\Mail\VerificationEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailVerificationEmail implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    public $tries = 3;

    public $timeout = 30;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event  Event
     * @return void
     */
    public function handle(object $event): void
    {
        if ($event->user instanceof MustVerifyEmail && !$event->user->hasVerifiedEmail()) {
            Mail::to($event->user->getEmailForVerification())->send(new VerificationEmail($event->user));
        }
    }

    /**
     * Back off.
     *
     * @return int[]
     */
    public function backoff(): array
    {
        return [5, 10, 15];
    }
}
