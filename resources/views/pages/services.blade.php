@extends('layouts.master')
@section('content')

<div class="flex min-h-screen">
<aside class="hidden md:flex flex-col gap-base py-gutter h-[calc(100vh-76px)] w-72 sticky top-[76px] bg-surface-container-low border-r border-outline-variant overflow-y-auto custom-scrollbar">

<form id="filterForm" method="GET" action="{{ route("Services") }}">

    <div class="px-6 py-4">
        <h2 class="font-headline-md text-headline-md text-primary">Filtres du Marché</h2>
        <p class="text-on-surface-variant text-sm">Affinez votre recherche</p>
    </div>

    {{-- Catégories --}}
    <nav class="flex flex-col gap-1 px-4">
        <span class="text-xs font-bold text-outline-variant uppercase px-2 mb-2 tracking-widest">Catégories</span>

        @foreach ($categories as $categorie)
        <label class="filter-link {{ in_array($categorie->id, request('categories', [])) ? 'bg-secondary-container text-on-secondary-container' : 'text-on-surface' }} font-body-md-bold rounded-lg px-4 py-3 flex items-center gap-3 transition-all cursor-pointer">
            <input type="checkbox" name="categories[]" value="{{ $categorie->id }}" class="hidden filter-checkbox"
                   {{ in_array($categorie->id, request('categories', [])) ? 'checked' : '' }}>
            <span class="material-symbols-outlined">agriculture</span>
            {{ $categorie->type }} : {{ $categorie->nom }}
        </label>
        @endforeach
    </nav>

    {{-- Fournisseurs --}}
    <div class="px-6 py-6 border-t border-outline-variant/30 mt-4">
        <span class="text-xs font-bold text-outline-variant uppercase mb-4 block tracking-widest">Éleveurs Certifiés</span>

        <div class="mb-4 relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline-variant text-sm">search</span>
            <input type="text" id="fournisseurSearch" placeholder="Rechercher un éleveur..."
                   class="w-full bg-surface border border-outline-variant rounded-lg pl-9 pr-3 py-2 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all">
        </div>

        <div class="space-y-3 max-h-48 overflow-y-auto custom-scrollbar pr-2" id="fournisseursList">
            @foreach ($fournisseurs as $fournisseur)
            <label class="flex items-center gap-3 cursor-pointer group fournisseur-item">
                <input type="checkbox" name="fournisseurs[]" value="{{ $fournisseur->id }}"
                       class="rounded border-outline text-primary focus:ring-primary w-5 h-5 filter-checkbox"
                       {{ in_array($fournisseur->id, request('fournisseurs', [])) ? 'checked' : '' }}>
                <span class="text-body-md group-hover:text-primary transition-colors fournisseur-name">{{ $fournisseur->nom }}</span>
            </label>
            @endforeach
        </div>
    </div>

    {{-- Localisation --}}
    <div class="px-6 py-6 border-t border-outline-variant/30">
        <span class="text-xs font-bold text-outline-variant uppercase mb-4 block tracking-widest">Localisation</span>
        <select name="quartier" class="w-full bg-surface border border-outline-variant rounded-lg px-3 py-2 text-body-md focus:ring-2 focus:ring-primary filter-select">
            <option value="">Abidjan (Toutes zones)</option>
            @foreach ($quartiers as $quartier)
            <option value="{{ $quartier->id }}" {{ request('quartier') == $quartier->id ? 'selected' : '' }}>
                {{ $quartier->commune?->ville?->nom_ville }}-{{ $quartier->commune?->nom_commune }}-{{ $quartier->nom_quartier }}
            </option>
            @endforeach
        </select>
    </div>

    {{-- Prix --}}
    <div class="px-6 py-6 border-t border-outline-variant/30">
        <div class="flex justify-between items-center mb-4">
            <span class="text-xs font-bold text-outline-variant uppercase tracking-widest">Prix (FCFA)</span>
            <span class="text-sm font-bold text-primary" id="priceValue">{{ request('prix_max', 1000000) / 40 }}k max</span>
        </div>
        <input class="w-full h-2 bg-outline-variant rounded-lg appearance-none cursor-pointer accent-primary"
               max="1000000" min="25000" step="5000" type="range"
               name="prix_max" id="priceRange" value="{{ request('prix_max', 25000) }}">
    </div>

</form>
</aside>

