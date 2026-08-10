@extends('layouts.fournisseur.main')
@section('content')

 <main class="lg:ml-64 p-margin-mobile pb-28 md:p-margin-desktop space-y-8 animate-in fade-in duration-500">
        <!-- Header Action Section -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 mb-8 md:mb-10">
            <div>
                <h3 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-primary">Inventaire des Produits</h3>
                <p class="text-on-surface-variant font-body-md">Gérez vos stocks en temps réel.</p>
            </div>
            <a href="{{route('Approvisionnement-Produit')}}" class="flex items-center justify-center gap-2 bg-primary text-on-primary px-6 md:px-8 py-3 md:py-4 rounded-xl font-body-md-bold shadow-lg hover:shadow-xl transition-all hover:scale-[1.02] active:scale-95 w-full sm:w-auto">
                <span class="material-symbols-outlined">local_shipping</span>
                <span>Approvisionnement</span>
            </a>
            {{-- <a href="{{route('Fournisseur-Produit')}}" class="flex items-center justify-center gap-2 bg-primary text-on-primary px-6 md:px-8 py-3 md:py-4 rounded-xl font-body-md-bold shadow-lg hover:shadow-xl transition-all hover:scale-[1.02] active:scale-95 w-full sm:w-auto">
                <span class="material-symbols-outlined">add_circle</span>
                <span>Ajouter un produit</span>
            </a> --}}
        </div>

        <!-- Product Grid - Bento Style -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
            <!-- Product Card 1 -->
              @forelse($produits as $produit)

              @php
                // // Gestion de l'image (si c'est un tableau JSON ou une simple chaîne)
                // $imagesArray = json_decode($produit->images, true);
                // $firstImage = is_array($imagesArray) && count($imagesArray) > 0 ? $imagesArray[0] : $produit->images;
                
                // Logique pour le badge de stock
                if($produit->quantite <= 0) {
                    $badgeClass = "bg-status-error";
                    $badgeText = "Rupture";
                    $imgClass = "grayscale opacity-80";
                    $cardClass = "opacity-80";
                } elseif($produit->quantite > 0 && $produit->quantite <= $produit->commande_min) {
                    $badgeClass = "bg-secondary";
                    $badgeText = "Stock Bas";
                    $imgClass = "group-hover:scale-105";
                    $cardClass = "";
                } else {
                    $badgeClass = "bg-status-success";
                    $badgeText = "En Stock";
                    $imgClass = "group-hover:scale-105";
                    $cardClass = "";
                }
            @endphp

           <div class="bg-surface-container-lowest rounded-2xl p-card-padding shadow-sm border border-outline-variant hover:border-primary hover:shadow-md transition-all group overflow-hidden flex flex-col h-full">
    
    <!-- Image et Badge -->
    <div class="relative h-48 -mx-card-padding -mt-card-padding mb-4 overflow-hidden bg-surface-container">
        @if($produit->produit?->image)
            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                 title="{{ $produit->produit?->nom }}" 
                 alt="{{ $produit->produit?->nom }}" 
                 src="{{ asset('storage/' . $produit->produit?->image) }}"/>
        @else
            <!-- Placeholder si pas d'image -->
            <div class="w-full h-full flex items-center justify-center text-on-surface-variant">
                <span class="material-symbols-outlined text-4xl opacity-50">image</span>
            </div>
        @endif
        
        <span class="absolute top-3 right-3 {{ $badgeClass }} text-white px-3 py-1 rounded-full font-label-sm text-[10px] shadow-md uppercase tracking-wider">
            {{ $badgeText }}
        </span>
    </div>
    
    <!-- Contenu de la carte (Flexible pour aligner le bas) -->
    <div class="flex-1 flex flex-col">
        
        <!-- En-tête : Catégorie, Titre et Prix -->
        <div class="flex justify-between items-start gap-3 mb-4">
            <div class="min-w-0">
                <p class="text-[10px] uppercase tracking-wider font-bold text-primary bg-primary/10 px-2 py-0.5 rounded inline-block mb-1 truncate max-w-full">
                    {{ $produit->categorie?->nom ?? 'Non classé' }}
                </p>
                <h4 class="font-body-md-bold text-on-surface truncate text-base" title="{{ $produit->produit?->nom }}">
                    {{ $produit->produit?->nom }}
                </h4>
            </div>
            <!-- Prix formaté (ex: 15 000 FCFA) -->
            <div class="text-right mt-3">
                <p class="font-headline-sm text-primary whitespace-nowrap">
                    {{ number_format($produit->prix, 0, ',', ' ') }} <span class="text-xs font-bold">FCFA</span>
                </p>
            </div>
        </div>
        
        <!-- Pousse les éléments suivants vers le bas de la carte -->
        <div class="flex-1"></div>

        <!-- Informations chiffrées (Stock & Min) -->
        <div class="grid grid-cols-2 gap-3 mb-4">
            <div class="bg-surface-container-low/50 border border-outline-variant p-2 rounded-lg flex flex-col items-center text-center">
                <span class="text-[9px] text-on-surface-variant uppercase tracking-widest mb-0.5">En Stock</span>
                <span class="font-body-md-bold text-on-surface">{{ $produit->quantite }}</span>
            </div>
            <div class="bg-surface-container-low/50 border border-outline-variant p-2 rounded-lg flex flex-col items-center text-center">
                <span class="text-[9px] text-on-surface-variant uppercase tracking-widest mb-0.5">Cmd Min.</span>
                <span class="font-body-md-bold text-on-surface">{{ $produit->commande_min ?? 1 }}</span>
            </div>
        </div>

        <!-- Ligne d'actions -->
        <div class="flex items-center justify-between pt-3 border-t border-outline-variant">
            <span class="text-[10px] text-on-surface-variant italic">
                Réf: #{{ $produit->produit?->code_barre }}
            </span>
           
            <a href="{{route('Edite-Fournisseur-Produit',$produit->id)}}" class="flex items-center gap-1.5 px-3 py-1.5 text-primary hover:bg-primary/10 rounded-lg transition-colors font-body-md-bold text-sm">
                <span class="material-symbols-outlined text-[18px]">edit</span>
                Éditer
            </a>
        </div>
        
    </div>
</div>

             @empty
            <!-- État vide si aucun produit n'est trouvé -->
            <div class="col-span-1 sm:col-span-2 lg:col-span-3 xl:col-span-4 flex flex-col items-center justify-center p-12 bg-surface-container-lowest border-2 border-dashed border-outline-variant rounded-3xl text-center mt-4">
                <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center text-primary mb-4">
                    <span class="material-symbols-outlined text-4xl">inventory_2</span>
                </div>
                <h3 class="font-headline-md text-on-surface mb-2">Aucun produit dans votre catalogue</h3>
                <p class="text-on-surface-variant max-w-md mb-6">Commencez par ajouter votre premier produit pour le proposer à la vente.</p>
                <a href="{{route('Fournisseur-Produit')}}" class="px-6 py-2.5 bg-primary text-on-primary font-body-md-bold rounded-xl shadow-lg hover:opacity-90 transition-all">
                    Ajouter mon premier produit
                </a>
            </div>
        @endforelse
            
           
        </div>
    </main>

@endsection