<div id="zone-dynamique" class="bg-white dark:bg-[#152238] rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">

    <div class="p-4 sm:p-6 border-b border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row justify-between gap-6">
        <div>
            <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">Historique des Commandes</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400">Consultez l'état de vos commandes passées et récentes</p>
        </div>

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

    <form method="GET" action="{{ route('Clients-Espace') }}" class="p-4 bg-slate-50 dark:bg-slate-800/30 border-b border-slate-200 dark:border-slate-800 space-y-4">

        <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
            @php $currentStatut = request('statut', 'tous'); @endphp

            @foreach([
                'tous' => 'Tous',
                'en_cours' => 'En cours',
                'livre' => 'Livrés',
                'RECEPTIONNEE' => 'Réceptionnée',
                'annule' => 'Annulés',
            ] as $valeur => $libelle)
            <label class="cursor-pointer">
                <input type="radio" name="statut" value="{{ $valeur }}" class="peer hidden" {{ $currentStatut == $valeur ? 'checked' : '' }}>
                <span class="inline-block px-3 py-1.5 rounded-full text-xs font-bold transition-colors peer-checked:bg-emerald-700 peer-checked:text-white bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 shadow-sm">{{ $libelle }}</span>
            </label>
            @endforeach
        </div>

        <div class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 w-full relative">
                <label class="text-[10px] font-bold text-slate-500 uppercase mb-1 block">Rechercher</label>
                <span class="absolute left-3 top-[26px] material-symbols-outlined text-slate-400 text-sm">search</span>
                <input type="text" name="search" value="{{ request('search') }}" maxlength="50" placeholder="N° de commande..." class="w-full pl-8 pr-3 py-1.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:border-emerald-500 outline-none">
            </div>

            <div class="w-full md:w-auto">
                <label class="text-[10px] font-bold text-slate-500 uppercase mb-1 block">Du</label>
                <input type="date" name="date_debut" value="{{ request('date_debut') }}" max="{{ now()->format('Y-m-d') }}" class="w-full px-3 py-1.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:border-emerald-500 outline-none">
            </div>

            <div class="w-full md:w-auto">
                <label class="text-[10px] font-bold text-slate-500 uppercase mb-1 block">Au</label>
                <input type="date" name="date_fin" value="{{ request('date_fin') }}" max="{{ now()->format('Y-m-d') }}" class="w-full px-3 py-1.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:border-emerald-500 outline-none">
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

    @if ($errors->any())
    <div class="p-4 bg-red-50 text-red-700 text-sm border-b border-red-100">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
    @endif

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
                            'RECUPEREE'    => 'Récupérée par le Livreur',
                            'EN_ROUTE'     => 'En livraison',
                            'AFFECTEE'     => 'Livreur affecté',
                            'LIVREE'       => 'Livrée — à confirmer',
                            'RECEPTIONNEE' => 'Réceptionnée',
                            default        => 'En attente',
                        };
                    }
                @endphp
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                    <td class="p-3 sm:p-4 font-mono font-semibold text-emerald-700 dark:text-emerald-400">{{ $commande->reference }}</td>
                    <td class="p-3 sm:p-4 text-slate-600 dark:text-slate-300">
                        {{ \Carbon\Carbon::parse($commande->date_commande)->format('d-m-Y H:i') }}
                    </td>
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

    @if($commandes->hasPages())
    <div class="p-4 sm:p-6 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30">
        {{ $commandes->links() }}
    </div>
    @endif
</div>