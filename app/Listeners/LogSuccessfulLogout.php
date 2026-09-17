<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Auth\Events\Logout;

class LogSuccessfulLogout
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
        if ($event->user) {
            activity('auth')
                ->causedBy($event->user)
                ->withProperties([
                    'session_id' => session()->getId(),
                ])
                ->log('Déconnexion de la plateforme');
        }
    }
}
