@extends('layouts.master')
@section('content')

<main class="min-h-screen pb-20">
<!-- Hero Section Carousel -->
<section class="carousel-container mb-12">
<div class="carousel-track" id="carouselTrack" style="transform: translateX(-100%);">
<div class="carousel-slide">
<img alt="Ferme avicole moderne au lever du soleil" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBKMP2aLGzxhvqeeERGDrXQd4XM5bY19AMQvultRfFs96tXbHhp6lChohTnctZfAv64OuEZc_q1MecmCW04TtJe7vWmgrSl6QOX6RpVXvExQt06CpHPjEW5ZkrtkFKS5bSgCa8XPQWnHsgYkF6onzKVe9CbYGAQzJXCHIYQy6krrxxMhxVE3Csy8YIgTdCltGXzhZbrGAs0VNTdEgfQDFfz21i02Z96nBxqqUVcR26FB_Z86Dg4V11Nh76P9diUAIJ44P25KYmPWwg">
<div class="absolute inset-0 hero-gradient"></div>
<div class="absolute inset-0 flex items-center px-margin-desktop">
<div class="max-w-2xl text-on-primary">
<h1 class="font-headline-lg text-headline-lg mb-4">La fraîcheur de la ferme, livrée à votre porte.</h1>
<p class="font-body-lg text-body-lg mb-8 opacity-90">Connectez-vous directement avec les meilleurs éleveurs locaux. Des produits de qualité supérieure, tracés et garantis.</p>
<form action="{{ route('Services') }}" method="GET" class="bg-surface-container-lowest p-2 rounded-2xl flex items-center gap-2 shadow-xl max-w-xl">
<div class="flex-1 flex items-center px-4 gap-3">
<span class="material-symbols-outlined text-primary">location_on</span>
<input class="w-full border-none focus:ring-0 text-on-surface py-3" name="commune_quartier" maxlength="255" placeholder="Entrez votre localisation..." type="text">
</div>
<button type="submit" class="bg-primary text-on-primary px-8 py-3 rounded-xl font-body-md-bold hover:bg-primary-container transition-colors flex items-center gap-2">
<span>Explorer</span>
<span class="material-symbols-outlined">arrow_forward</span>
</button>
</form>
</div>
</div>
</div>
<div class="carousel-slide">
<img loading="lazy" alt="Œufs bio frais" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCfCtoqvkUHoQ390NDhwHQNxOxiNcmot_DdgYhhu6UjuQjLleFOpFriAdhIke5SEZ26uLFgffqC9o48P4UgAVweoHczl5HttONRpa9kfGiPr7HuiRTDiPS62gx6lwVH0G3-KZmoXNtgcwiicX18vNIsWS75EtB5hD3s04b-8GysYHhkunCnqFSSERuCtZhfkTviijgTqAsJkeju3OnfAatzFiqNytT2dk8I54Y27b7b7gpHVxs51pueGA7dPaElcFAl7E7lOC7Dgq4">
<div class="absolute inset-0 hero-gradient"></div>
<div class="absolute inset-0 flex items-center px-margin-desktop">
<div class="max-w-2xl text-on-primary">
<h1 class="font-headline-lg text-headline-lg mb-4">Œufs frais et volailles locales certifiées.</h1>
<p class="font-body-lg text-body-lg mb-8 opacity-90">Découvrez nos produits 100% bio en provenance directe des éleveurs de la région.</p>
<a href="{{ route('Services') }}" class="bg-surface-container-lowest p-2 rounded-2xl flex items-center gap-2 shadow-xl max-w-xl inline-flex">
<span class="material-symbols-outlined text-primary px-4">location_on</span>
<span class="bg-primary text-on-primary px-8 py-3 rounded-xl font-body-md-bold">Explorer</span>
</a>
</div>
</div>
</div>
<div class="carousel-slide">
<img loading="lazy" alt="Éleveur local souriant" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDvZJHXyjmcoMAptDdeCQj0Vh70-5MlEbbnMlkzNhbwbNelJ1ZwOuhFphFx3xIb6u1mxWh3TssmP_5mdOi_llqPlLEdOIuIhgGIDWvZWmrLaAOfKXvXgNOVI2w-bE63hE4C1Un8F1rnj8emnhKwh5kQ_7_WJsQHf2Yow6fXG-8DVC5P43X-NoUjQNtgdTdhovTq_wOr2r6Bxo0zIWXRQm77mNvU0Gxkgrz9LHkKmJsyxzcPdbtTEds9Ca_XyuPZcxPGnxiu2eyL6Eo">
<div class="absolute inset-0 hero-gradient"></div>
<div class="absolute inset-0 flex items-center px-margin-desktop">
<div class="max-w-2xl text-on-primary">
<h1 class="font-headline-lg text-headline-lg mb-4">Soutenez les fermiers et éleveurs locaux.</h1>
<p class="font-body-lg text-body-lg mb-8 opacity-90">Un circuit court transparent avec paiement sécurisé par Mobile Money.</p>
<a href="{{ route('Services') }}" class="bg-surface-container-lowest p-2 rounded-2xl flex items-center gap-2 shadow-xl max-w-xl inline-flex">
<span class="material-symbols-outlined text-primary px-4">location_on</span>
<span class="bg-primary text-on-primary px-8 py-3 rounded-xl font-body-md-bold">Explorer</span>
</a>
</div>
</div>
</div>
</div>
<button class="absolute left-4 top-1/2 -translate-y-1/2 z-20 bg-white/20 hover:bg-white/40 backdrop-blur-md text-white p-3 rounded-full transition-all" id="prevSlide" type="button">
<span class="material-symbols-outlined">chevron_left</span>
</button>
<button class="absolute right-4 top-1/2 -translate-y-1/2 z-20 bg-white/20 hover:bg-white/40 backdrop-blur-md text-white p-3 rounded-full transition-all" id="nextSlide" type="button">
<span class="material-symbols-outlined">chevron_right</span>
</button>
<div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 flex gap-3" id="carouselDots">
<button type="button" class="w-3 h-3 rounded-full bg-white/50 transition-all hover:bg-white" data-slide="0"></button>
<button type="button" class="w-3 h-3 rounded-full bg-white transition-all scale-125" data-slide="1"></button>
<button type="button" class="w-3 h-3 rounded-full bg-white/50 transition-all hover:bg-white" data-slide="2"></button>
</div>
</section>

