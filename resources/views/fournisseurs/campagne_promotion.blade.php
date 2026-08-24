@extends('layouts.fournisseur.main')
@section('content')

<main class="lg:ml-64 p-margin-mobile pb-28 md:p-margin-desktop space-y-8 animate-in fade-in duration-500 max-w-7xl">
    
    <!-- En-tête -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 md:mb-8">
        <div>
            <h2 class="font-headline-lg-mobile md:font-headline-lg text-primary">Mes Campagnes Promos</h2>
            <p class="text-on-surface-variant text-sm md:text-body-md mt-1">Gérez vos réductions et observez leur statut.</p>
        </div>
        <div class="flex gap-2 md:gap-3">
            <!-- Ce bouton redirige vers le formulaire de création qu'on a fait avant -->
            <a href="{{ route('Ajouter-Campagnes') }}" class="px-4 md:px-6 py-2.5 rounded-xl font-body-md-bold bg-primary text-on-primary hover:opacity-90 shadow-lg shadow-primary/10 transition-all flex items-center justify-center gap-2 text-sm md:text-base">
                <span class="material-symbols-outlined text-lg">add</span>
                Nouvelle Campagne
            </a>
        </div>
    </div>

    <!-- Alertes de succès (Si on vient de créer une campagne) -->
    @if(session('success'))
        <div class="p-4 mb-6 bg-status-success/10 border border-status-success/20 rounded-xl flex items-center gap-3">
            <span class="material-symbols-outlined text-status-success">check_circle</span>
            <p class="text-status-success font-body-md-bold">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Tableau des Campagnes -->
    <section class="bg-surface-container-lowest border border-outline-variant rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low text-on-surface-variant text-sm font-body-md-bold border-b border-outline-variant">
                        <th class="py-4 px-6">Titre de la Campagne</th>
                        <th class="py-4 px-6 text-center">Réduction</th>
                        <th class="py-4 px-6 text-center">Cible</th>
                        <th class="py-4 px-6">Période</th>
                        <th class="py-4 px-6 text-center">Statut</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    
                    @forelse ($campagnes as $campagne)
                        <tr class="hover:bg-surface-container-low/50 transition-colors">
                            
                            <!-- Titre -->
                            <td class="py-4 px-6">
                                <p class="font-body-md-bold text-on-surface">{{ $campagne->titre }}</p>
                                @if($campagne->seuil_quantite)
                                    <p class="text-xs text-on-surface-variant mt-1">Dès {{ $campagne->seuil_quantite }} articles achetés</p>
                                @endif
                            </td>

                            <!-- Réduction -->
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-primary/10 text-primary font-bold text-sm">
                                    - {{ $campagne->taux_remise }} %
                                </span>
                            </td>

                            <!-- Cible (Globale ou ciblée) -->
                            <td class="py-4 px-6 text-center">
                                @if($campagne->produits_count == 0)
                                    <span class="text-sm font-medium text-status-info bg-status-info/10 px-2 py-1 rounded-lg">Toute la boutique</span>
                                @else
                                    <span class="text-sm font-medium text-on-surface-variant">{{ $campagne->produits_count }} Produit(s)</span>
                                @endif
                            </td>

                            <!-- Dates -->
                            <td class="py-4 px-6">
                                <p class="text-sm text-on-surface">Du: {{ \Carbon\Carbon::parse($campagne->date_debut)->format('d/m/Y H:i') }}</p>
                                <p class="text-sm text-on-surface">Au: {{ \Carbon\Carbon::parse($campagne->date_fin)->format('d/m/Y H:i') }}</p>
                            </td>

                            <!-- Statut (Calculé selon la date et la case à cocher) -->
                            <td class="py-4 px-6 text-center">
                                @if(!$campagne->est_active)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-outline-variant/30 text-on-surface-variant text-xs font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-on-surface-variant"></span> Inactive (Manuelle)
                                    </span>
                                @elseif(now()->lt($campagne->date_debut))
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-status-warning/10 text-status-warning text-xs font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-status-warning"></span> À venir
                                    </span>
                                @elseif(now()->gt($campagne->date_fin))
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-status-error/10 text-status-error text-xs font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-status-error"></span> Terminée
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-status-success/10 text-status-success text-xs font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-status-success animate-pulse"></span> En cours
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{route('Edit-Campagne-Fournisseur',$campagne->id)}}" class="p-2 rounded-lg text-on-surface-variant hover:bg-primary/10 hover:text-primary transition-colors" title="Modifier">
                                        <span class="material-symbols-outlined text-xl">edit</span>
                                    </a>
                                    <form action="{{ route('Delete-Campagne-Fournisseur', $campagne->id) }}" method="POST" class="inline-block" 
                                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement cette promotion ? Cette action annulera les réductions en cours.');">
                                        @csrf
                                        @method('DELETE') <!-- Indispensable pour la sécurité Laravel -->
                                        
                                        <button type="submit" class="p-2 rounded-lg text-on-surface-variant hover:bg-status-error/10 hover:text-status-error transition-colors" title="Supprimer">
                                            <span class="material-symbols-outlined text-xl">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <!-- État vide (S'il n'a pas encore créé de campagne) -->
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-surface-container-high mb-4">
                                    <span class="material-symbols-outlined text-3xl text-on-surface-variant">campaign</span>
                                </div>
                                <h3 class="font-headline-md text-on-surface mb-2">Aucune campagne active</h3>
                                <p class="text-on-surface-variant text-sm mb-6 max-w-md mx-auto">Vous n'avez pas encore créé de promotions pour vos clients. Créez votre première campagne pour booster vos ventes !</p>
                                <a href="{{ route('Ajouter-Campagnes') }}" class="inline-flex px-6 py-2.5 rounded-xl font-body-md-bold bg-primary text-on-primary hover:opacity-90 shadow-lg shadow-primary/10 transition-all text-sm">
                                    Créer une promotion
                                </a>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </section>

</main>
@endsection
