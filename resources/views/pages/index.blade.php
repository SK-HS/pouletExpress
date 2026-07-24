@extends('layouts.master')
@section('content')

<main class="min-h-screen pb-20">
<!-- Hero Section Carousel -->
<section class="carousel-container mb-12">
<div class="carousel-track" id="carouselTrack" style="transform: translateX(-100%);">
<!-- Slide 1 -->
<div class="carousel-slide">
<img alt="Modern poultry farm at sunrise" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBKMP2aLGzxhvqeeERGDrXQd4XM5bY19AMQvultRfFs96tXbHhp6lChohTnctZfAv64OuEZc_q1MecmCW04TtJe7vWmgrSl6QOX6RpVXvExQt06CpHPjEW5ZkrtkFKS5bSgCa8XPQWnHsgYkF6onzKVe9CbYGAQzJXCHIYQy6krrxxMhxVE3Csy8YIgTdCltGXzhZbrGAs0VNTdEgfQDFfz21i02Z96nBxqqUVcR26FB_Z86Dg4V11Nh76P9diUAIJ44P25KYmPWwg">
<div class="absolute inset-0 hero-gradient"></div>
<div class="absolute inset-0 flex items-center px-margin-desktop">
<div class="max-w-2xl text-on-primary">
<h1 class="font-headline-lg text-headline-lg mb-4">La fraîcheur de la ferme, livrée à votre porte.</h1>
<p class="font-body-lg text-body-lg mb-8 opacity-90">Connectez-vous directement avec les meilleurs éleveurs locaux. Des produits de qualité supérieure, tracés et garantis.</p>
<div class="bg-surface-container-lowest p-2 rounded-2xl flex items-center gap-2 shadow-xl max-w-xl">
<div class="flex-1 flex items-center px-4 gap-3">
<span class="material-symbols-outlined text-primary">location_on</span>
<input class="w-full border-none focus:ring-0 text-on-surface py-3" placeholder="Entrez votre localisation..." type="text">
</div>
<a href="produits.html" class="bg-primary text-on-primary px-8 py-3 rounded-xl font-body-md-bold hover:bg-primary-container transition-colors flex items-center gap-2">
<span class="">Explorer</span>
<span class="material-symbols-outlined">arrow_forward</span>
</a>
</div>
</div>
</div>
</div>
<!-- Slide 2 -->
<div class="carousel-slide">
<img alt="Fresh organic eggs" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCfCtoqvkUHoQ390NDhwHQNxOxiNcmot_DdgYhhu6UjuQjLleFOpFriAdhIke5SEZ26uLFgffqC9o48P4UgAVweoHczl5HttONRpa9kfGiPr7HuiRTDiPS62gx6lwVH0G3-KZmoXNtgcwiicX18vNIsWS75EtB5hD3s04b-8GysYHhkunCnqFSSERuCtZhfkTviijgTqAsJkeju3OnfAatzFiqNytT2dk8I54Y27b7b7gpHVxs51pueGA7dPaElcFAl7E7lOC7Dgq4">
<div class="absolute inset-0 hero-gradient"></div>
<div class="absolute inset-0 flex items-center px-margin-desktop">
<div class="max-w-2xl text-on-primary">
<h1 class="font-headline-lg text-headline-lg mb-4">Œufs frais et volailles locales certifiées.</h1>
<p class="font-body-lg text-body-lg mb-8 opacity-90">Découvrez nos produits 100% bio en provenance directe des éleveurs de la région.</p>
<div class="bg-surface-container-lowest p-2 rounded-2xl flex items-center gap-2 shadow-xl max-w-xl">
<div class="flex-1 flex items-center px-4 gap-3">
<span class="material-symbols-outlined text-primary">location_on</span>
<input class="w-full border-none focus:ring-0 text-on-surface py-3" placeholder="Entrez votre localisation..." type="text">
</div>
<a href="produits.html" class="bg-primary text-on-primary px-8 py-3 rounded-xl font-body-md-bold hover:bg-primary-container transition-colors flex items-center gap-2">
<span class="">Explorer</span>
<span class="material-symbols-outlined">arrow_forward</span>
</a>
</div>
</div>
</div>
</div>
<!-- Slide 3 -->
<div class="carousel-slide">
<img alt="Friendly local farmer" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDvZJHXyjmcoMAptDdeCQj0Vh70-5MlEbbnMlkzNhbwbNelJ1ZwOuhFphFx3xIb6u1mxWh3TssmP_5mdOi_llqPlLEdOIuIhgGIDWvZWmrLaAOfKXvXgNOVI2w-bE63hE4C1Un8F1rnj8emnhKwh5kQ_7_WJsQHf2Yow6fXG-8DVC5P43X-NoUjQNtgdTdhovTq_wOr2r6Bxo0zIWXRQm77mNvU0Gxkgrz9LHkKmJsyxzcPdbtTEds9Ca_XyuPZcxPGnxiu2eyL6Eo">
<div class="absolute inset-0 hero-gradient"></div>
<div class="absolute inset-0 flex items-center px-margin-desktop">
<div class="max-w-2xl text-on-primary">
<h1 class="font-headline-lg text-headline-lg mb-4">Soutenez les fermiers et éleveurs locaux.</h1>
<p class="font-body-lg text-body-lg mb-8 opacity-90">Un circuit court transparent avec paiement sécurisé par Mobile Money.</p>
<div class="bg-surface-container-lowest p-2 rounded-2xl flex items-center gap-2 shadow-xl max-w-xl">
<div class="flex-1 flex items-center px-4 gap-3">
<span class="material-symbols-outlined text-primary">location_on</span>
<input class="w-full border-none focus:ring-0 text-on-surface py-3" placeholder="Entrez votre localisation..." type="text">
</div>
<a href="produits.html" class="bg-primary text-on-primary px-8 py-3 rounded-xl font-body-md-bold hover:bg-primary-container transition-colors flex items-center gap-2">
<span class="">Explorer</span>
<span class="material-symbols-outlined">arrow_forward</span>
</a>
</div>
</div>
</div>
</div>
</div>
<!-- Carousel Controls -->
<button class="absolute left-4 top-1/2 -translate-y-1/2 z-20 bg-white/20 hover:bg-white/40 backdrop-blur-md text-white p-3 rounded-full transition-all" id="prevSlide">
<span class="material-symbols-outlined">chevron_left</span>
</button>
<button class="absolute right-4 top-1/2 -translate-y-1/2 z-20 bg-white/20 hover:bg-white/40 backdrop-blur-md text-white p-3 rounded-full transition-all" id="nextSlide">
<span class="material-symbols-outlined">chevron_right</span>
</button>
<!-- Dots -->
<div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 flex gap-3" id="carouselDots">
<button class="w-3 h-3 rounded-full bg-white/50 transition-all hover:bg-white" data-slide="0"></button>
<button class="w-3 h-3 rounded-full bg-white transition-all scale-125" data-slide="1"></button>
<button class="w-3 h-3 rounded-full bg-white/50 transition-all hover:bg-white" data-slide="2"></button>
</div>
</section>

