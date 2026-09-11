<?php

namespace App\Notifications;

use App\Models\CommandeClient;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReceptionCommandeClientNotification extends Notification
{
    use Queueable;

    public function __construct(
        public CommandeClient $commandeClient
    ) {}

    public function via($notifiable): array
    {
        return ['database']; // Votre système en BDD
    }

    /**
     * Les données enregistrées en base et lues par votre script JS
     */
    public function toArray($notifiable): array
    {
        return [
            'titre'    => 'Reception Commande !',
            'message'  => "La Commande #{$this->commandeClient->reference} de " . number_format($this->commandeClient->montant_ttc, 0, ',', ' ') . " FCFA". " a été réceptionnée par le client.",
            'icone'    => 'shopping_bag',
            'couleur'  => 'emerald',      
            'lien'     => null,
        ];
    }
}