<!-- Categories Grid (stats) -->
<section class="px-margin-desktop mb-16">
<div class="bg-surface-container-low rounded-3xl p-8 md:p-12">
<div class="grid grid-cols-2 lg:grid-cols-4 gap-8 md:gap-12">
<div class="flex flex-col items-center text-center group">
<div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center mb-4 shadow-sm group-hover:scale-110 transition-transform">
<span class="material-symbols-outlined text-primary text-3xl">agriculture</span>
</div>
<span class="font-headline-lg text-headline-lg text-on-surface">{{ $statsElevateurs ?? '500+' }}</span>
<span class="font-body-md text-on-surface-variant">Éleveurs Partenaires</span>
</div>
<div class="flex flex-col items-center text-center group">
<div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center mb-4 shadow-sm group-hover:scale-110 transition-transform">
<span class="material-symbols-outlined text-primary text-3xl">local_shipping</span>
</div>
<span class="font-headline-lg text-headline-lg text-on-surface">{{ $statsLivreurs ?? '150+' }}</span>
<span class="font-body-md text-on-surface-variant">Livreurs Actifs</span>
</div>
<div class="flex flex-col items-center text-center group">
<div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center mb-4 shadow-sm group-hover:scale-110 transition-transform">
<span class="material-symbols-outlined text-primary text-3xl">map</span>
</div>
<span class="font-headline-lg text-headline-lg text-on-surface">{{ $statsZones ?? '12' }}</span>
<span class="font-body-md text-on-surface-variant">Zones de Couverture</span>
</div>
<div class="flex flex-col items-center text-center group">
<div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center mb-4 shadow-sm group-hover:scale-110 transition-transform">
<span class="material-symbols-outlined text-primary text-3xl">inventory_2</span>
</div>
<span class="font-headline-lg text-headline-lg text-on-surface">{{ $statsProduits ?? '5000+' }}</span>
<span class="font-body-md text-on-surface-variant">Produits Frais</span>
</div>
</div>
</div>
</section>

