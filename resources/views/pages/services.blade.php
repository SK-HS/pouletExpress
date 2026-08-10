@extends('layouts.master')
@section('content')

<div class="flex min-h-screen">
     {{-- Sidebar desktop (visible normalement) --}}
    <aside class="hidden md:flex flex-col gap-base py-gutter h-[calc(100vh-76px)] w-72 sticky top-[76px] bg-surface-container-low border-r border-outline-variant overflow-y-auto custom-scrollbar">
        @include('pages.filter_page')
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
                <button type="button" class="md:hidden flex items-center gap-2 px-4 py-2 bg-primary text-on-primary rounded-xl text-body-md shadow-sm active:scale-95 transition-all" onclick="toggleMobileFilters(true)">
                    <span class="material-symbols-outlined text-lg">filter_list</span> Filtres
                </button>
                <div  class="relative flex-1 md:flex-none min-w-0 overflow-hidden">
                   {{-- <span class="material-symbols-outlined text-lg absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none">sort</span> --}}
                <select name="tri" form="filterForm"
                        onchange="document.getElementById('filterForm').submit()"
                        class="appearance-none flex items-center gap-2 pl-4 pr-8 py-2 bg-surface border border-outline-variant rounded-xl text-body-md hover:bg-surface-container-high transition-colors cursor-pointer">
                    <option value="prix_asc" {{ request('tri', 'prix_asc') == 'prix_asc' ? 'selected' : '' }}>Prix croissant</option>
                    <option value="prix_desc" {{ request('tri') == 'prix_desc' ? 'selected' : '' }}>Prix décroissant</option>
                </select>
               
            </div>
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
                    {{-- <a href="#" data-produit-id="{{ $produit->id }}" class="bg-primary-container hover:bg-primary text-on-primary-container hover:text-on-primary p-2 rounded-xl transition-all flex items-center justify-center">
                        <span class="material-symbols-outlined">add_shopping_cart</span>
                    </a> --}}
                    <button type="button" class="add-to-cart-btn bg-primary-container hover:bg-primary text-on-primary-container hover:text-on-primary p-1.5 md:p-2 rounded-lg md:rounded-xl transition-all active:scale-90" data-produit-id="{{ $produit->id }}">
                    <span class="material-symbols-outlined text-sm md:text-base">add_shopping_cart</span>
                </button>
                </div>
            </div>
        </div>
        @empty
        <p class="text-on-surface-variant col-span-full text-center py-12">Aucun produit ne correspond à ces filtres.</p>
        @endforelse
    </div>
</main>
</div>

{{-- Drawer mobile : réutilise le MÊME formulaire, juste déplacé visuellement --}}
<div class="fixed inset-0 z-[60] bg-black/50 opacity-0 pointer-events-none transition-opacity duration-300" id="mobile-filter-drawer">
    <div class="absolute right-0 top-0 h-full w-[85%] max-w-sm bg-surface translate-x-full transition-transform duration-300 flex flex-col shadow-2xl" id="drawer-panel">
        <div class="flex items-center justify-between p-4 border-b border-outline-variant bg-surface">
            <h2 class="font-headline-md text-primary">Filtres du Marché</h2>
            <button type="button" class="p-2 rounded-full hover:bg-surface-container-high transition-colors" onclick="toggleMobileFilters(false)">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-4 space-y-6" id="filters-container-mobile">
            {{-- Contenu injecté par JS depuis la sidebar desktop, voir script plus bas --}}
        </div>
        <div class="p-4 border-t border-outline-variant bg-surface-container-lowest grid grid-cols-2 gap-3">
            <button type="button" class="py-3 text-primary font-body-md-bold border border-outline-variant rounded-xl hover:bg-surface-container-high" onclick="document.getElementById('filterForm').reset(); document.getElementById('filterForm').submit();">Réinitialiser</button>
            <button type="button" class="py-3 bg-primary text-on-primary font-body-md-bold rounded-xl shadow-md" onclick="document.getElementById('filterForm').submit();">Appliquer</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// document.addEventListener('DOMContentLoaded', function () {
//     const form = document.getElementById('filterForm');

//     // Soumission auto au changement de checkbox/select
//     document.querySelectorAll('.filter-checkbox, .filter-select').forEach(el => {
//         el.addEventListener('change', () => form.submit());
//     });

//     // Slider de prix : soumission avec debounce (évite de spammer le serveur à chaque pixel de déplacement)
//     const priceRange = document.getElementById('priceRange');
//     const priceValue = document.getElementById('priceValue');
//     let debounce;

//     priceRange.addEventListener('input', function () {
//         priceValue.textContent = (this.value / 1000) + 'k max';
//         clearTimeout(debounce);
//         debounce = setTimeout(() => form.submit(), 500);
//     });

//     // Recherche live dans la liste des fournisseurs (filtre côté client, pas besoin de soumettre)
//     const fournisseurSearch = document.getElementById('fournisseurSearch');
//     fournisseurSearch.addEventListener('input', function () {
//         const query = this.value.toLowerCase();
//         document.querySelectorAll('.fournisseur-item').forEach(item => {
//             const name = item.querySelector('.fournisseur-name').textContent.toLowerCase();
//             item.style.display = name.includes(query) ? 'flex' : 'none';
//         });
//     });
// });

</script>

<script>
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
        moveFormToMobile(); // ✅ on déplace le formulaire AVANT d'ouvrir le drawer
        drawer.classList.remove('opacity-0', 'pointer-events-none');
        panel.classList.remove('translate-x-full');
        document.body.style.overflow = 'hidden';
    } else {
        drawer.classList.add('opacity-0', 'pointer-events-none');
        panel.classList.add('translate-x-full');
        document.body.style.overflow = '';
    }
}

// Remet le formulaire dans la sidebar desktop si on repasse en grand écran
window.addEventListener('resize', function () {
    if (window.innerWidth >= 768) {
        moveFormToDesktop();
    }
});
</script>
@endpush

