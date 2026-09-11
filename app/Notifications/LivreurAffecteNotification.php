<?php

namespace App\Notifications;

use App\Models\CommandeClient;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LivreurAffecteNotification extends Notification
{
    use Queueable;

    public function __construct(protected CommandeClient $commande) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'titre'   => 'Livreur affecté',
            'message' => "Un livreur a pris en charge votre commande #{$this->commande->reference}.",
            'lien'    => route('Suivi-last-Commande', $this->commande->id),
            'icone'   => 'local_shipping',
            'couleur' => 'blue',
        ];
    }
}