<!-- Categories Grid -->
<section class="px-margin-desktop mb-16">
<div class="bg-surface-container-low rounded-3xl p-8 md:p-12">
<div class="grid grid-cols-2 lg:grid-cols-4 gap-8 md:gap-12">
<div class="flex flex-col items-center text-center group">
<div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center mb-4 shadow-sm group-hover:scale-110 transition-transform">
<span class="material-symbols-outlined text-primary text-3xl">agriculture</span>
</div>
<span class="font-headline-lg text-headline-lg text-on-surface">500+</span>
<span class="font-body-md text-on-surface-variant">Éleveurs Partenaires</span>
</div>
<div class="flex flex-col items-center text-center group">
<div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center mb-4 shadow-sm group-hover:scale-110 transition-transform">
<span class="material-symbols-outlined text-primary text-3xl">local_shipping</span>
</div>
<span class="font-headline-lg text-headline-lg text-on-surface">150+</span>
<span class="font-body-md text-on-surface-variant">Livreurs Actifs</span>
</div>
<div class="flex flex-col items-center text-center group">
<div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center mb-4 shadow-sm group-hover:scale-110 transition-transform">
<span class="material-symbols-outlined text-primary text-3xl">map</span>
</div>
<span class="font-headline-lg text-headline-lg text-on-surface">12</span>
<span class="font-body-md text-on-surface-variant">Zones de Couverture</span>
</div>
<div class="flex flex-col items-center text-center group">
<div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center mb-4 shadow-sm group-hover:scale-110 transition-transform">
<span class="material-symbols-outlined text-primary text-3xl">inventory_2</span>
</div>
<span class="font-headline-lg text-headline-lg text-on-surface">5000+</span>
<span class="font-body-md text-on-surface-variant">Produits Frais</span>
</div>
</div>
</div>
</section>

