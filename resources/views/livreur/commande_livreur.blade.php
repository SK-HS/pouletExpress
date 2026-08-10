@extends('layouts.livreur.main')
@section('content')

@php
    // Compteurs par statut pour les onglets
    $totalAll       = $commande->count();
    $totalCollect   = $commande->where('statut', 'AFFECTEE')->count();
    $totalRecuperee = $commande->where('statut', 'RECUPEREE')->count();
    $totalTransit   = $commande->where('statut', 'EN_ROUTE')->count();
    $totalLivree    = $commande->where('statut', 'LIVREE')->count();
@endphp

<main class="md:ml-64 w-full md:w-[calc(100%-16rem)] pb-20 md:pb-15 p-3 sm:p-4 md:p-8 mx-auto space-y-4 sm:space-y-6">

    {{-- ── En-tête ───────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white">Mes Commandes</h2>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">Suivi de vos enlèvements et livraisons</p>
        </div>
        {{-- <div class="relative w-full sm:w-72 md:w-80">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">search</span>
            <input id="searchInput" type="text"
                   placeholder="Référence, client, quartier..."
                   class="w-full pl-9 pr-4 py-2 bg-white dark:bg-slate-800 border border-slate-200
                          dark:border-slate-700 rounded-xl text-xs sm:text-sm outline-none
                          focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 transition shadow-sm">
        </div> --}}
    </div>

    {{-- ── Cartes statistiques rapides ──────────────────── --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm">
            <p class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-1">Total</p>
            <p class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ $totalAll }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm">
            <p class="text-xs text-amber-600 font-bold uppercase tracking-wider mb-1">À collecter</p>
            <p class="text-2xl font-extrabold text-amber-600">{{ $totalCollect }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm">
            <p class="text-xs text-blue-600 font-bold uppercase tracking-wider mb-1">Collectées</p>
            <p class="text-2xl font-extrabold text-blue-600">{{ $totalRecuperee }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm">
            <p class="text-xs text-blue-600 font-bold uppercase tracking-wider mb-1">En transit</p>
            <p class="text-2xl font-extrabold text-blue-600">{{ $totalTransit }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm">
            <p class="text-xs text-emerald-600 font-bold uppercase tracking-wider mb-1">Livrées</p>
            <p class="text-2xl font-extrabold text-emerald-600">{{ $totalLivree }}</p>
        </div>
    </div>

{{-- ── Zone Dynamique (AJAX) ─────────────────────────────── --}}
<div id="zone-dynamique" class="w-full">

    <form method="GET" action="{{ route('Commande-Livreur') }}" id="filterForm" class="flex flex-col gap-4">
        
        {{-- Barre de recherche et filtres de dates --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-4 flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 w-full relative">
                <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">Rechercher</label>
                <span class="absolute left-3 top-[26px] material-symbols-outlined text-slate-400 text-sm">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Référence, Client, Quartier..." class="w-full pl-8 pr-3 py-1.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:border-emerald-500 outline-none transition-colors">
            </div>
            
            <div class="w-full md:w-auto">
                <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">Du</label>
                <input type="date" name="date_debut" value="{{ request('date_debut') }}" class="w-full px-3 py-1.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:border-emerald-500 outline-none transition-colors">
            </div>

            <div class="w-full md:w-auto">
                <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">Au</label>
                <input type="date" name="date_fin" value="{{ request('date_fin') }}" class="w-full px-3 py-1.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:border-emerald-500 outline-none transition-colors">
            </div>
        </div>

        {{-- Tableau principal - AJOUT DE min-h-[60vh] flex flex-col pour bloquer la taille --}}
        <div class="min-h-[60vh] flex flex-col bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">

            {{-- Onglets filtres --}}
            <div class="flex items-center gap-1 border-b border-slate-200 dark:border-slate-700 px-3 sm:px-6 overflow-x-auto py-1 hide-scrollbar">
                @php $currentStatus = request('statut', 'all'); @endphp
                
                <label class="cursor-pointer">
                    <input type="radio" name="statut" value="all" class="peer hidden" {{ $currentStatus == 'all' ? 'checked' : '' }}>
                    <div class="px-3 sm:px-4 py-3 text-xs sm:text-sm font-bold border-b-2 transition whitespace-nowrap peer-checked:border-emerald-600 peer-checked:text-emerald-700 border-transparent text-slate-500 hover:text-emerald-600">
                        Toutes ({{ $totalAll ?? 0 }})
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="statut" value="AFFECTEE" class="peer hidden" {{ $currentStatus == 'AFFECTEE' ? 'checked' : '' }}>
                    <div class="px-3 sm:px-4 py-3 text-xs sm:text-sm font-bold border-b-2 transition whitespace-nowrap peer-checked:border-emerald-600 peer-checked:text-emerald-700 border-transparent text-slate-500 hover:text-emerald-600">
                        À collecter ({{ $totalCollect ?? 0 }})
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="statut" value="RECUPEREE" class="peer hidden" {{ $currentStatus == 'RECUPEREE' ? 'checked' : '' }}>
                    <div class="px-3 sm:px-4 py-3 text-xs sm:text-sm font-bold border-b-2 transition whitespace-nowrap peer-checked:border-emerald-600 peer-checked:text-emerald-700 border-transparent text-slate-500 hover:text-emerald-600">
                        Collectées ({{ $totalRecuperee ?? 0 }})
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="statut" value="EN_ROUTE" class="peer hidden" {{ $currentStatus == 'EN_ROUTE' ? 'checked' : '' }}>
                    <div class="px-3 sm:px-4 py-3 text-xs sm:text-sm font-bold border-b-2 transition whitespace-nowrap peer-checked:border-emerald-600 peer-checked:text-emerald-700 border-transparent text-slate-500 hover:text-emerald-600">
                        En transit ({{ $totalTransit ?? 0 }})
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="statut" value="LIVREE" class="peer hidden" {{ $currentStatus == 'LIVREE' ? 'checked' : '' }}>
                    <div class="px-3 sm:px-4 py-3 text-xs sm:text-sm font-bold border-b-2 transition whitespace-nowrap peer-checked:border-emerald-600 peer-checked:text-emerald-700 border-transparent text-slate-500 hover:text-emerald-600">
                        Livrées ({{ $totalLivree ?? 0 }})
                    </div>
                </label>
            </div>

            {{-- En-têtes colonnes desktop --}}
            <div class="hidden md:grid grid-cols-8 gap-4 px-6 py-3 bg-slate-50 dark:bg-slate-800/80 font-bold text-xs text-slate-400 uppercase tracking-wider border-b border-slate-200 dark:border-slate-700">
                <div>Référence</div>
                <div>date</div>
                <div>Fournisseur</div>
                <div>Client</div>
                <div>Quartier</div>
                <div>Montant</div>
                <div>Statut</div>
                <div class="text-right">Actions</div>
            </div>

            {{-- Liste des commandes - AJOUT DE flex-1 pour qu'il remplisse l'espace --}}
            <div id="commandesList" class="divide-y divide-slate-100 dark:divide-slate-700/60 flex-1 flex flex-col">

                @forelse ($commandes as $cmd)
                @php
                    $statutLiv = $cmd->livraison?->statut ?? 'AFFECTEE';
                    $badgeClass = match($statutLiv) {
                        'AFFECTEE' => 'bg-amber-100 text-amber-700',
                        'RECUPEREE' => 'bg-blue-100 text-orange-700',
                        'EN_ROUTE' => 'bg-blue-100 text-blue-700',
                        'LIVREE'   => 'bg-emerald-100 text-emerald-700',
                        default    => 'bg-slate-100 text-slate-600',
                    };
                    $statutLabel = match($statutLiv) {
                        'AFFECTEE' => 'À collecter',
                        'RECUPEREE' => 'Collectée',
                        'EN_ROUTE' => 'En transit',
                        'LIVREE'   => 'Livrée',
                        default    => $statutLiv,
                    };
                @endphp

                <div class="order-row p-4 md:px-6 md:py-4 hover:bg-slate-50/80 dark:hover:bg-slate-700/40 transition flex flex-col md:grid md:grid-cols-8 gap-3 md:gap-4 md:items-center">

                    <div class="flex items-center justify-between md:justify-start gap-2">
                        <span class="font-mono text-xs font-bold bg-slate-100 dark:bg-slate-700 px-2.5 py-1 rounded-md text-slate-700 dark:text-slate-200">
                            #{{ $cmd->reference }}
                        </span>
                        <span class="md:hidden px-2 py-0.5 rounded-full text-[11px] font-bold {{ $badgeClass }}">
                            {{ $statutLabel }}
                        </span>
                    </div>

                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase md:hidden mb-0.5">Date</p>
                        <p class="font-bold text-sm text-slate-900 dark:text-white">
                            {{ date('d/m/Y H:i', strtotime($cmd->date_commande)) ?? '—' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase md:hidden mb-0.5">Fournisseur</p>
                        <p class="font-bold text-sm text-slate-900 dark:text-white">
                            {{$cmd->fournisseur?->nom ?? $cmd->fournisseur?->nom_ferme ??  '—' }} <br>
                            {{ $cmd->fournisseur?->telephone ?? $cmd->fournisseur?->contact ?? '—' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase md:hidden mb-0.5">Client</p>
                        <p class="font-bold text-sm text-slate-900 dark:text-white">
                            {{ $cmd->client?->nom ?? '—' }} <br>
                            {{ $cmd->client?->telephone ?? $cmd->client?->contact ?? '—' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase md:hidden mb-0.5">Zone</p>
                        <p class="text-sm text-slate-700 dark:text-slate-300">
                            {{ $cmd->quartier?->nom_quartier ?? '—' }} <br>
                            {{  $cmd->lieu_livraison ?? '—' }}
                        </p>
                    </div>

                    <div class="flex items-center justify-between md:block">
                        <p class="text-[10px] font-bold text-slate-400 uppercase md:hidden">Montant</p>
                        <p class="font-extrabold text-sm text-emerald-700 dark:text-emerald-400">
                            {{ number_format($cmd->montant_ttc, 0, ',', ' ') }} F
                        </p>
                    </div>

                    <div class="hidden md:block">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $badgeClass }}">
                            {{ $statutLabel }}
                        </span>
                    </div>

                    <div class="flex flex-col sm:flex-row md:flex-col lg:flex-row gap-2 pt-2 md:pt-0 border-t md:border-0 border-slate-100 dark:border-slate-700/60 md:justify-end">
                        <button type="button" onclick="ouvrirDetail('{{ $cmd->id }}')" class="w-full sm:w-auto px-3 py-2 rounded-xl bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 text-slate-700 dark:text-slate-200 font-bold text-xs flex items-center justify-center gap-1 transition">
                            <span class="material-symbols-outlined text-sm">visibility</span>
                            Détails
                        </button>
                    </div>
                </div>
                @empty
                {{-- État Vide centré au milieu du tableau grâce à flex-1 --}}
                <div class="flex-1 flex flex-col items-center justify-center text-center py-16 text-slate-400" id="emptyState">
                    <span class="material-symbols-outlined text-5xl mb-4 block">inventory_2</span>
                    <p class="font-bold text-sm">Aucune commande ne correspond aux filtres.</p>
                </div>
                @endforelse

            </div>

            {{-- Pagination --}}
            @if(method_exists($commandes, 'links') && $commandes->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 mt-auto">
                {{ $commandes->links() }}
            </div>
            @endif
                 <div class="h-20 md:hidden w-full"></div>
        </div>
    </form>
</div>


</main>

{{-- ── Modal Détails ─────────────────────────────────────── --}}
<div id="detailModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-5 border-b border-slate-200 dark:border-slate-700">
            <h3 class="font-extrabold text-lg text-slate-900 dark:text-white">Détails de la commande</h3>
            <button  onclick="fermerDetail()"
                    class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center hover:bg-slate-200 transition">
                <span class="material-symbols-outlined text-lg">close</span>
            </button>
        </div>
        <div id="detailContent" class="p-5 space-y-4">
            <div class="flex items-center justify-center py-8">
                <span class="material-symbols-outlined text-4xl text-slate-300 animate-pulse">hourglass_top</span>
            </div>
        </div>
    </div>
</div>

<script>

// ── Modal détails ────────────────────────────────────────
window.ouvrirDetail = function (commandeId) {
    const modal = document.getElementById('detailModal');
    const content = document.getElementById('detailContent');
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    // Charge les détails via AJAX
    fetch('/livreur/livreur/commande/' + commandeId + '/detail', {
        headers: { 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        content.innerHTML = `
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Référence</span>
                    <span class="font-bold">#${data.reference}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Client</span>
                    <span class="font-bold">${data.client}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Téléphone</span>
                    <a href="tel:${data.telephone}" class="font-bold text-emerald-600">${data.telephone}</a>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Zone</span>
                    <span class="font-bold">${data.quartier}</span>
                </div>
                ${data.lieu_livraison ? `
                <div class="text-sm">
                    <span class="text-slate-500 block mb-1">Lieu précis</span>
                    <p class="font-bold bg-slate-50 rounded-lg p-2 text-xs">${data.lieu_livraison}</p>
                </div>` : ''}
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Créneau</span>
                    <span class="font-bold">${data.creneau === 'matin' ? 'Matin (08h-12h)' : 'Après-midi (14h-18h)'}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Paiement</span>
                    <span class="font-bold">${data.mode_paiement ?? '—'}</span>
                </div>

                <div class="border-t border-slate-200 pt-3 mt-3">
                    <p class="font-bold text-xs text-slate-500 uppercase tracking-wider mb-2">Articles</p>
                    ${data.articles.map(a => `
                        <div class="flex justify-between text-sm py-1.5 border-b border-slate-100 last:border-0">
                            <span>${a.quantite}× ${a.nom}</span>
                            <span class="font-bold text-emerald-700">${Number(a.montant).toLocaleString('fr-FR')} FCFA</span>
                        </div>
                    `).join('')}
                </div>

                <div class="flex justify-between font-extrabold text-base pt-2 border-t-2 border-dashed border-slate-300">
                    <span>Total</span>
                    <span class="text-emerald-700">${Number(data.total).toLocaleString('fr-FR')} FCFA</span>
                </div>

                ${data.latitude && data.longitude ? `
                <a href="https://www.google.com/maps?q=${data.latitude},${data.longitude}"
                   target="_blank"
                   class="flex items-center justify-center gap-2 w-full py-3 bg-blue-600 text-white
                          font-bold rounded-xl text-sm mt-2 hover:bg-blue-700 transition">
                    <span class="material-symbols-outlined text-base">map</span>
                    Ouvrir dans Google Maps
                </a>` : ''}
            </div>
        `;
    })
    .catch(() => {
        content.innerHTML = '<p class="text-center text-red-600 py-8">Erreur lors du chargement des détails.</p>';
    });
};

window.fermerDetail = function () {
    const modal = document.getElementById('detailModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
};

// Ferme la modal en cliquant sur l'overlay
document.getElementById('detailModal').addEventListener('click', function (e) {
    if (e.target === this) fermerDetail();
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    
    function fetchCommandes(url) {
        const zone = document.getElementById('zone-dynamique');
        zone.style.opacity = '0.5'; 

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const nouvelleZone = doc.getElementById('zone-dynamique');
            
            if (nouvelleZone) {
                zone.innerHTML = nouvelleZone.innerHTML;
                zone.style.opacity = '1';
                attacherEvenements();
            }
        });
    }

    function attacherEvenements() {
        const form = document.getElementById('filterForm');
        if (!form) return;

        // Écoute les onglets (boutons radio)
        form.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', () => {
                const url = form.action + '?' + new URLSearchParams(new FormData(form)).toString();
                fetchCommandes(url);
                window.history.pushState({}, '', url);
            });
        });

        // Écoute la recherche (délai de frappe)
        let timeout = null;
        const searchInput = form.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('keyup', function () {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    const url = form.action + '?' + new URLSearchParams(new FormData(form)).toString();
                    fetchCommandes(url);
                    window.history.pushState({}, '', url);
                }, 400); 
            });
        }
        
        // Écoute les filtres de dates
        form.querySelectorAll('input[type="date"]').forEach(dateInput => {
            dateInput.addEventListener('change', () => {
                const url = form.action + '?' + new URLSearchParams(new FormData(form)).toString();
                fetchCommandes(url);
                window.history.pushState({}, '', url);
            });
        });

        // Écoute les boutons de Pagination Laravel
        document.querySelectorAll('#zone-dynamique nav a').forEach(lienPagination => {
            lienPagination.addEventListener('click', function(e) {
                e.preventDefault();
                fetchCommandes(this.href);
                window.history.pushState({}, '', this.href);
            });
        });
    }

    attacherEvenements();
});
</script>


@endsection
