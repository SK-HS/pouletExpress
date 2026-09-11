<?php

namespace App\Notifications;

use App\Models\CommandeClient;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CommandeLivreeNotification extends Notification
{
    use Queueable; // envoyée en tâche de fond, jamais en synchrone

    public function __construct(protected CommandeClient $commande) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'titre'   => 'Commande livrée',
            'message' => "Votre commande #{$this->commande->reference} a été livrée. Pensez à confirmer la réception.",
            'lien'    => route('Suivi-last-Commande', $this->commande->id),
            'icone'   => 'check_circle',
            'couleur' => 'emerald',
        ];
    }
}