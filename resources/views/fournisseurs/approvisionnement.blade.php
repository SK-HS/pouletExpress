@extends('layouts.fournisseur.main')
@section('content')

<main class="lg:ml-64 p-margin-mobile pb-28 md:p-margin-desktop space-y-8 animate-in fade-in duration-500">
    
    <!-- En-tête de la page -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 mb-8 md:mb-10">
       <div>
    <!-- Conteneur Flex pour aligner le Titre et le Bouton sur la même ligne -->
    <div class="flex flex-wrap items-center gap-3 md:gap-4">
        
        <h3 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-primary flex items-center gap-2">
            <span class="material-symbols-outlined text-3xl md:text-4xl">add_shopping_cart</span>
            Réapprovisionnement
        </h3>
        
        <!-- Bouton Historique (Petit format) -->
        <a href="{{ route('Approvisionnement-Historique') }}"  class="bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold flex items-center gap-1.5 hover:bg-red-700 shadow-sm transition-all active:scale-95 whitespace-nowrap mt-1">
            <span class="material-symbols-outlined text-[16px]">history</span>
            Historique
        </a>

    </div>
    
    <p class="text-on-surface-variant font-body-md mt-2">Mettez à jour vos stocks rapidement pour éviter les ruptures.</p>
</div>

        
        <!-- Petit résumé rapide -->
        <div class="flex gap-4">
            <div class="bg-status-error/10 border border-status-error/20 px-4 py-2 rounded-xl text-center">
                <span class="block text-status-error font-bold text-xl">{{ $produits->where('quantite', '<=', 0)->count() }}</span>
                <span class="text-[10px] text-status-error uppercase tracking-wider font-bold">Ruptures</span>
            </div>
            <div class="bg-secondary/10 border border-secondary/20 px-4 py-2 rounded-xl text-center">
                <span class="block text-secondary font-bold text-xl">{{ $produits->where('quantite', '>', 0)->where('quantite', '<=', 5)->count() }}</span>
                <span class="text-[10px] text-secondary uppercase tracking-wider font-bold">Stock Bas</span>
            </div>
        </div>
    </div>

    <!-- Messages de succès/erreur -->
    @if(session('success'))
        <div class="bg-status-success/10 border border-status-success text-status-success p-4 rounded-xl flex items-center gap-3">
            <span class="material-symbols-outlined">check_circle</span>
            <p class="font-bold text-sm">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Liste des produits pour réapprovisionnement -->
    <div class="space-y-4">
        @forelse($produits as $produit)

        @php
            // Logique pour les badges (adaptée pour la vue liste)
            if($produit->quantite <= 0) {
                $badgeClass = "bg-status-error/10 text-status-error border-status-error/30";
                $badgeText = "Rupture";
            } elseif($produit->quantite > 0 && $produit->quantite <= ($produit->commande_min ?? 5)) {
                $badgeClass = "bg-secondary/10 text-secondary border-secondary/30";
                $badgeText = "Stock Bas";
            } else {
                $badgeClass = "bg-status-success/10 text-status-success border-status-success/30";
                $badgeText = "En Stock";
            }
        @endphp

        <!-- Carte Produit (Ligne Horizontale) -->
        <div class="bg-surface-container-lowest rounded-2xl p-4 sm:p-5 shadow-sm border border-outline-variant hover:border-primary/40 transition-all flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6">
            
            <!-- Informations du produit -->
            <div class="flex items-center gap-4 flex-1 w-full min-w-0">
                <!-- Image -->
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl overflow-hidden bg-surface-container flex-shrink-0 border border-outline-variant">
                    @if($produit->produit?->image)
                        <img class="w-full h-full object-cover" src="{{ asset('storage/' . $produit->produit?->image) }}" alt="Image">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-on-surface-variant">
                            <span class="material-symbols-outlined opacity-50">image</span>
                        </div>
                    @endif
                </div>

                <!-- Texte -->
                <div class="min-w-0 flex-1">
                    <p class="text-[10px] uppercase tracking-wider font-bold text-primary mb-1">
                        {{ $produit->categorie?->nom ?? 'Non classé' }}
                    </p>
                    <h4 class="font-body-md-bold text-on-surface truncate text-base mb-2">
                        {{ $produit->produit?->nom }}
                    </h4>
                    
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded border text-[10px] font-extrabold uppercase tracking-wider {{ $badgeClass }}">
                            {{ $badgeText }}
                        </span>
                        <span class="text-xs text-on-surface-variant font-medium bg-surface-container-low px-2 py-0.5 rounded">
                            Stock: <strong class="text-on-surface">{{ $produit->quantite }}</strong>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Formulaire d'ajout de stock -->
            <!-- Note: Assurez-vous d'avoir une route 'Fournisseur-Reapprovisionner' dans web.php -->
            <form action="{{route('Reapprovisionner-Produit', $produit->id)}}" method="POST" class="flex items-stretch gap-2 w-full sm:w-auto mt-2 sm:mt-0 pt-4 sm:pt-0 border-t sm:border-t-0 border-outline-variant">
                @csrf
                @method('PUT')

                <div class="relative flex-1 sm:w-32">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-on-surface-variant text-[18px]">add</span>
                    <input type="number" name="quantite_ajoutee" min="1" required placeholder="Qté à ajouter" 
                           class="w-full pl-9 pr-3 py-2.5 border-2 border-outline-variant rounded-xl font-body-md focus:border-primary focus:ring-0 outline-none transition-all bg-surface-container-lowest text-on-surface text-sm font-bold placeholder:font-normal">
                </div>
                
                <button type="submit" class="flex items-center justify-center bg-primary hover:opacity-90 text-on-primary px-4 py-2.5 rounded-xl transition-all font-bold text-sm shadow-sm active:scale-95">
                    <span class="material-symbols-outlined text-[20px] sm:hidden">save</span>
                    <span class="hidden sm:inline">Ajouter</span>
                </button>
            </form>
            
        </div>

        @empty
        <!-- État vide -->
        <div class="flex flex-col items-center justify-center p-12 bg-surface-container-lowest border-2 border-dashed border-outline-variant rounded-3xl text-center">
            <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center text-primary mb-4">
                <span class="material-symbols-outlined text-4xl">inventory_2</span>
            </div>
            <h3 class="font-headline-md text-on-surface mb-2">Aucun produit à réapprovisionner</h3>
            <p class="text-on-surface-variant max-w-md">Ajoutez d'abord des produits à votre catalogue avant de pouvoir gérer leurs stocks.</p>
        </div>
        @endforelse
    </div>
</main>

@endsection
