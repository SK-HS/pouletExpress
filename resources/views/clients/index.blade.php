@extends('layouts.client.main')
@section('content')

@php
    $nomComplet = Auth::guard('client')->user()?->nom ?? '';
    $mots = array_filter(explode(' ', trim($nomComplet)));
    $initiales = collect($mots)->take(2)->map(fn($mot) => strtoupper($mot[0]))->implode('');
@endphp

<main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6 sm:space-y-8">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gradient-to-r from-emerald-900 to-emerald-700 dark:from-[#152238] dark:to-emerald-950 p-6 md:p-8 rounded-2xl sm:rounded-3xl text-white shadow-xl shadow-emerald-900/10 relative overflow-hidden">
        <div class="relative z-10 space-y-2">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-800/60 dark:bg-emerald-900/60 text-emerald-200 text-xs font-semibold uppercase tracking-wider border border-emerald-500/20">
                <span class="pulse-dot"></span> Compte Vérifié
            </span>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold tracking-tight">
                Bonjour, <span class="user-fullname">{{ $nomComplet }}</span>
            </h1>
            <p class="text-emerald-100/80 text-xs sm:text-sm md:text-base max-w-xl">
                Gérez vos approvisionnements en volaille fraîche et suivez vos livraisons en temps réel.
            </p>
        </div>
        <div class="relative z-10 flex gap-3 pt-2 md:pt-0">
            <a href="{{ route('Services') }}" class="btn-secondary text-xs sm:text-sm">
                <span class="material-symbols-outlined text-lg">shopping_basket</span> Commander du Poulet
            </a>
        </div>
        <div class="absolute -right-8 -bottom-10 opacity-10 pointer-events-none hidden sm:block">
            <span class="material-symbols-outlined text-[200px]">pets</span>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">

        <div class="bg-white dark:bg-[#152238] p-5 sm:p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm card-hover flex flex-col justify-between">
            <div>
                <div class="flex justify-between items-center mb-3 sm:mb-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Commandes</span>
                    <span class="material-symbols-outlined text-emerald-600 text-2xl">receipt_long</span>
                </div>
                <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white mb-1">
                    {{ $totalCommandes }}
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-2">
                    {{ $commandesLivrees }} livrée(s) · {{ $commandesEnCours }} en cours
                </p>
            </div>
            <div class="w-full bg-slate-100 dark:bg-slate-800 h-2.5 rounded-full overflow-hidden">
                <div class="bg-emerald-600 h-full rounded-full"
                     style="width: {{ $totalCommandes > 0 ? round(($commandesLivrees / $totalCommandes) * 100) : 0 }}%;"></div>
            </div>
        </div>

        @if($commandeActive)
        @php
            $statutLiv = $commandeActive->livraison?->statut ?? 'EN_ATTENTE';
            $statutLabel = match($statutLiv) {
                'EN_ATTENTE' => 'En attente de livreur',
                'AFFECTEE'   => 'Livreur affecté',
                'RECUPEREE'  => 'Commande récupérée par le Livreur',
                'EN_ROUTE'   => 'En cours de livraison',
                default      => $statutLiv,
            };
        @endphp
        <div class="bg-gradient-to-br from-emerald-600 to-emerald-800 text-white p-5 sm:p-6 rounded-2xl shadow-md card-hover relative overflow-hidden flex flex-col justify-between">
            <div>
                <span class="badge badge-warning mb-3">Commande Active</span>
                <h3 class="text-lg sm:text-xl font-bold mb-1">Commande #{{ $commandeActive->reference }}</h3>
                <p class="text-xs text-emerald-100 mb-4">{{ $statutLabel }}</p>
            </div>
            <a href="{{ route('Suivi-last-Commande', $commandeActive->id) }}"
               class="w-full bg-white text-emerald-800 hover:bg-emerald-50 font-bold py-2.5 px-4 rounded-xl text-xs sm:text-sm transition-colors flex items-center justify-center gap-2 shadow-sm text-center">
                <span class="material-symbols-outlined text-lg">map</span> Suivre la commande en direct
            </a>
        </div>
        @else
        <div class="bg-white dark:bg-[#152238] p-5 sm:p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm card-hover flex flex-col justify-between">
            <div>
                <div class="flex justify-between items-center mb-3 sm:mb-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Dépensé</span>
                    <span class="material-symbols-outlined text-amber-500 text-2xl">payments</span>
                </div>
                <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white mb-1">
                    {{ number_format($totalDepense, 0, ',', ' ') }}
                    <span class="text-xs sm:text-sm font-semibold text-slate-500">FCFA</span>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400">Sur {{ $commandesLivrees }} commande(s) livrée(s)</p>
            </div>
        </div>
        @endif

        <div class="bg-white dark:bg-[#152238] p-5 sm:p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm card-hover flex flex-col justify-between sm:col-span-2 lg:col-span-1">
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-3 sm:mb-4">Profil Acheteur</h3>
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-10 h-10 rounded-full bg-emerald-600 text-white font-bold flex items-center justify-center text-sm shadow">
                        {{ $initiales ?: '?' }}
                    </div>
                    <div>
                        <h4 class="font-bold text-sm sm:text-base text-slate-900 dark:text-white user-fullname">{{ $nomComplet }}</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Acheteur Particulier (Abidjan)</p>
                    </div>
                </div>
            </div>
            <a href="{{ route('Client-Profil') }}" class="btn-outline w-full text-xs justify-center text-center">
                <span class="material-symbols-outlined text-base">edit</span> Éditer mes coordonnées
            </a>
        </div>
    </div>

    {{-- Zone dynamique extraite dans un partial dédié — rechargée seule en AJAX,
         sans jamais refaire les requêtes coûteuses ci-dessus (stats, commande active...) --}}
    @include('clients.historique_commande')