<!-- Banner Promo -->
<section class="px-4 md:px-margin-desktop mb-12 md:mb-16">
    <div class="relative group rounded-2xl md:rounded-3xl overflow-hidden shadow-md">

        <div class="hidden md:flex absolute left-4 top-1/2 -translate-y-1/2 z-20 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <button type="button" id="promoPrev" class="w-12 h-12 flex items-center justify-center bg-white/90 backdrop-blur shadow-lg text-primary rounded-full transition-transform hover:scale-110 active:scale-95">
                <span class="material-symbols-outlined text-2xl">chevron_left</span>
            </button>
        </div>

        <div class="hidden md:flex absolute right-4 top-1/2 -translate-y-1/2 z-20 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <button type="button" id="promoNext" class="w-12 h-12 flex items-center justify-center bg-white/90 backdrop-blur shadow-lg text-primary rounded-full transition-transform hover:scale-110 active:scale-95">
                <span class="material-symbols-outlined text-2xl">chevron_right</span>
            </button>
        </div>

        <div class="flex overflow-x-auto overflow-y-hidden touch-pan-y snap-x snap-mandatory no-scrollbar" id="promoSlider">

            @forelse ($publicites as $pub)
            @php
                // Parsing défensif des dates : évite un crash si le modèle
                // n'a pas de cast 'date' configuré ou si la valeur est déjà un Carbon
                $dateDebut = null;
                $dateFin = null;
                try {
                    if (!empty($pub->date_debut)) {
                        $dateDebut = $pub->date_debut instanceof \Carbon\Carbon
                            ? $pub->date_debut
                            : \Carbon\Carbon::parse($pub->date_debut);
                    }
                    if (!empty($pub->date_fin)) {
                        $dateFin = $pub->date_fin instanceof \Carbon\Carbon
                            ? $pub->date_fin
                            : \Carbon\Carbon::parse($pub->date_fin);
                    }
                } catch (\Exception $e) {
                    // Date invalide en base : on l'ignore silencieusement plutôt que de crasher la page d'accueil
                    $dateDebut = null;
                    $dateFin = null;
                }

                // Image avec fallback
                // $urlPub = $pub->image ? '/storage/' . $pub->image : asset('storage/images/promo-placeholder.png');
                $urlPub = !empty($pub['image']) ? '/storage/' . $pub['image'] : asset('storage/images/promo-placeholder.png');

                // Lien externe validé : seuls http/https sont autorisés,
                // empêche l'injection d'un schéma dangereux (javascript:, data:, etc.)
                // si jamais ce champ était un jour éditable sans contrôle strict
                $lienValide = null;
                if (!empty($pub->lien)) {
                    $lien = trim($pub->lien);
                    if (preg_match('#^https?://#i', $lien) || str_starts_with($lien, '/')) {
                        $lienValide = $lien;
                    }
                }
                $estExterne = $lienValide && preg_match('#^https?://#i', $lienValide) && !str_contains($lienValide, request()->getHost());
            @endphp
               <div class="min-w-full shrink-0 snap-start relative h-[300px] sm:h-[350px] md:h-[400px] promo-card">
                    <img alt="{{ $pub['titre'] ?? 'Publicité' }}" class="w-full h-full object-cover select-none" draggable="false" loading="lazy" src="{{ $urlPub }}" onerror="this.onerror=null;this.src='{{ asset('storage/images/promo-placeholder.png') }}';">

                    <div class="absolute inset-0 bg-gradient-to-r from-primary/95 sm:from-primary/90 via-primary/70 sm:via-primary/50 to-transparent flex items-center px-5 sm:px-8 md:px-12">
                        <div class="max-w-[85%] sm:max-w-md text-white">

                            @if(!empty($pub->badge))
                            <span class="bg-secondary text-on-secondary px-2.5 py-1 md:px-4 md:py-1.5 rounded-full text-[10px] md:text-xs font-bold mb-2 md:mb-4 inline-block shadow-sm tracking-wide">
                                {{ mb_strtoupper($pub->badge) }}
                            </span>
                            @endif

                            <h2 class="text-xl sm:text-2xl md:text-4xl font-black mb-2 leading-tight md:mb-3 drop-shadow-sm">{{ $pub->titre }}</h2>

                            @if($dateDebut || $dateFin)
                            <div class="flex items-center gap-1 md:gap-1.5 text-white/95 text-[10px] md:text-sm font-medium mb-2 md:mb-4 bg-black/20 w-fit px-2 py-1 rounded-md backdrop-blur-sm">
                                <span class="material-symbols-outlined text-sm md:text-base">schedule</span>
                                <span>
                                    @if($dateDebut && $dateFin)
                                        Du {{ $dateDebut->format('d/m/Y') }} au {{ $dateFin->format('d/m/Y') }}
                                    @elseif($dateDebut)
                                        Dès le {{ $dateDebut->format('d/m/Y') }}
                                    @else
                                        Jusqu'au {{ $dateFin->format('d/m/Y') }}
                                    @endif
                                </span>
                            </div>
                            @endif

                            <p class="text-xs sm:text-sm md:text-base mb-4 md:mb-6 opacity-90 line-clamp-2 md:line-clamp-3 drop-shadow-sm">{{ $pub->description }}</p>

                            @if($lienValide)
                            <a href="{{ $lienValide }}"
                               @if($estExterne) target="_blank" rel="noopener noreferrer" @endif
                               class="inline-flex items-center gap-2 bg-white text-primary px-5 py-2.5 md:px-8 md:py-3.5 rounded-lg md:rounded-xl text-xs md:text-base font-bold hover:bg-primary-fixed transition-colors shadow-lg active:scale-95">
                                {{ $pub->bouton_texte ?? 'En profiter' }}
                                <span class="material-symbols-outlined text-sm md:text-lg">arrow_forward</span>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="min-w-full shrink-0 snap-start relative h-[300px] md:h-[400px] promo-card bg-surface-container flex flex-col items-center justify-center p-6 text-center">
                    <span class="material-symbols-outlined text-4xl md:text-6xl text-outline-variant mb-4">campaign</span>
                    <p class="text-on-surface-variant font-bold text-sm md:text-xl">Bientôt de nouvelles offres exclusives...</p>
                </div>
            @endforelse

        </div>

        @if(isset($publicites) && $publicites->count() > 1)
        <div class="absolute bottom-3 md:bottom-5 left-1/2 -translate-x-1/2 flex gap-1.5 md:gap-2 z-20" id="promoDots">
            @foreach ($publicites as $index => $pub)
            <button type="button" class="w-1.5 h-1.5 md:w-2 md:h-2 rounded-full {{ $index === 0 ? 'bg-white w-3 md:w-6' : 'bg-white/50' }} transition-all duration-300 shadow-sm"></button>
            @endforeach
        </div>
        @endif

    </div>
