@extends('layouts.master')
@section('content')

<main class="min-h-[70vh] flex items-center justify-center px-margin-mobile py-12">
    <div class="w-full max-w-2xl text-center">

        <span class="material-symbols-outlined text-7xl text-primary mb-6 block">check_circle</span>

        <h1 class="font-headline-lg text-headline-lg text-primary mb-2">Commande confirmée !</h1>
        <p class="text-on-surface-variant mb-8">
            Merci pour votre commande. Référence : <span class="font-body-md-bold text-on-surface">{{ $commande->reference }}</span>
        </p>

        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant p-6 text-left mb-8">
            <h2 class="font-headline-md text-headline-md text-primary mb-4">Détails de la commande</h2>

            <div class="space-y-3 mb-6">
                @foreach ($commande->detailCommandeClients as $detail)
                <div class="flex justify-between items-center pb-3 border-b border-outline-variant">
                    <div>
                        <p class="font-body-md-bold">{{ $detail->produitFournisseur?->produit?->nom }}</p>
                        <p class="text-sm text-on-surface-variant">Quantité : {{ $detail->quantite }}</p>
                    </div>
                    <span class="font-body-md-bold">{{ number_format($detail->montant) }} FCFA</span>
                </div>
                @endforeach
            </div>

            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-on-surface-variant">Sous-total</span>
                    <span>{{ number_format($commande->montant_ttc) }} FCFA</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-on-surface-variant">Livraison</span>
                    <span>{{ number_format($commande->montant_ttc) }} FCFA</span>
                </div>
                <div class="flex justify-between font-headline-md text-headline-md pt-3 border-t-2 border-dashed border-outline-variant">
                    <span>Total payé</span>
                    <span class="text-primary">{{ number_format($commande->montant_ttc) }} FCFA</span>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-outline-variant text-sm text-on-surface-variant space-y-1">
                <p><span class="font-body-md-bold text-on-surface">Livraison :</span> {{ $commande->quartier?->commune?->nom_commune }}, {{ $commande->quartier?->commune?->ville?->nom_ville }}</p>
                <p><span class="font-body-md-bold text-on-surface">Téléphone :</span> {{ $commande->telephone_livraison }}</p>
                <p><span class="font-body-md-bold text-on-surface">Créneau :</span> {{ $commande->creneau === 'matin' ? 'Matin (08h - 12h)' : 'Après-midi (14h - 18h)' }}</p>
                <p><span class="font-body-md-bold text-on-surface">Statut :</span> {{ $commande->statut }}</p>
            </div>
        </div>

        <a href="{{ route('Services') }}" class="inline-block bg-primary text-on-primary px-8 py-3 rounded-xl font-body-md-bold shadow-md hover:bg-primary-container transition-all">
            Retour au marché
        </a>
    </div>
</main>

@endsection
