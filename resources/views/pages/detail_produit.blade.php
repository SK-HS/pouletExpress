@extends('layouts.master')
@section('content')


  <main class="flex-grow container mx-auto px-margin-mobile md:px-margin-desktop py-8">
                <!-- Breadcrumbs -->
                <nav class="flex items-center space-x-2 text-on-surface-variant mb-8 font-body-md">
                        <a class="hover:text-primary" href="{{route('index')}}">Accueil</a>
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                        <a class="hover:text-primary" href="{{route('Services')}}">Produits</a>
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                        <span id="breadcrumb-product" class="text-primary font-body-md-bold">{{ $produit->produit?->nom }}</span>
                </nav>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                        <!-- Left: Image Section -->
                       <div class="lg:col-span-7 space-y-4">
                          <div class="relative rounded-2xl overflow-hidden shadow-md group aspect-[4/3] md:aspect-video lg:aspect-auto lg:h-[500px]">
                              <img id="main-image" class="w-full h-full object-cover"
                                  src="/storage/{{ $produit->produit?->image }}"
                                  alt="{{ $produit->produit?->designation }}">
                          </div>
                          <div id="thumbnails-container" class="flex gap-4">
                              @if($produit->images)
                              @foreach ($produit->images as $index => $image)
                              <div class="thumbnail w-24 h-24 rounded-xl border-2 {{ $index === 0 ? 'border-primary' : 'border-outline-variant' }} overflow-hidden cursor-pointer hover:border-primary transition-colors"
                                  data-src="/storage/{{ $image }}">
                                  <img class="w-full h-full object-cover"
                                      src="/storage/{{ $image }}"
                                      alt="Volaille bio">
                              </div>
                              @endforeach
                              @endif
                          </div>
                      </div>

                        <!-- Right: Product Info -->
                        <div class="lg:col-span-5 flex flex-col gap-6">
                                <div class="space-y-2">
                                        <div class="flex items-center gap-2">
                                                <span id="product-category"
                                                        class="bg-primary-fixed text-on-primary-fixed px-3 py-1 rounded-full font-label-caps text-label-caps">PRODUIT
                                                        BIO</span>
                                                <span
                                                        class="text-status-success font-body-md-bold flex items-center gap-1">
                                                        <span
                                                                class="material-symbols-outlined text-[18px]">verified</span>
                                                        Certifié
                                                </span>
                                        </div>
                                        <h2 id="product-title" class="font-headline-lg text-headline-lg text-primary">
                                               {{ $produit->produit?->nom }}</h2>
                                        <div class="flex items-center gap-3">
                                                <span id="product-price"
                                                        class="font-headline-lg text-secondary text-headline-lg">
                                                        {{ $produit->prix }}
                                                        FCFA</span>
                                                <span id="product-original-price"
                                                        class="text-on-surface-variant line-through font-body-md">
                                                        {{ $produit->prix }}
                                                        FCFA</span>
                                        </div>
                                </div>

                                <!-- Vendor Mini Card -->
                                <div class="bg-surface-container rounded-2xl p-4 flex items-center justify-between shadow-sm cursor-pointer hover:bg-surface-container-high transition-colors"
                                        onclick="window.location.href='{{ route('Detail-Fournisseurs', $produit->fournisseur_id) }}'">
                                        <div class="flex items-center gap-3">
                                                <div
                                                        class="w-12 h-12 rounded-full overflow-hidden bg-white border border-outline-variant">
                                                        <img id="vendor-image" class="w-full h-full object-cover"
                                                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuD-dlDXy_qhayaWi-a-i7sD2HLCC2iUCN-QIxNEavigy6WjSNpPLTzNF_18gDKe55FXXD42dUMvHmtmf94o6Q0LjFWOVxtlWY7MSDDEC7ur1O67BRqcBpRfOUK-HJa3p6ZZSbMH5gnddJ_uEwKiNME5DpxKfi9TQIK35cNFcJIE_ObpvzpGHRcehLNqr7_bSgmIqfzh6RYapLatd6-_z1KaT1H1ULoedruKYO1PxTuJcmZfeK3SXWVQhg4y-aggEQRs1igMXFEumrM"
                                                                alt="Ferme Avicole Saliou">
                                                </div>
                                                <div>
                                                        <p id="vendor-name" class="font-body-md-bold text-primary">
                                                          {{ $produit->fournisseur?->nom }}
                                                        </p>
                                                        <div class="flex items-center gap-1">
                                                                <span
                                                                        class="material-symbols-outlined text-secondary text-[16px]">star</span>
                                                                <span id="vendor-rating"
                                                                        class="text-on-surface-variant font-body-md">4.9/5
                                                                        (Éleveur Certifié)</span>
                                                        </div>
                                                </div>
                                        </div>
                                        <span
                                                class="bg-status-success/10 text-status-success px-2 py-1 rounded-lg text-label-sm font-label-sm uppercase tracking-wider"
                                                 onclick="window.location.href='{{ route('Detail-Fournisseurs', $produit->fournisseur_id) }}'">Voir
                                                profil</span>
                                </div>

                                <div class="space-y-1">
                                        <p id="product-description" class="text-on-surface-variant font-body-md">
                                               {{ $produit->description }}</p>
                                        <p class="text-on-surface font-body-md-bold mt-4 flex items-center gap-2">
                                                <span
                                                        class="material-symbols-outlined text-status-success">inventory_2</span>
                                                En stock : <span id="product-stock" class="text-primary">{{ $produit->quantite }}</span>
                                        </p>
                                        <p class="text-on-surface font-body-md-bold mt-4 flex items-center gap-2">
                                                <span
                                                        class="material-symbols-outlined text-status-success">balance</span>
                                                Poids : <span id="product-stock" class="text-primary">{{ $produit->taille?->taille }}</span>
                                        </p>
                                        <p class="text-on-surface font-body-md-bold mt-4 flex items-center gap-2">
                                                <span
                                                        class="material-symbols-outlined text-status-success">category</span>
                                                Categorie : <span id="product-stock" class="text-primary">{{ $produit->categorie?->nom }}</span>
                                        </p>
                                </div>
                                <!-- Purchase Section -->
                                <div class="pt-4 border-t border-outline-variant space-y-4">
                                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                                                {{-- <div class="flex items-center justify-between border-2 border-outline rounded-xl h-12 w-full sm:w-auto">
                                                        <button class="px-4 h-full hover:bg-surface-container transition-colors"
                                                                onclick="const i = this.nextElementSibling; i.value = Math.max(1, parseInt(i.value)-1)">-</button>
                                                        <input class="w-12 text-center bg-transparent border-none focus:ring-0 font-body-md-bold"
                                                                readonly="" type="number" value="1">
                                                        <button class="px-4 h-full hover:bg-surface-container transition-colors"
                                                                onclick="const i = this.previousElementSibling; i.value = parseInt(i.value)+1">+</button>
                                                </div> --}}
                                                <button  data-produit-id="{{ $produit->id }}"
                                                        class="flex-grow add-to-cart-btn bg-primary text-on-primary h-12 rounded-xl font-body-md-bold flex items-center justify-center gap-2 shadow-lg hover:bg-primary-container transition-colors">
                                                        <span class="material-symbols-outlined">shopping_cart</span>
                                                        Ajouter au panier
                                                </button>
                                        </div>
                                        {{-- <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <a href="finaliser-commande.html"
                                                        class="border-2 border-primary text-primary h-12 rounded-xl font-body-md-bold hover:bg-primary/5 transition-colors flex items-center justify-center">
                                                        Acheter maintenant
                                                </a>
                                                <a href="finaliser-commande.html"
                                                        class="bg-orange-money text-white h-12 rounded-xl font-body-md-bold flex items-center justify-center gap-2 hover:opacity-90 transition-opacity">
                                                        Payer via Mobile Money
                                                </a>
                                        </div> --}}
                                </div>
                        </div>

                </div>
                </div>
        </main>

@endsection