</section>

<!-- Categories Browsing -->
<section class="px-margin-desktop mb-16">
<div class="flex justify-between items-end mb-8">
<div>
<h2 class="font-headline-md text-headline-md text-on-surface">Parcourir par catégories</h2>
<p class="text-on-surface-variant font-body-md">Trouvez exactement ce dont vous avez besoin</p>
</div>
<a href="{{ route('Services') }}" class="text-primary font-body-md-bold flex items-center gap-1 hover:underline">
Voir tout <span class="material-symbols-outlined text-sm">chevron_right</span>
</a>
</div>
<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-gutter">
<a href="{{ route('Services') }}" class="group cursor-pointer bg-surface-container-low hover:bg-primary-fixed transition-all duration-300 rounded-3xl p-6 flex flex-col items-center text-center">
<div class="w-16 h-16 mb-4 rounded-2xl bg-surface-container-highest flex items-center justify-center group-hover:bg-white transition-colors">
<span class="material-symbols-outlined text-primary text-4xl">pets</span>
</div>
<span class="font-body-md-bold text-on-surface">Poulets</span>
</a>
<a href="{{ route('Services') }}" class="group cursor-pointer bg-surface-container-low hover:bg-primary-fixed transition-all duration-300 rounded-3xl p-6 flex flex-col items-center text-center">
<div class="w-16 h-16 mb-4 rounded-2xl bg-surface-container-highest flex items-center justify-center group-hover:bg-white transition-colors">
<span class="material-symbols-outlined text-primary text-4xl">egg</span>
</div>
<span class="font-body-md-bold text-on-surface">Œufs</span>
</a>
<a href="{{ route('Services') }}" class="group cursor-pointer bg-surface-container-low hover:bg-primary-fixed transition-all duration-300 rounded-3xl p-6 flex flex-col items-center text-center">
<div class="w-16 h-16 mb-4 rounded-2xl bg-surface-container-highest flex items-center justify-center group-hover:bg-white transition-colors">
<span class="material-symbols-outlined text-primary text-4xl">featured_seasonal_and_gifts</span>
</div>
<span class="font-body-md-bold text-on-surface">Pintades</span>
</a>
<a href="{{ route('Services') }}" class="group cursor-pointer bg-surface-container-low hover:bg-primary-fixed transition-all duration-300 rounded-3xl p-6 flex flex-col items-center text-center">
<div class="w-16 h-16 mb-4 rounded-2xl bg-surface-container-highest flex items-center justify-center group-hover:bg-white transition-colors">
<span class="material-symbols-outlined text-primary text-4xl">cruelty_free</span>
</div>
<span class="font-body-md-bold text-on-surface">Dindes</span>
</a>
<a href="{{ route('Services') }}" class="group cursor-pointer bg-surface-container-low hover:bg-primary-fixed transition-all duration-300 rounded-3xl p-6 flex flex-col items-center text-center">
<div class="w-16 h-16 mb-4 rounded-2xl bg-surface-container-highest flex items-center justify-center group-hover:bg-white transition-colors">
<span class="material-symbols-outlined text-primary text-4xl">grass</span>
</div>
<span class="font-body-md-bold text-on-surface">Aliments</span>
</a>
<a href="{{ route('Services') }}" class="group cursor-pointer bg-surface-container-low hover:bg-primary-fixed transition-all duration-300 rounded-3xl p-6 flex flex-col items-center text-center">
<div class="w-16 h-16 mb-4 rounded-2xl bg-surface-container-highest flex items-center justify-center group-hover:bg-white transition-colors">
<span class="material-symbols-outlined text-primary text-4xl">construction</span>
</div>
<span class="font-body-md-bold text-on-surface">Matériel</span>
</a>
</div>
</section>