<!-- Banner Promo -->
<section class="px-margin-desktop mb-16">
<div class="relative group overflow-hidden rounded-3xl">
<div class="flex transition-transform duration-700 ease-in-out" id="promoSlider">
<div class="min-w-full relative h-64 md:h-80">
<img alt="Promo Aliment" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCk1ZOLaGeZxho8LnJGV2feCkWYDW2Mo5QDMN03UO5mLeTk7KvtsjYRk5kaCvUHp6JcGwp9wUI5W9CJcFIsoJaRPuBzC-lbhsAtfawDnSfPCPF6pCtYY1sQKlEY4rOex90AFriz0HoQ-T5i3oKALyk0Jh2MyzLbCoxIGgePHejrHaOJ_vpsxtzI-jdKN5XkRVqvw1TefDnj1Ddz-dnK5PpfDe17QQHlViTZMSAS_yNRJo6sRs2mYZRMaYkJ9dgBFaQWz11o1uldHgk">
<div class="absolute inset-0 bg-gradient-to-r from-primary/80 to-transparent flex items-center px-12">
<div class="max-w-md text-white">
<span class="bg-secondary text-on-secondary px-3 py-1 rounded-full text-label-sm font-bold mb-4 inline-block">OFFRE LIMITÉE</span>
<h2 class="text-headline-md font-headline-md mb-2">Promo -15% sur l'aliment volaille</h2>
<p class="text-body-md mb-6 opacity-90">Optimisez la croissance de vos sujets avec notre gamme premium AgriNutrition.</p>
<a href="produits.html" class="inline-block bg-white text-primary px-6 py-2 rounded-lg font-body-md-bold hover:bg-primary-fixed transition-colors">En profiter</a>
</div>
</div>
</div>
</div>
</div>
</section>

<!-- Categories Browsing -->
<section class="px-margin-desktop mb-16">
<div class="flex justify-between items-end mb-8">
<div>
<h2 class="font-headline-md text-headline-md text-on-surface">Parcourir par catégories</h2>
<p class="text-on-surface-variant font-body-md">Trouvez exactement ce dont vous avez besoin</p>
</div>
<a href="produits.html" class="text-primary font-body-md-bold flex items-center gap-1 hover:underline">
Voir tout <span class="material-symbols-outlined text-sm">chevron_right</span>
</a>
</div>
<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-gutter">
<a href="produits.html" class="group cursor-pointer bg-surface-container-low hover:bg-primary-fixed transition-all duration-300 rounded-3xl p-6 flex flex-col items-center text-center">
<div class="w-16 h-16 mb-4 rounded-2xl bg-surface-container-highest flex items-center justify-center group-hover:bg-white transition-colors">
<span class="material-symbols-outlined text-primary text-4xl">pets</span>
</div>
<span class="font-body-md-bold text-on-surface">Poulets</span>
</a>
<a href="produits.html" class="group cursor-pointer bg-surface-container-low hover:bg-primary-fixed transition-all duration-300 rounded-3xl p-6 flex flex-col items-center text-center">
<div class="w-16 h-16 mb-4 rounded-2xl bg-surface-container-highest flex items-center justify-center group-hover:bg-white transition-colors">
<span class="material-symbols-outlined text-primary text-4xl">egg</span>
</div>
<span class="font-body-md-bold text-on-surface">Œufs</span>
</a>
<a href="produits.html" class="group cursor-pointer bg-surface-container-low hover:bg-primary-fixed transition-all duration-300 rounded-3xl p-6 flex flex-col items-center text-center">
<div class="w-16 h-16 mb-4 rounded-2xl bg-surface-container-highest flex items-center justify-center group-hover:bg-white transition-colors">
<span class="material-symbols-outlined text-primary text-4xl">featured_seasonal_and_gifts</span>
</div>
<span class="font-body-md-bold text-on-surface">Pintades</span>
</a>
<a href="produits.html" class="group cursor-pointer bg-surface-container-low hover:bg-primary-fixed transition-all duration-300 rounded-3xl p-6 flex flex-col items-center text-center">
<div class="w-16 h-16 mb-4 rounded-2xl bg-surface-container-highest flex items-center justify-center group-hover:bg-white transition-colors">
<span class="material-symbols-outlined text-primary text-4xl">cruelty_free</span>
</div>
<span class="font-body-md-bold text-on-surface">Dindes</span>
</a>
<a href="produits.html" class="group cursor-pointer bg-surface-container-low hover:bg-primary-fixed transition-all duration-300 rounded-3xl p-6 flex flex-col items-center text-center">
<div class="w-16 h-16 mb-4 rounded-2xl bg-surface-container-highest flex items-center justify-center group-hover:bg-white transition-colors">
<span class="material-symbols-outlined text-primary text-4xl">grass</span>
</div>
<span class="font-body-md-bold text-on-surface">Aliments</span>
</a>
<a href="produits.html" class="group cursor-pointer bg-surface-container-low hover:bg-primary-fixed transition-all duration-300 rounded-3xl p-6 flex flex-col items-center text-center">
<div class="w-16 h-16 mb-4 rounded-2xl bg-surface-container-highest flex items-center justify-center group-hover:bg-white transition-colors">
<span class="material-symbols-outlined text-primary text-4xl">construction</span>
</div>
<span class="font-body-md-bold text-on-surface">Matériel</span>
</a>
</div>
</section>

