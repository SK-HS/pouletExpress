<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogSuccessfulLogin
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
    public function handle(object $event): void
    {
        activity('auth')
            ->causedBy($event->user)
            ->withProperties([
                'session_id' => session()->getId(),
                'ip' => request()->ip(),
            ])
            ->log('Connexion à la plateforme');

    }
}