<!-- Featured Products Slider -->
<section class="px-4 md:px-margin-desktop mb-12 md:mb-16 relative">

    <div class="flex items-center justify-between mb-4 md:mb-8">
        <div class="flex items-center gap-2 md:gap-3">
            <span class="material-symbols-outlined text-secondary text-2xl md:text-3xl">star</span>
            <h2 class="font-headline-md text-lg md:text-3xl text-on-surface font-black">Produits en vedette</h2>
        </div>

        <div class="hidden md:flex gap-2">
            <button type="button" class="bg-surface-container-high hover:bg-primary-fixed text-on-surface p-2 rounded-full transition-all active:scale-95" id="prodPrev">
                <span class="material-symbols-outlined">chevron_left</span>
            </button>
            <button type="button" class="bg-surface-container-high hover:bg-primary-fixed text-on-surface p-2 rounded-full transition-all active:scale-95" id="prodNext">
                <span class="material-symbols-outlined">chevron_right</span>
            </button>
        </div>
    </div>

    <div class="flex overflow-x-auto snap-x snap-mandatory gap-4 md:gap-6 no-scrollbar pb-4 md:pb-6" id="productSlider">
        @forelse ($produits as $index => $produit)
        @php
            $nomProduit = $produit->produit?->nom ?? 'Produit sans nom';
            $urlProduit = $produit->produit?->image ? '/storage/' . $produit->produit->image : asset('images/produit-placeholder.png');
            $nomFournisseurProd = $produit->fournisseur?->nom ?? 'Fournisseur inconnu';

            // calcul prix promo
            $prix_initial = $produit->prix;
            $promo_immediate = $produit->getPromoImmediate();
            $promo_volume = $produit->getPromoVolume();
            $prix_final = $prix_initial;
            $pourcentage_immediat = 0;
            
            if ($promo_immediate) {
                $pourcentage_immediat = round($promo_immediate->taux_remise);
                $prix_final = $prix_initial - ($prix_initial * ($pourcentage_immediat / 100));
            }
        @endphp
        <div class="product-card min-w-[75vw] sm:min-w-[280px] md:min-w-[320px] snap-start bg-surface-container-lowest rounded-2xl md:rounded-3xl overflow-hidden shadow-sm hover:shadow-md flex flex-col h-full border border-outline-variant/30 transition-transform duration-300 hover:-translate-y-1">

    <div class="relative h-40 md:h-48 overflow-hidden cursor-pointer shrink-0" onclick="window.location.href='{{ route('Detail-Produit', $produit->id) }}'">
        <img alt="{{ $nomProduit }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ $urlProduit }}" loading="{{ $index < 2 ? 'eager' : 'lazy' }}" onerror="this.onerror=null;this.src='{{ asset('images/produit-placeholder.png') }}';">
        
        <!-- ZONE DES BADGES SUPERPOSÉS -->
        <div class="absolute top-3 left-3 md:top-4 md:left-4 flex flex-col items-start gap-1">
            <!-- Badge BIO existant -->
            <span class="bg-secondary text-on-secondary px-2 md:px-3 py-0.5 md:py-1 rounded-full text-[10px] md:text-xs font-bold shadow-sm">
                BIO
            </span>
            
            <!-- NOUVEAU BADGE PROMO IMMÉDIATE -->
            @if($promo_immediate)
            <span class="bg-status-error text-white px-2 md:px-3 py-0.5 md:py-1 rounded-full text-[10px] md:text-xs font-bold shadow-sm animate-pulse">
                -{{ $pourcentage_immediat }}%
            </span>
            @endif
        </div>
    </div>

    <div class="p-3 md:p-5 flex flex-col flex-grow">
        <h3 class="font-body-md-bold text-sm md:text-lg text-on-surface mb-1 cursor-pointer hover:text-primary line-clamp-2 leading-tight" onclick="window.location.href='{{ route('Detail-Produit', $produit->id) }}'">
            {{ $nomProduit }}
        </h3>

        <p class="text-[10px] md:text-sm text-on-surface-variant flex items-center gap-1 mb-3 md:mb-4 cursor-pointer hover:underline truncate" onclick="window.location.href='{{ $produit->fournisseur_id ? route('Detail-Fournisseurs', $produit->fournisseur_id) : '#' }}'">
            <span class="material-symbols-outlined text-sm md:text-base">storefront</span>
            <span class="truncate">{{ $nomFournisseurProd }}</span>
        </p>

        <div class="mt-auto flex items-end justify-between border-t border-outline-variant/30 pt-3">
            
            <!-- ZONE DES PRIX ET QUANTITÉS -->
            <div class="flex flex-col justify-center">
                
                <!-- NOUVEAU : Quantité et Promo Volume sur la même ligne -->
                <div class="flex items-center gap-2 mb-0.5">
                    <span class="text-[10px] md:text-xs font-bold text-secondary">
                        {{ max(0, (int) $produit->quantite) }} Dispo
                    </span>
                    
                    @if($promo_volume)
                        <span class="text-[9px] md:text-[10px] text-orange-money font-bold bg-orange-money/10 px-1.5 py-0.5 rounded">
                             -{{ round($promo_volume->taux_remise) }}% dès {{ $promo_volume->seuil_quantite }} pièces achettée
                        </span>
                    @endif
                </div>
                
                @if($promo_immediate)
                    <!-- Prix barré -->
                    <span class="text-[10px] md:text-[11px] text-on-surface-variant line-through leading-none mb-0.5 mt-0.5">
                        {{ number_format($prix_initial, 0, ',', ' ') }} FCFA
                    </span>
                    <!-- Nouveau prix réduit en rouge -->
                    <span class="font-headline-md text-sm md:text-lg text-status-error font-black leading-none mt-0.5">
                        {{ number_format($prix_final, 0, ',', ' ') }} FCFA
                    </span>
                @else
                    <!-- Prix normal -->
                    <span class="font-headline-md text-sm md:text-lg text-primary font-black leading-none mt-0.5">
                        {{ number_format($prix_initial, 0, ',', ' ') }} FCFA
                    </span>
                @endif
                
            </div>

            <!-- BOUTON PANIER -->
            <button type="button" class="add-to-cart-btn bg-primary text-on-primary p-2 md:p-3 rounded-xl md:rounded-2xl hover:scale-110 active:scale-95 transition-all shadow-md flex items-center justify-center shrink-0" data-produit-id="{{ $produit->id }}">
                <span class="material-symbols-outlined text-base md:text-xl">add_shopping_cart</span>
            </button>
        </div>

    </div>

