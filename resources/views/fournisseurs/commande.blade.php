@extends('layouts.fournisseur.main')
@section('content')

<main class="lg:ml-64 p-margin-mobile pb-28 md:p-margin-desktop space-y-6 animate-in fade-in duration-500">
    
    <!-- En-tête et Total de la période -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6">
        <div>
            <h3 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-3xl">shopping_cart</span>
                Mes Commandes
            </h3>
            <p class="text-on-surface-variant font-body-md mt-1">Gérez, filtrez et suivez l'historique de vos ventes.</p>
        </div>

        <!-- Carte Total de la période -->
        <div class="bg-primary/10 border border-primary/20 p-4 rounded-2xl flex items-center gap-4 min-w-[250px] shadow-sm">
            <div class="w-12 h-12 bg-primary text-on-primary rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined">payments</span>
            </div>
            <div>
                <p class="text-xs font-bold text-primary uppercase tracking-wider">Total de la sélection</p>
                <p class="font-headline-md font-black text-on-surface">
                    {{ number_format($totalPeriode ?? 0, 0, ',', ' ') }} <span class="text-sm">FCFA</span>
                </p>
            </div>
        </div>
    </div>

    <!-- Onglets des statuts -->
    <div class="flex gap-2 overflow-x-auto hide-scrollbar pb-1 border-b border-outline-variant/50">
        <a href="{{ route('Fournisseur-Commande') }}" class="whitespace-nowrap px-4 py-2 rounded-t-xl font-bold transition-all {{ !request('statut') ? 'bg-primary text-on-primary' : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container' }}">Toutes</a>
        <a href="{{ route('Fournisseur-Commande', ['statut' => 'NOUVEAU']) }}" class="whitespace-nowrap px-4 py-2 rounded-t-xl font-bold transition-all flex items-center gap-2 {{ request('statut') == 'NOUVELLE' ? 'bg-primary text-on-primary' : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container' }}">
            Nouvelles
            @if(isset($countNouvelles) && $countNouvelles > 0)
                <span class="bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full">{{ $countNouvelles }}</span>
            @endif
        </a>
        <a href="{{ route('Fournisseur-Commande', ['statut' => 'EN_COURS']) }}" class="whitespace-nowrap px-4 py-2 rounded-t-xl font-bold transition-all {{ request('statut') == 'EN_COURS' ? 'bg-primary text-on-primary' : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container' }}">En cours</a>
        <a href="{{ route('Fournisseur-Commande', ['statut' => 'AFFECTEE']) }}" class="whitespace-nowrap px-4 py-2 rounded-t-xl font-bold transition-all {{ request('statut') == 'AFFECTEE' ? 'bg-primary text-on-primary' : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container' }}">Affectées</a>
        <a href="{{ route('Fournisseur-Commande', ['statut' => 'RECUPEREE']) }}" class="whitespace-nowrap px-4 py-2 rounded-t-xl font-bold transition-all {{ request('statut') == 'RECUPEREE' ? 'bg-primary text-on-primary' : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container' }}">Récupérées</a>
        <a href="{{ route('Fournisseur-Commande', ['statut' => 'LIVREE']) }}" class="whitespace-nowrap px-4 py-2 rounded-t-xl font-bold transition-all {{ request('statut') == 'LIVREE' ? 'bg-primary text-on-primary' : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container' }}">Livrées</a>
    </div>

    <!-- Barre de Filtres (Recherche & Dates) -->
    <form method="GET" action="{{ route('Fournisseur-Commande') }}" class="bg-surface-container-lowest p-4 rounded-2xl shadow-sm border border-outline-variant flex flex-col md:flex-row gap-4 items-end">
        <!-- Conserver le statut actuel s'il y en a un -->
        @if(request('statut'))
            <input type="hidden" name="statut" value="{{ request('statut') }}">
        @endif

        <div class="flex-1 w-full relative">
            <label class="text-xs font-bold text-on-surface-variant uppercase mb-1 block">Rechercher</label>
            <span class="absolute left-3 top-[28px] material-symbols-outlined text-on-surface-variant text-sm">search</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="N° de commande, client..." class="w-full pl-9 pr-3 py-2 bg-surface-container border border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition">
        </div>

        <div class="w-full md:w-auto">
            <label class="text-xs font-bold text-on-surface-variant uppercase mb-1 block">Du</label>
            <input type="date" name="date_debut" value="{{ request('date_debut') }}" class="w-full px-3 py-2 bg-surface-container border border-outline-variant rounded-lg text-sm focus:border-primary outline-none">
        </div>

        <div class="w-full md:w-auto">
            <label class="text-xs font-bold text-on-surface-variant uppercase mb-1 block">Au</label>
            <input type="date" name="date_fin" value="{{ request('date_fin') }}" class="w-full px-3 py-2 bg-surface-container border border-outline-variant rounded-lg text-sm focus:border-primary outline-none">
        </div>

        <div class="w-full md:w-auto flex gap-2">
            <button type="submit" class="flex-1 bg-primary text-on-primary px-6 py-2 rounded-lg font-bold text-sm hover:opacity-90 transition shadow-sm">
                Filtrer
            </button>
            <!-- Bouton pour réinitialiser les filtres -->
            @if(request('search') || request('date_debut') || request('date_fin'))
                <a href="{{ route('Fournisseur-Commande', ['statut' => request('statut')]) }}" class="bg-surface-variant text-on-surface-variant px-4 py-2 rounded-lg hover:bg-outline-variant transition flex items-center justify-center" title="Réinitialiser">
                    <span class="material-symbols-outlined text-sm">close</span>
                </a>
            @endif
        </div>
    </form>

    <!-- TABLEAU DES COMMANDES -->
    <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-surface-container-low/50 text-on-surface-variant text-[11px] uppercase tracking-wider font-extrabold">
                        <th class="p-4">Réf / Date</th>
                        <th class="p-4">Client</th>
                        <th class="p-4">Statut</th>
                        <th class="p-4 text-right">Total TTC</th>
                        <th class="p-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($commandes as $commande)
                        @php
                            // Couleurs selon le statut pour le badge
                            switch(strtoupper($commande->statut)) {
                                case 'EN ATTENTE':
                                case 'NOUVEAU': $badgeClass = 'bg-error-container text-error border-error/20'; break;
                                case 'LIVRÉE':
                                case 'LIVREE':   $badgeClass = 'bg-status-success/10 text-status-success border-status-success/20'; break;
                                default:         $badgeClass = 'bg-secondary-container text-on-secondary-container border-secondary-container'; break;
                            }
                        @endphp
                        <tr class="hover:bg-primary/5 transition-colors group">
                            
                            <!-- Date et Ref -->
                            <td class="p-4">
                                <span class="block font-bold text-on-surface text-sm">#{{ $commande->reference ?? 'CMD-'.$commande->id }}</span>
                                <span class="block text-xs text-on-surface-variant flex items-center gap-1 mt-0.5">
                                    <span class="material-symbols-outlined text-[12px]">schedule</span> 
                                    {{ \Carbon\Carbon::parse($commande->date_commande)->format('d/m/Y H:i') }}
                                </span>
                            </td>

                            <!-- Client -->
                            <td class="p-4">
                                <span class="block font-bold text-on-surface text-sm">{{ $commande->client->nom ?? 'Client Inconnu' }}</span>
                                <span class="block text-xs text-on-surface-variant">{{ $commande->client->telephone ?? 'Pas de numéro' }}</span>
                            </td>

                            <!-- Statut -->
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider border {{ $badgeClass }}">
                                    {{ $commande->statut }}
                                </span>
                            </td>

                            <!-- Total -->
                            <td class="p-4 text-right">
                                <span class="font-black text-primary text-sm whitespace-nowrap">
                                    {{ number_format($commande->montant_ttc, 0, ',', ' ') }} F
                                </span>
                            </td>

                            <!-- Action -->
                            <td class="p-4 text-center">
                                <a href="{{ route('Suivi-Commande-Fournisseur', $commande->id) }}" class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-surface-container hover:bg-primary hover:text-white border border-outline-variant rounded-lg transition-colors text-xs font-bold text-on-surface">
                                    <span class="material-symbols-outlined text-[16px]">visibility</span>
                                    Voir
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-12 text-center">
                                <div class="w-16 h-16 bg-surface-container-low rounded-full flex items-center justify-center mx-auto mb-4 text-on-surface-variant">
                                    <span class="material-symbols-outlined text-3xl">search_off</span>
                                </div>
                                <h4 class="font-bold text-on-surface mb-1">Aucune commande trouvée</h4>
                                <p class="text-sm text-on-surface-variant">Modifiez vos filtres ou vérifiez une autre période.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Laravel -->
        @if($commandes->hasPages())
            <div class="p-4 border-t border-outline-variant bg-surface-container-lowest">
                {{ $commandes->links() }}
            </div>
        @endif
    </div>
</main>
@endsection
