@extends('layouts.master')
@section('content')

<div class="flex min-h-screen">
    {{-- Sidebar desktop --}}
    <aside class="hidden md:flex flex-col gap-base py-gutter h-[calc(100vh-76px)] w-72 sticky top-[76px] bg-surface-container-low border-r border-outline-variant overflow-y-auto custom-scrollbar">
        @include('pages.filter_page')
    </aside>

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
            <button type="button" class="md:hidden flex items-center gap-2 px-4 py-2 bg-primary text-on-primary rounded-xl text-body-md shadow-sm active:scale-95 transition-all" onclick="toggleMobileFilters(true)">
                <span class="material-symbols-outlined text-lg">filter_list</span> Filtres
            </button>
            <div class="relative flex-1 md:flex-none min-w-0 overflow-hidden">
                <select name="tri" form="filterForm"
                        onchange="document.getElementById('filterForm').submit()"
                        class="appearance-none flex items-center gap-2 pl-4 pr-8 py-2 bg-surface border border-outline-variant rounded-xl text-body-md hover:bg-surface-container-high transition-colors cursor-pointer">
                    <option value="prix_asc" {{ request('tri', 'prix_asc') == 'prix_asc' ? 'selected' : '' }}>Prix croissant</option>
                    <option value="prix_desc" {{ request('tri') == 'prix_desc' ? 'selected' : '' }}>Prix décroissant</option>
                </select>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-3 md:gap-6" id="productGrid">
    @forelse ($produits as $produit)
    @php
        //s Toutes les données potentiellement absentes sont sécurisées ici,
        // une seule fois, plutôt que de multiplier les ?-> dans tout le template
        $nomProduit    = $produit->produit?->nom ?? 'Produit sans nom';
        $imageProduit  = $produit->produit?->image;
        $urlImage      = $imageProduit ? '/storage/' . $imageProduit : asset('images/produit.png');
        $nomCategorie  = $produit->categorie?->nom ?? '—';
        $nomFournisseur = $produit->fournisseur?->nom ?? 'Fournisseur inconnu';
        $urlDetail     = route('Detail-Produit', $produit->id);
        $urlFournisseur = $produit->fournisseur_id ? route('Detail-Fournisseurs', $produit->fournisseur_id) : '#';
    @endphp
    <div class="group bg-surface-container-lowest rounded-xl md:rounded-2xl p-2 md:p-3 shadow-sm border border-outline-variant/30 transition-all duration-300 hover:shadow-md flex flex-col h-full">

        <div class="relative w-full aspect-square rounded-lg md:rounded-xl overflow-hidden mb-2 md:mb-4 cursor-pointer shrink-0" onclick="window.location.href='{{ $urlDetail }}'">
            <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                 title="{{ $nomProduit }}"
                 src="{{ $urlImage }}"
                 alt="{{ $nomProduit }}"
                 loading="lazy"
                 decoding="async"
                 onerror="this.onerror=null;this.src='{{ asset('images/produit-placeholder.png') }}';">
            <div class="absolute top-2 left-2 md:top-3 md:left-3 bg-primary text-on-primary text-[8px] md:text-[10px] font-bold px-2 py-1 rounded-full uppercase tracking-wider shadow-sm">
                Top Vente
            </div>
        </div>

        <div class="px-1 md:px-2 flex flex-col flex-grow">
            <p class="text-[9px] md:text-xs text-outline font-bold uppercase tracking-widest mb-1 truncate">{{ $nomCategorie }}</p>

            <h3 title="{{ $nomProduit }}" class="font-body-md-bold text-sm md:text-lg text-on-surface mb-1 cursor-pointer hover:text-primary line-clamp-2 leading-tight" onclick="window.location.href='{{ $urlDetail }}'">
                {{ $nomProduit }}
            </h3>

            <div class="flex items-center gap-1 mb-2">
                <span class="material-symbols-outlined text-orange-money text-[12px] md:text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
                <span class="text-[10px] md:text-sm font-bold">4.8</span>
                <span class="text-[9px] md:text-xs text-outline">(124)</span>
            </div>

            <p class="text-[10px] md:text-xs text-on-surface-variant flex items-center gap-1 mb-2 md:mb-4 cursor-pointer hover:underline truncate" onclick="window.location.href='{{ $urlFournisseur }}'">
                <span class="material-symbols-outlined text-[14px] md:text-md">user_attributes</span>
                <span class="truncate">{{ $nomFournisseur }}</span>
            </p>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 mb-3 md:mb-4 mt-auto">
                <span class="flex items-center gap-1 text-[10px] md:text-sm text-on-surface-variant font-bold">
                    <span class="material-symbols-outlined text-orange-money font-bold text-[14px] md:text-md">balance</span>
                    {{ $produit->taille?->taille ?? '—' }} Kg
                </span>
                <span class="flex items-center gap-1 text-[10px] md:text-sm text-on-surface-variant font-bold">
                    <span class="material-symbols-outlined text-orange-money font-bold text-[14px] md:text-md">storefront</span>
                    {{ max(0, (int) $produit->quantite) }} En Stock
                </span>
            </div>

            <div class="flex items-center justify-between pt-1 border-t border-outline-variant/30">
                <span class="font-headline-md text-sm md:text-lg text-primary font-black">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</span>

                <button type="button" class="add-to-cart-btn bg-primary-container hover:bg-primary text-on-primary-container hover:text-on-primary p-1.5 md:p-2 rounded-lg transition-all active:scale-90 shadow-sm" data-produit-id="{{ $produit->id }}">
                    <span class="material-symbols-outlined text-sm md:text-xl">add_shopping_cart</span>
                </button>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full py-16 flex flex-col items-center justify-center bg-surface-container-lowest rounded-2xl border border-outline-variant/30">
        <span class="material-symbols-outlined text-4xl text-outline-variant mb-3">search_off</span>
        <p class="text-on-surface-variant text-center font-bold">Aucun produit ne correspond à ces filtres.</p>
    </div>
    @endforelse
    </div>

    {{-- Pagination — indispensable si $produits est paginé, sinon le lien "page suivante" n'existe nulle part --}}
    @if(method_exists($produits, 'links'))
    <div class="mt-8">
        {{ $produits->links() }}
    </div>
    @endif
</main>
</div>

{{-- Drawer mobile --}}
<div class="fixed inset-0 z-[60] bg-black/50 opacity-0 pointer-events-none transition-opacity duration-300" id="mobile-filter-drawer">
    <div class="absolute right-0 top-0 h-full w-[85%] max-w-sm bg-surface translate-x-full transition-transform duration-300 flex flex-col shadow-2xl" id="drawer-panel">
        <div class="flex items-center justify-between p-4 border-b border-outline-variant bg-surface">
            <h2 class="font-headline-md text-primary">Filtres du Marché</h2>
            <button type="button" class="p-2 rounded-full hover:bg-surface-container-high transition-colors" onclick="toggleMobileFilters(false)">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-4 space-y-6" id="filters-container-mobile"></div>
        <div class="p-4 border-t border-outline-variant bg-surface-container-lowest grid grid-cols-2 gap-3">
            <button type="button" class="py-3 text-primary font-body-md-bold border border-outline-variant rounded-xl hover:bg-surface-container-high" onclick="document.getElementById('filterForm').reset(); document.getElementById('filterForm').submit();">Réinitialiser</button>
            <button type="button" class="py-3 bg-primary text-on-primary font-body-md-bold rounded-xl shadow-md" onclick="document.getElementById('filterForm').submit();">Appliquer</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Scripts commentés/dupliqués retirés — leur logique vit déjà dans pages.filter_page
// pour éviter le risque de double attachement d'événements (double-soumission du formulaire)

function moveFormToMobile() {
    const mobileContainer = document.getElementById('filters-container-mobile');
    const desktopForm = document.getElementById('filterForm');
    if (mobileContainer && desktopForm && !mobileContainer.contains(desktopForm)) {
        mobileContainer.appendChild(desktopForm);
    }
}

function moveFormToDesktop() {
    const desktopAside = document.querySelector('aside.hidden.md\\:flex');
    const desktopForm = document.getElementById('filterForm');
    if (desktopAside && desktopForm && !desktopAside.contains(desktopForm)) {
        desktopAside.appendChild(desktopForm);
    }
}

function toggleMobileFilters(show) {
    const drawer = document.getElementById('mobile-filter-drawer');
    const panel = document.getElementById('drawer-panel');

    if (show) {
        moveFormToMobile();
        drawer.classList.remove('opacity-0', 'pointer-events-none');
        panel.classList.remove('translate-x-full');
        document.body.style.overflow = 'hidden';
    } else {
        drawer.classList.add('opacity-0', 'pointer-events-none');
        panel.classList.add('translate-x-full');
        document.body.style.overflow = '';
    }
}

window.addEventListener('resize', function () {
    if (window.innerWidth >= 768) {
        moveFormToDesktop();
    }
});
</script>
@endpush