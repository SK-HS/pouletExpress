<?php

namespace App\Notifications;

use App\Models\CommandeClient;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NouvelleCommandeFournisseurNotification extends Notification
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
            'titre'   => 'Nouvelle commande',
            'message' => "Vous avez reçu une nouvelle commande #{$this->commande->reference} de " . number_format($this->commande->montant_ttc, 0, ',', ' ') . ' FCFA.',
            'lien'    => route('Suivi-Commande-Fournisseur', $this->commande->id),
            'icone'   => 'shopping_cart',
            'couleur' => 'amber',
        ];
    }
}