<!-- Featured Products Slider -->
<section class="px-margin-desktop mb-16 relative">
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-secondary text-3xl">star</span>
            <h2 class="font-headline-md text-headline-md text-on-surface">Produits en vedette</h2>
        </div>
        <div class="flex gap-2">
            <button class="bg-surface-container-high hover:bg-primary-fixed text-on-surface p-2 rounded-full transition-all" id="prodPrev">
                <span class="material-symbols-outlined">chevron_left</span>
            </button>
            <button class="bg-surface-container-high hover:bg-primary-fixed text-on-surface p-2 rounded-full transition-all" id="prodNext">
                <span class="material-symbols-outlined">chevron_right</span>
            </button>
        </div>
    </div>

    <div class="flex overflow-x-auto snap-x snap-mandatory gap-gutter no-scrollbar pb-6" id="productSlider">
        @foreach ($produits as $produit)
        <div class="product-card min-w-[280px] md:min-w-[320px] snap-start bg-surface-container-lowest rounded-3xl overflow-hidden card-shadow group flex flex-col h-full border border-outline-variant/30 transition-transform hover:translate-y-[-4px]">
            <div class="relative h-48 overflow-hidden cursor-pointer" onclick="window.location.href='{{ route('Detail-Produit', $produit->id) }}'">
                <img alt="{{ $produit->produit?->nom }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="/storage/{{ $produit->produit?->image }}">
                <div class="absolute top-4 left-4 bg-secondary text-on-secondary px-3 py-1 rounded-full text-label-sm font-bold">BIO</div>
            </div>
            <div class="p-card-padding flex flex-col flex-1">
                <h3 class="font-body-md-bold text-on-surface mb-1 cursor-pointer hover:text-primary" onclick="window.location.href='{{ route('Detail-Produit', $produit->id) }}'">
                    {{ $produit->produit?->nom }}
                </h3>
                <p class="text-on-surface-variant text-sm mb-4 cursor-pointer hover:underline" onclick="window.location.href='{{ route('Detail-Fournisseurs', $produit->fournisseur_id) }}'">
                    {{ $produit->fournisseur?->nom }}
                </p>
                <div class="mt-auto flex items-center justify-between">
                    <div>
                        <span class="text-primary font-headline-md">{{ $produit->prix }} FCFA</span>
                    </div>
                    <div>
                        <span class="bg-secondary text-on-secondary px-3 py-1 rounded-full text-label-sm font-bold">{{ $produit->quantite }} Disponible</span>
                    </div>
                    <a href="{{ route('Ajouter-Panier', $produit->id) }}" class="bg-primary text-on-primary p-3 rounded-2xl hover:scale-110 active:scale-95 transition-all shadow-md flex items-center justify-center">
                        <span class="material-symbols-outlined">add_shopping_cart</span>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
        <!-- Product Card 2 -->
{{-- <div class="min-w-[280px] md:min-w-[320px] snap-start bg-surface-container-lowest rounded-3xl overflow-hidden card-shadow group flex flex-col h-full border border-outline-variant/30 transition-transform hover:translate-y-[-4px]">
<div class="relative h-48 overflow-hidden cursor-pointer" onclick="window.location.href='detail-produit.html?id=oeufs'">
<img alt="Œufs Frais" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDuPbLSkf3X5sQ9M5aJN2YVEr_-65gAKQj27vjhXt6NXL7ecXcxs4iZ5ed32j3FfHEOUq87lssVbUEVyxlmZuOSBpKJ_lH7qIzpdOUoIO5CHypKo311M8VEmc7nU9Yt_pzVto6Fx6QV7SUOIEeDvPYbnEyAaeES71IbiE-TrylLmGsYXl5CvXToszP04TlgcB2NyO6diEFkVbBJCdu_YrJA__paqTXC3nXRaAa9b0jSfa1hnhuSRSN9yAigLrFLvENAVV5qAqcNfMo">
<div class="absolute top-4 left-4 bg-status-success text-on-primary px-3 py-1 rounded-full text-label-sm font-bold">FRAIS</div>
</div>
<div class="p-card-padding flex flex-col flex-1">
<h3 class="font-body-md-bold text-on-surface mb-1 cursor-pointer hover:text-primary" onclick="window.location.href='detail-produit.html?id=oeufs'">Plateau de 30 Œufs Frais</h3>
<p class="text-on-surface-variant text-sm mb-4 cursor-pointer hover:underline" onclick="window.location.href='detail-fournisseur.html'">Les Œufs d'Or - Grand Bassam</p>
<div class="mt-auto flex items-center justify-between">
<div>
<span class="text-primary font-headline-md">2.500 FCFA</span>
</div>
<a href="panier.html" class="bg-primary text-on-primary p-3 rounded-2xl hover:scale-110 active:scale-95 transition-all shadow-md flex items-center justify-center">
<span class="material-symbols-outlined">add_shopping_cart</span>
</a>
</div>
</div>
</div> --}}

    </div>

    <div class="flex justify-center gap-2 mt-4" id="prodDots">
        @foreach ($produits as $index => $produit)
        <button class="w-2 h-2 rounded-full {{ $index === 0 ? 'bg-primary' : 'bg-outline-variant' }} transition-all duration-300" data-index="{{ $index }}"></button>
        @endforeach
    </div>