<!-- Main Content -->
<main class="flex-1 p-margin-mobile md:p-8">
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
        <div>
            <div class="flex items-center gap-2 text-primary font-label-caps mb-2">
                <span class="material-symbols-outlined text-sm">verified</span>
                PRODUITS DE SAISON
            </div>
            <h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">Marché Avicole Local</h2>
        </div>
        <div class="flex gap-2">
            <select name="tri" form="filterForm" class="filter-select flex items-center gap-2 px-4 py-2 bg-surface border border-outline-variant rounded-full text-body-md hover:bg-surface-container-high transition-colors">
                <option value="prix_asc" {{ request('tri') == 'prix_asc' ? 'selected' : '' }}>Trier par: Prix croissant</option>
                <option value="prix_desc" {{ request('tri') == 'prix_desc' ? 'selected' : '' }}>Trier par: Prix décroissant</option>
                <option value="recent" {{ request('tri') == 'recent' ? 'selected' : '' }}>Trier par: Plus récent</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="productGrid">
        @forelse ($produits as $produit)
        <div class="group bg-surface-container-lowest rounded-2xl p-3 shadow-sm border border-outline-variant/30 transition-all duration-300 hover:shadow-md">
            <div class="relative w-full aspect-square rounded-xl overflow-hidden mb-4 cursor-pointer" onclick="window.location.href='{{ route('Detail-Produit', $produit->id) }}'">
                <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" src="/storage/{{ $produit->produit->image }}" alt="{{ $produit->produit?->nom }}">
                <div class="absolute top-3 left-3 bg-primary text-on-primary text-[10px] font-bold px-2 py-1 rounded-full uppercase tracking-wider">Top Vente</div>
            </div>
            <div class="px-2">
                <p class="text-xs text-outline font-bold uppercase tracking-widest mb-1">{{ $produit->categorie?->nom }}</p>
                <h3 class="font-body-md-bold text-lg text-on-surface mb-1 cursor-pointer hover:text-primary" onclick="window.location.href='{{ route('Detail-Produit', $produit->id) }}'">{{ $produit->produit?->nom }}</h3>
                <div class="flex items-center gap-1 mb-2">
                    <span class="material-symbols-outlined text-orange-money text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
                    <span class="text-sm font-bold">4.8</span>
                    <span class="text-xs text-outline">(124)</span>
                </div>
                <p class="text-xs text-on-surface-variant flex items-center gap-1 mb-4 cursor-pointer hover:underline" onclick="window.location.href='{{ route('Detail-Fournisseurs', $produit->fournisseur_id) }}'">
                    <span class="material-symbols-outlined text-md">user_attributes</span> {{ $produit->fournisseur?->nom }}
                </p>
                <p class="text-md text-on-surface-variant flex items-center justify-between mb-4 font-bold cursor-pointer hover:underline">
                    <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-orange-money font-bold text-md">balance</span>
                        {{ $produit->taille?->taille }} Kg
                    </span>
                    <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-orange-money font-bold text-md">storefront</span>
                        {{ $produit->quantite }} Stock
                    </span>
                </p>
                <div class="flex items-center justify-between mt-auto">
                    <span class="font-headline-md text-primary">{{ $produit->prix }} FCFA</span>
                    <a href="panier.html" class="bg-primary-container hover:bg-primary text-on-primary-container hover:text-on-primary p-2 rounded-xl transition-all flex items-center justify-center">
                        <span class="material-symbols-outlined">add_shopping_cart</span>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <p class="text-on-surface-variant col-span-full text-center py-12">Aucun produit ne correspond à ces filtres.</p>
        @endforelse
    </div>
</main>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('filterForm');

    // Soumission auto au changement de checkbox/select
    document.querySelectorAll('.filter-checkbox, .filter-select').forEach(el => {
        el.addEventListener('change', () => form.submit());
    });

    // Slider de prix : soumission avec debounce (évite de spammer le serveur à chaque pixel de déplacement)
    const priceRange = document.getElementById('priceRange');
    const priceValue = document.getElementById('priceValue');
    let debounce;

    priceRange.addEventListener('input', function () {
        priceValue.textContent = (this.value / 1000) + 'k max';
        clearTimeout(debounce);
        debounce = setTimeout(() => form.submit(), 500);
    });

    // Recherche live dans la liste des fournisseurs (filtre côté client, pas besoin de soumettre)
    const fournisseurSearch = document.getElementById('fournisseurSearch');
    fournisseurSearch.addEventListener('input', function () {
        const query = this.value.toLowerCase();
        document.querySelectorAll('.fournisseur-item').forEach(item => {
            const name = item.querySelector('.fournisseur-name').textContent.toLowerCase();
            item.style.display = name.includes(query) ? 'flex' : 'none';
        });
    });
});
</script>
@endpush