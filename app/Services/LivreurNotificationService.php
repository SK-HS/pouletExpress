<?php

namespace App\Services;

use App\Models\CommandeLivreur;
use App\Models\Livreur;
use App\Notifications\NouvelleCommandeDisponibleNotification;

class LivreurNotificationService
{
   
    protected int $rayonKm = 15;

    public function notifierLivreursProches(CommandeLivreur $commandeLivreur): void
    {
        $fournisseur = $commandeLivreur->commandeClient?->fournisseur;

        if (!$fournisseur || !$fournisseur->latitude || !$fournisseur->longitude) {
            return; // pas de position fournisseur, impossible de calculer qui est "proche"
        }

        $livreursProches = $this->trouverLivreursProches(
            (float) $fournisseur->latitude,
            (float) $fournisseur->longitude
        );

        if ($livreursProches->isEmpty()) {
            return;
        }

        foreach ($livreursProches as $livreur) {
            $livreur->notify(new NouvelleCommandeDisponibleNotification(
                $commandeLivreur,
                $livreur->distance_km
            ));
        }
    }

   
    protected function trouverLivreursProches(float $lat, float $lng)
    {
        $distanceSql = "( 6371 * acos( cos( radians($lat) ) * cos( radians( livreurs.latitude ) ) * cos( radians( livreurs.longitude ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( livreurs.latitude ) ) ) )";

        return Livreur::selectRaw("livreurs.*, {$distanceSql} AS distance_km")
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('disponible', true)
            ->having('distance_km', '<=', $this->rayonKm)
            ->orderBy('distance_km', 'asc')
            ->get();
    }
}