</section>

<!-- Nearby Farmers Bento -->
<section class="px-margin-desktop mb-20">
<div class="mb-8">
<h2 class="font-headline-md text-headline-md text-on-surface">Éleveurs certifiés à proximité</h2>
<p class="text-on-surface-variant font-body-md">Soutenez l'économie locale et achetez en toute confiance</p>
</div>
<div class="relative group">
<button class="absolute -left-4 top-1/2 -translate-y-1/2 z-20 bg-white shadow-lg text-primary p-3 rounded-full transition-all hover:scale-110 opacity-0 group-hover:opacity-100" id="farmerPrev">
<span class="material-symbols-outlined">chevron_left</span>
</button>
<button class="absolute -right-4 top-1/2 -translate-y-1/2 z-20 bg-white shadow-lg text-primary p-3 rounded-full transition-all hover:scale-110 opacity-0 group-hover:opacity-100" id="farmerNext">
<span class="material-symbols-outlined">chevron_right</span>
</button>

<div class="flex overflow-x-auto snap-x snap-mandatory gap-gutter no-scrollbar pb-8" id="farmerSlider">
<!-- Farmer 1 -->
<div class="min-w-[300px] md:min-w-[400px] snap-start group/card relative rounded-3xl overflow-hidden card-shadow h-[450px]">
<img alt="Ferme Avicole Saliou" class="w-full h-full object-cover group-hover/card:scale-105 transition-transform duration-700" src="https://lh3.googleusercontent.com/aida-public/AB6AXuClt--Dt17U9-iSO3qB4klbwJ3e0NZv4ekRoO7ybgf7eTlc-Umi63UFl6grNbwhx4hTVW2PRc0xAXhT59-qBnfzBGVspN06Jgu6Gwms0RCSnG08olhH4jNP6ZI7eY30ZeRwMpB_jlPOhdPgY0jvTJN3WA_LEgx_3dPkfCvTi-2HB8LZx7hEULGQPOJuDem1ixNregZoEV2aDl1TzlZZ-1Uy0EmojDuNqFgbbjGO8sz8p6E5cpxvF7JUD5G70jr4xM0V9awR-fqrzSQ">
<div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-8 text-white">
<div class="flex items-center gap-4 mb-4">
<div class="w-14 h-14 rounded-2xl border-2 border-primary-fixed overflow-hidden">
<img alt="Farmer" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC7tisvdeNuOrGpG5FFJCrix7h3s2yP6lB28m4gGZwC0ZD1k_JtfA7FRuciIWnF46j22ki7UFHzBVSOMHOukPGqltLKKu1h_ILDLOTkn1wg38W3jeNMmYsldaDmLaGTN_oCbRorGtnGZGz0pVAdRCvmsWkj8wr0QTsb0x1-Yhz6LJ7HPe-YLYkKkzPw0m_TkFTUuyr5X7koc2xLX6h3WUJcU5u5gN0AdB3fAE-aNWcS6RlKvT0eTJkdg4W1q5a-L2Qr6n6mz7nk-Hk">
</div>
<div>
<h3 class="font-headline-md">Ferme Avicole Saliou</h3>
<div class="flex items-center gap-1 text-secondary-container">
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="font-body-md-bold">4.9</span>
</div>
</div>
</div>
<p class="text-sm opacity-90 mb-6 line-clamp-2">Spécialiste du poulet bicyclette bio depuis 15 ans. Alimentation 100% naturelle.</p>
<a href="detail-fournisseur.html" class="bg-white text-primary w-fit px-6 py-2 rounded-xl font-body-md-bold hover:bg-primary-fixed transition-colors">Voir la ferme</a>
</div>
</div>

