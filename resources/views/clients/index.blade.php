@extends('layouts.client.main')
@section('content')

@php
    $nomComplet = Auth::guard('client')->user()?->nom ?? '';
    $mots = array_filter(explode(' ', trim($nomComplet)));
    $initiales = collect($mots)->take(2)->map(fn($mot) => strtoupper($mot[0]))->implode('');
@endphp

<main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6 sm:space-y-8">

    {{-- ── Bannière d'accueil ─────────────────────────────── --}}
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

    {{-- ── Statistiques + commande active ────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">

        {{-- Total commandes --}}
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

        {{-- Bannière commande active OU total dépensé si aucune active --}}
        @if($commandeActive)
        @php
            $statutLiv = $commandeActive->livraison?->statut ?? 'EN_ATTENTE';
            $statutLabel = match($statutLiv) {
                'EN_ATTENTE' => 'En attente de livreur', 
                'AFFECTEE'   => 'Livreur affecté',
                'RECUPEREE'   => 'Commande récupérée par le Livreur',
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

        {{-- Profil --}}
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

   {{-- ── Historique des commandes ──────────────────────── --}}
<div id="zone-dynamique" class="bg-white dark:bg-[#152238] rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">

    <!-- En-tête et Total -->
    <div class="p-4 sm:p-6 border-b border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row justify-between gap-6">
        <div>
            <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">Historique des Commandes</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400">Consultez l'état de vos commandes passées et récentes</p>
        </div>

        <!-- Carte Total Dynamique -->
        <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800/50 p-3 rounded-xl flex items-center gap-3">
            <div class="w-10 h-10 bg-emerald-600 text-white rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined text-xl">account_balance_wallet</span>
            </div>
            <div>
                <p class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Total de la sélection</p>
                <p class="font-bold text-slate-900 dark:text-white text-lg">
                    {{ number_format($totalPeriode ?? 0, 0, ',', ' ') }} <span class="text-xs">FCFA</span>
                </p>
            </div>
        </div>
    </div>

    <!-- ── FORMULAIRE DE FILTRES ── -->
    <!-- Mettez ici le vrai nom de votre route pour cette page ! -->
    <form method="GET" action="{{ route('Clients-Espace') }}" class="p-4 bg-slate-50 dark:bg-slate-800/30 border-b border-slate-200 dark:border-slate-800 space-y-4">
        
        <!-- Onglets des statuts (Boutons radio invisibles) -->
        <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
            @php $currentStatut = request('statut', 'tous'); @endphp
            
            <label class="cursor-pointer">
                <input type="radio" name="statut" value="tous" class="peer hidden" onchange="this.form.submit()" {{ $currentStatut == 'tous' ? 'checked' : '' }}>
                <span class="inline-block px-3 py-1.5 rounded-full text-xs font-bold transition-colors peer-checked:bg-emerald-700 peer-checked:text-white bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 shadow-sm">Tous</span>
            </label>
            <label class="cursor-pointer">
                <input type="radio" name="statut" value="en_cours" class="peer hidden" onchange="this.form.submit()" {{ $currentStatut == 'en_cours' ? 'checked' : '' }}>
                <span class="inline-block px-3 py-1.5 rounded-full text-xs font-bold transition-colors peer-checked:bg-emerald-700 peer-checked:text-white bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 shadow-sm">En cours</span>
            </label>
            <label class="cursor-pointer">
                <input type="radio" name="statut" value="livre" class="peer hidden" onchange="this.form.submit()" {{ $currentStatut == 'livre' ? 'checked' : '' }}>
                <span class="inline-block px-3 py-1.5 rounded-full text-xs font-bold transition-colors peer-checked:bg-emerald-700 peer-checked:text-white bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 shadow-sm">Livrés</span>
            </label>
            <label class="cursor-pointer">
                <input type="radio" name="statut" value="RECEPTIONNEE" class="peer hidden" onchange="this.form.submit()" {{ $currentStatut == 'RECEPTIONNEE' ? 'checked' : '' }}>
                <span class="inline-block px-3 py-1.5 rounded-full text-xs font-bold transition-colors peer-checked:bg-emerald-700 peer-checked:text-white bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 shadow-sm">Réceptionnée</span>
            </label>
            <label class="cursor-pointer">
                <input type="radio" name="statut" value="annule" class="peer hidden" onchange="this.form.submit()" {{ $currentStatut == 'annule' ? 'checked' : '' }}>
                <span class="inline-block px-3 py-1.5 rounded-full text-xs font-bold transition-colors peer-checked:bg-emerald-700 peer-checked:text-white bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 shadow-sm">Annulés</span>
            </label>
        </div>

        <!-- Recherche et Dates -->
        <div class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 w-full relative">
                <label class="text-[10px] font-bold text-slate-500 uppercase mb-1 block">Rechercher</label>
                <span class="absolute left-3 top-[26px] material-symbols-outlined text-slate-400 text-sm">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="N° de commande..." class="w-full pl-8 pr-3 py-1.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:border-emerald-500 outline-none">
            </div>
            
            <div class="w-full md:w-auto">
                <label class="text-[10px] font-bold text-slate-500 uppercase mb-1 block">Du</label>
                <input type="date" name="date_debut" value="{{ request('date_debut') }}" class="w-full px-3 py-1.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:border-emerald-500 outline-none">
            </div>

            <div class="w-full md:w-auto">
                <label class="text-[10px] font-bold text-slate-500 uppercase mb-1 block">Au</label>
                <input type="date" name="date_fin" value="{{ request('date_fin') }}" class="w-full px-3 py-1.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:border-emerald-500 outline-none">
            </div>

            <div class="w-full md:w-auto flex gap-2">
                <button type="submit" class="flex-1 bg-emerald-700 text-white px-4 py-1.5 rounded-lg font-bold text-sm hover:bg-emerald-800 transition shadow-sm">
                    Filtrer
                </button>
                @if(request('search') || request('date_debut') || request('date_fin'))
                    <a href="{{ route('Clients-Espace') }}" class="bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-white px-3 py-1.5 rounded-lg hover:bg-slate-300 transition flex items-center justify-center" title="Réinitialiser">
                        <span class="material-symbols-outlined text-sm">close</span>
                    </a>
                @endif
            </div>
        </div>
    </form>
    
    <!-- ── TABLEAU ── -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[540px]">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/50 text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                    <th class="p-3 sm:p-4">N° Commande</th>
                    <th class="p-3 sm:p-4">Date</th>
                    <th class="p-3 sm:p-4">Montant Total</th>
                    <th class="p-3 sm:p-4">Statut</th>
                    <th class="p-3 sm:p-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody id="orders-table-body" class="divide-y divide-slate-100 dark:divide-slate-800 text-xs sm:text-sm">
                @forelse ($commandes as $commande)
                @php
                    $statutLiv = $commande->livraison?->statut;
                    
                    if ($commande->statut === 'ANNULEE') {
                        $badgeClass = 'badge-danger'; $label = 'Annulé';
                    } elseif ($commande->commande_recu == 1) {
                        $badgeClass = 'badge-success'; $label = 'Livré & Réceptionnée';
                    } else {
                        $badgeClass = 'badge-warning'; 
                        $label = match($statutLiv) {
                            'RECUPEREE' => 'Récupérée par le Livreur',
                            'EN_ROUTE'  => 'En livraison',
                            'AFFECTEE'  => 'Livreur affecté',
                            'LIVREE'    => 'Livrée — à confirmer',
                            'RECEPTIONNEE'    => 'Réceptionnée',
                            default     => 'En attente'
                        };
                    }
                @endphp
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                    <td class="p-3 sm:p-4 font-mono font-semibold text-emerald-700 dark:text-emerald-400">{{ $commande->reference }}</td>
                    <td class="p-3 sm:p-4 text-slate-600 dark:text-slate-300">{{ \Carbon\Carbon::parse($commande->date_commande)->format('d-m-Y H:i') }}</td>
                    <td class="p-3 sm:p-4 font-bold">{{ number_format($commande->montant_ttc, 0, ',', ' ') }} FCFA</td>
                    <td class="p-3 sm:p-4">
                        <span class="badge {{ $badgeClass }}">
                            @if($label === 'En attente' || $label === 'En livraison')<span class="pulse-dot"></span>@endif
                            {{ $label }}
                        </span>
                    </td>
                    <td class="p-3 sm:p-4 text-right space-x-1">
                        @if($statutLiv === 'LIVREE' && $commande->commande_recu == 0)
                        <form method="POST" action="{{ route('Valider-Livraison-Commande', $commande->id) }}" class="inline-block">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="px-2.5 py-2 inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition">
                                <span class="material-symbols-outlined text-sm">check_circle</span>
                                <span class="hidden sm:inline">Marquer reçue</span>
                            </button>
                        </form>
                        @endif

                        <a href="{{ route('Suivi-last-Commande', $commande->id) }}"
                           class="p-1.5 sm:p-2 hover:bg-emerald-50 dark:hover:bg-slate-700 rounded-lg text-emerald-600 transition-colors inline-block"
                           title="Suivre cette commande">
                            <span class="material-symbols-outlined align-middle">visibility</span>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-slate-400">
                        <span class="material-symbols-outlined text-4xl mb-2 block">search_off</span>
                        <p class="font-bold">Aucune commande trouvée.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination Laravel --}}
    @if($commandes->hasPages())
    <div class="p-4 sm:p-6 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30">
        {{ $commandes->links() }}
    </div>
    @endif
</div>

</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const rows = document.querySelectorAll('#orders-table-body tr[data-status]');
    const emptyState = document.getElementById('emptyFilterState');

    filterBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            // Style actif
            filterBtns.forEach(function (b) {
                b.classList.remove('bg-emerald-700', 'text-white');
                b.classList.add('bg-slate-100', 'dark:bg-slate-800', 'text-slate-600', 'dark:text-slate-300');
            });
            this.classList.add('bg-emerald-700', 'text-white');
            this.classList.remove('bg-slate-100', 'dark:bg-slate-800', 'text-slate-600', 'dark:text-slate-300');

            const filtre = this.dataset.orderFilter;
            let visibles = 0;

            rows.forEach(function (row) {
                if (filtre === 'tous' || row.dataset.status === filtre) {
                    row.style.display = '';
                    visibles++;
                } else {
                    row.style.display = 'none';
                }
            });

            emptyState.classList.toggle('hidden', visibles > 0);
        });
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    
    // Fonction qui fait la requête en arrière-plan
    function fetchCommandes(url) {
        const zone = document.getElementById('zone-dynamique');
        
        // Petit effet visuel pour montrer que ça charge
        zone.style.opacity = '0.5'; 

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            // On convertit le texte HTML reçu en vraie page web
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // On extrait uniquement la nouvelle "zone-dynamique" du résultat
            const nouvelleZone = doc.getElementById('zone-dynamique');
            
            if (nouvelleZone) {
                // On remplace l'ancien tableau par le nouveau !
                zone.innerHTML = nouvelleZone.innerHTML;
                zone.style.opacity = '1';
                
                // On réattache les événements aux nouveaux éléments (très important)
                attacherEvenements();
            }
        });
    }

    // Fonction pour attacher les écouteurs sur le formulaire
    function attacherEvenements() {
        const form = document.querySelector('#zone-dynamique form');
        if (!form) return;

        // 1. Quand on valide le formulaire (ex: bouton "Filtrer")
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const url = form.action + '?' + new URLSearchParams(new FormData(form)).toString();
            fetchCommandes(url);
            // Modifie l'URL dans la barre d'adresse sans recharger
            window.history.pushState({}, '', url); 
        });

        // 2. Quand on clique sur les boutons radio (Tous, En cours...)
        form.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', () => form.dispatchEvent(new Event('submit')));
        });

        // 3. Quand on tape dans la barre de recherche (avec un léger délai pour ne pas spammer le serveur)
        let timeout = null;
        const searchInput = form.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('keyup', function () {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    form.dispatchEvent(new Event('submit'));
                }, 400); // Attend 400ms après la dernière frappe
            });
        }
        
        // 4. Quand on change les dates
        form.querySelectorAll('input[type="date"]').forEach(dateInput => {
            dateInput.addEventListener('change', () => form.dispatchEvent(new Event('submit')));
        });

        // 5. Intercepter les clics sur la Pagination de Laravel pour ne pas recharger la page
        document.querySelectorAll('#zone-dynamique nav a').forEach(lienPagination => {
            lienPagination.addEventListener('click', function(e) {
                e.preventDefault();
                fetchCommandes(this.href);
                window.history.pushState({}, '', this.href);
            });
        });
    }

    // On lance la fonction une première fois au chargement
    attacherEvenements();

    // Gérer le bouton "Précédent" du navigateur
    window.addEventListener('popstate', function () {
        fetchCommandes(window.location.href);
    });

});
</script>


@endsection
