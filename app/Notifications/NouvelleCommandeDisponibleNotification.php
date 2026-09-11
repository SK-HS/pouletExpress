<?php

namespace App\Notifications;

use App\Models\CommandeLivreur;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NouvelleCommandeDisponibleNotification extends Notification
{
    use Queueable;

    public function __construct(protected CommandeLivreur $commandeLivreur, protected float $distanceKm) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $commande = $this->commandeLivreur->commandeClient;

        return [
            'titre'   => 'Nouvelle course disponible',
            'message' => "Commande #{$commande->reference} disponible à " . round($this->distanceKm, 1) . " km — " . number_format($commande->montant_ttc, 0, ',', ' ') . ' FCFA.',
            'lien'    => route('Commande-Livreur'), // renvoie vers le dashboard où la course apparaîtra via polling
            'icone'   => 'local_shipping',
            'couleur' => 'amber',
        ];
    }
}