<!-- Farmer 2 -->
<div class="min-w-[300px] md:min-w-[400px] snap-start group/card relative rounded-3xl overflow-hidden card-shadow h-[450px]">
<img alt="Les Œufs d'Or" class="w-full h-full object-cover group-hover/card:scale-105 transition-transform duration-700" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDO1YPXrZ-rbsH0SH04mCcBYyuD1zjTxz1Yr1tuXevlnQ4CagGgRUSP89O1SiBxLcINSx24a4W6wfqQfTVyvXE-xxHor4VNdPUivQEpQTKfaI-bTG88XGNh_zgfWH3rkOalKHfSgeM-mQ8m2g0m8NaEoLLa3IevFmHG0VJ3D-1IpEqzmS7IbatJjf5a53zI8-Iez8KZ4CQoVr8Fv_6CxnxAJgqrkHxQa82hOskRbnqopQ4_bspeyg9Guh4IT3ZirB2wTeMYlbZfacs">
<div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-8 text-white">
<div class="flex items-center gap-4 mb-4">
<div class="w-14 h-14 rounded-2xl border-2 border-primary-fixed overflow-hidden bg-white">
<span class="material-symbols-outlined text-primary text-3xl flex items-center justify-center h-full">egg</span>
</div>
<div>
<h3 class="font-headline-md">Les Œufs d'Or</h3>
<div class="flex items-center gap-1 text-secondary-container">
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="font-body-md-bold">4.7</span>
</div>
</div>
</div>
<p class="text-sm opacity-90 mb-6 line-clamp-2">Production d'œufs frais de qualité supérieure à Grand Bassam. Livraison rapide.</p>
<a href="detail-fournisseur.html" class="bg-white text-primary w-fit px-6 py-2 rounded-xl font-body-md-bold hover:bg-primary-fixed transition-colors">Voir la ferme</a>
</div>
</div>
</div>
</div>
</section>
</main>


@endsection