</main>

<script>
document.addEventListener('DOMContentLoaded', function () {

    let controleurRequete = null; // AbortController courant — permet d'annuler une requête obsolète

    function fetchCommandes(url) {
        const zone = document.getElementById('zone-dynamique');

        // Annule la requête précédente si elle est encore en cours
        // (évite qu'une réponse lente n'écrase une réponse plus récente)
        if (controleurRequete) controleurRequete.abort();
        controleurRequete = new AbortController();

        zone.style.opacity = '0.5';
        zone.style.pointerEvents = 'none';

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            signal: controleurRequete.signal,
        })
        .then(response => {
            // Session expirée / non authentifié → redirection propre plutôt
            // que d'injecter une page de login dans le tableau
            if (response.status === 401 || response.status === 419) {
                window.location.href = "{{ route('Login-Client') }}";
                return null;
            }
            if (!response.ok) {
                throw new Error('Erreur serveur (' + response.status + ')');
            }
            return response.text();
        })
        .then(html => {
            if (html === null) return; // cas redirection déjà géré ci-dessus

            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const nouvelleZone = doc.getElementById('zone-dynamique');

            if (nouvelleZone) {
                zone.innerHTML = nouvelleZone.innerHTML;
                attacherEvenements();
            }
            zone.style.opacity = '1';
            zone.style.pointerEvents = '';
        })
        .catch(err => {
            // Une requête annulée volontairement (nouvelle recherche) n'est pas une vraie erreur
            if (err.name === 'AbortError') return;

            console.error('Erreur chargement historique :', err);
            zone.style.opacity = '1';
            zone.style.pointerEvents = '';

            // Message d'erreur visible plutôt qu'un silence total
            const alerte = document.createElement('div');
            alerte.className = 'p-4 bg-red-50 text-red-700 text-sm font-bold text-center';
            alerte.textContent = 'Impossible de charger vos commandes. Vérifiez votre connexion et réessayez.';
            zone.prepend(alerte);
            setTimeout(() => alerte.remove(), 4000);
        });
    }

    function attacherEvenements() {
        const form = document.querySelector('#zone-dynamique form');
        if (!form) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const url = form.action + '?' + new URLSearchParams(new FormData(form)).toString();
            fetchCommandes(url);
            window.history.pushState({}, '', url);
        });

        form.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', () => form.requestSubmit());
        });

        let timeout = null;
        const searchInput = form.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('keyup', function () {
                clearTimeout(timeout);
                timeout = setTimeout(() => form.requestSubmit(), 400);
            });
        }

        form.querySelectorAll('input[type="date"]').forEach(dateInput => {
            dateInput.addEventListener('change', () => form.requestSubmit());
        });

        document.querySelectorAll('#zone-dynamique nav a').forEach(lienPagination => {
            lienPagination.addEventListener('click', function (e) {
                e.preventDefault();
                fetchCommandes(this.href);
                window.history.pushState({}, '', this.href);
            });
        });
    }

    attacherEvenements();

    window.addEventListener('popstate', function () {
        fetchCommandes(window.location.href);
    });
});
</script>

@endsection