</div>

        @empty
        <div class="w-full text-center py-12 text-on-surface-variant">
            <span class="material-symbols-outlined text-4xl mb-3 block">inventory_2</span>
            Aucun produit en vedette pour le moment.
        </div>
        @endforelse
    </div>

    @if(count($produits) > 1)
    <div class="flex justify-center gap-2 mt-2 md:mt-4" id="prodDots">
        @foreach ($produits as $index => $produit)
        <button type="button" class="w-2 h-2 md:w-2.5 md:h-2.5 rounded-full {{ $index === 0 ? 'bg-primary' : 'bg-outline-variant' }} transition-all duration-300" data-index="{{ $index }}"></button>
        @endforeach
    </div>
    @endif
</section>

<!-- Nearby Farmers Bento -->
<section class="px-4 md:px-margin-desktop mb-12 md:mb-20">

    <div class="mb-4 md:mb-8">
        <h2 class="font-headline-md text-xl md:text-headline-md text-on-surface font-black">Éleveurs certifiés à proximité</h2>
        <p class="text-on-surface-variant text-sm md:text-base font-body-md mt-1">Soutenez l'économie locale et achetez en toute confiance</p>
    </div>

    <div class="relative group">

        <div class="hidden md:flex absolute -left-4 sm:-left-6 top-1/2 -translate-y-1/2 z-20 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <button type="button" id="farmerPrev" class="w-12 h-12 flex items-center justify-center bg-white shadow-xl text-primary rounded-full transition-transform duration-300 hover:scale-110 active:scale-95">
                <span class="material-symbols-outlined text-2xl">chevron_left</span>
            </button>
        </div>
        <div class="hidden md:flex absolute -right-4 sm:-right-6 top-1/2 -translate-y-1/2 z-20 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <button type="button" id="farmerNext" class="w-12 h-12 flex items-center justify-center bg-white shadow-xl text-primary rounded-full transition-transform duration-300 hover:scale-110 active:scale-95">
                <span class="material-symbols-outlined text-2xl">chevron_right</span>
            </button>
        </div>

        <div class="flex overflow-x-auto snap-x snap-mandatory gap-4 md:gap-6 no-scrollbar pb-4 md:pb-8" id="farmerSlider">

            @forelse ($fournisseurs as $index => $fournisseur)
            @php
                $urlFournisseurImg = $fournisseur->image ? '/storage/' . $fournisseur->image : asset('images/fournisseur-placeholder.png');
                $nomAffiche = trim(($fournisseur->nom_ferme ?? '') . ' ' . ($fournisseur->nom ?? '')) ?: 'Éleveur';
            @endphp
            <div class="min-w-[85vw] sm:min-w-[300px] md:min-w-[400px] snap-start group/card relative rounded-2xl md:rounded-3xl overflow-hidden shadow-md md:shadow-lg h-[350px] md:h-[450px] shrink-0">

                <img alt="{{ $nomAffiche }}" class="w-full h-full object-cover group-hover/card:scale-105 transition-transform duration-700" src="{{ $urlFournisseurImg }}" loading="{{ $index < 2 ? 'eager' : 'lazy' }}" onerror="this.onerror=null;this.src='{{ asset('images/fournisseur-placeholder.png') }}';">

                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent flex flex-col justify-end p-5 md:p-8 text-white">

                    <div class="flex items-center gap-3 md:gap-4 mb-3 md:mb-4">
                        <div class="w-12 h-12 md:w-14 md:h-14 rounded-xl md:rounded-2xl border-2 border-primary-fixed overflow-hidden shrink-0 bg-white">
                            <img alt="Logo" class="w-full h-full object-cover" src="{{ asset('storage/Logo/user.jpg') }}" loading="lazy">
                        </div>

                        <div>
                            <h3 class="text-lg md:text-2xl font-bold leading-tight line-clamp-1">{{ $nomAffiche }}</h3>
                            <div class="flex items-center gap-1 text-secondary-container mt-0.5">
                                <span class="material-symbols-outlined text-[14px] md:text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="text-xs md:text-sm font-bold">{{ $fournisseur->note_moyenne ?? '—' }}</span>
                            </div>
                        </div>
                    </div>

                    <p class="text-xs md:text-sm text-gray-200 mb-4 md:mb-6 line-clamp-2">{{ $fournisseur->type ?? '' }}</p>

                    <a href="{{ route('Detail-Fournisseurs', $fournisseur->id) }}" class="bg-white text-primary w-fit px-5 py-2.5 md:px-6 md:py-3 rounded-lg md:rounded-xl text-sm md:text-base font-bold hover:bg-primary-fixed transition-colors shadow-lg active:scale-95">
                        Voir la ferme
                    </a>
                </div>

            </div>
            @empty
            <div class="w-full text-center py-12 text-on-surface-variant">
                <span class="material-symbols-outlined text-4xl mb-3 block">agriculture</span>
                Aucun éleveur à afficher pour le moment.
            </div>
            @endforelse

        </div>
    </div>
</section>

</main>

@endsection