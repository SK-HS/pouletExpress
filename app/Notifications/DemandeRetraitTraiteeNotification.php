<?php

namespace App\Notifications;

use App\Models\DemandeRetrait;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DemandeRetraitTraiteeNotification extends Notification
{
    use Queueable;

    public function __construct(protected DemandeRetrait $demande) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $approuvee = $this->demande->statut === 'TRAITEE';

        return [
            'titre'   => $approuvee ? 'Retrait approuvé' : 'Retrait rejeté',
            'message' => $approuvee
                ? 'Votre demande de retrait de ' . number_format($this->demande->montant, 0, ',', ' ') . ' FCFA a été approuvée. Le virement Mobile Money arrive sous peu.'
                : "Votre demande de retrait a été rejetée. Contactez le support pour plus d\'informations.",
            'lien'    => null,
            'icone'   => $approuvee ? 'check_circle' : 'cancel',
            'couleur' => $approuvee ? 'emerald' : 'red',
        ];
    }
}