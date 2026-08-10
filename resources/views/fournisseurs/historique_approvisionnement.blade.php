@extends('layouts.fournisseur.main')
@section('content')

<main class="lg:ml-64 p-margin-mobile pb-28 md:p-margin-desktop space-y-8 animate-in fade-in duration-500">
    
    <!-- En-tête de la page -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 mb-8 md:mb-10">
        <div>
            <h3 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-3xl md:text-4xl">history</span>
                Historique des Stocks
            </h3>
            <p class="text-on-surface-variant font-body-md mt-1">Consultez l'historique détaillé de toutes vos entrées en stock.</p>
        </div>
        
        <!-- Bouton pour retourner à l'action de réapprovisionnement -->
        <!-- Remplacez la route par la vôtre -->
        <a href="{{ route('Approvisionnement-Produit') }}"  class="bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold flex items-center gap-1.5 hover:bg-red-700 shadow-sm transition-all active:scale-95 whitespace-nowrap mt-1">
            <span class="material-symbols-outlined">add_shopping_cart</span>
            Faire un réapprovisionnement
        </a>
    </div>

    <!-- Conteneur du tableau -->
    <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant overflow-hidden">
        
        <!-- En-tête du tableau avec filtre (optionnel pour l'esthétique) -->
        <div class="p-4 sm:p-6 border-b border-outline-variant flex flex-col sm:flex-row items-center justify-between gap-4 bg-surface-container-low/30">
            <h4 class="font-bold text-on-surface text-lg">Dernières opérations</h4>
            <div class="relative w-full sm:w-64">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-on-surface-variant text-sm">search</span>
                <input type="text" placeholder="Chercher un produit..." class="w-full pl-9 pr-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition">
            </div>
        </div>

        <!-- Le Tableau -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-surface-container-low/50 text-on-surface-variant text-xs uppercase tracking-wider font-bold">
                        <th class="p-4 pl-6">Date</th>
                        <th class="p-4">Produit</th>
                        <th class="p-4">Motif</th>
                        <th class="p-4 text-center">Stock Avant</th>
                        <th class="p-4 text-center text-primary">Ajout</th>
                        <th class="p-4 text-center">Nouveau Stock</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    
                    @forelse($approvisionnements ?? [] as $appro)
                    <tr class="hover:bg-primary/5 transition-colors group">
                        
                        <!-- Date et Heure -->
                        <td class="p-4 pl-6">
                            <span class="block text-sm font-bold text-on-surface">{{ \Carbon\Carbon::parse($appro->created_at)->format('d/m/Y') }}</span>
                            <span class="block text-[11px] text-on-surface-variant">{{ \Carbon\Carbon::parse($appro->created_at)->format('H:i') }}</span>
                        </td>
                        
                        <!-- Produit (Image + Nom) -->
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg overflow-hidden bg-surface-container border border-outline-variant flex-shrink-0">
                                    @if($appro->produitFournisseur?->produit?->image)
                                        <img src="{{ asset('storage/' . $appro->produitFournisseur->produit->image) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-on-surface-variant">
                                            <span class="material-symbols-outlined text-sm">image</span>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-on-surface">{{ $appro->produitFournisseur?->produit?->nom ?? 'Produit Inconnu' }}</p>
                                    <p class="text-[10px] text-primary uppercase tracking-wider">{{ $appro->produitFournisseur?->categorie?->nom ?? 'Non classé' }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Motif -->
                        <td class="p-4">
                            <span class="bg-surface-container text-on-surface-variant px-2.5 py-1 rounded-md text-xs font-medium border border-outline-variant/50">
                                {{ $appro->motif ?? 'Réapprovisionnement' }}
                            </span>
                        </td>

                        <!-- Quantité Avant -->
                        <td class="p-4 text-center">
                            <span class="text-sm text-on-surface-variant font-medium">{{ $appro->quantite_avant }}</span>
                        </td>

                        <!-- Quantité Ajoutée (Mise en valeur verte) -->
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center justify-center gap-1 bg-status-success/10 text-status-success font-extrabold text-sm px-3 py-1 rounded-full border border-status-success/20">
                                <span class="material-symbols-outlined text-[14px]">arrow_upward</span>
                                {{ $appro->quantite_ajoutee }}
                            </span>
                        </td>

                        <!-- Quantité Après -->
                        <td class="p-4 text-center">
                            <span class="text-sm font-extrabold text-on-surface">{{ $appro->quantite_apres }}</span>
                        </td>
                        
                    </tr>
                    @empty
                    
                    <!-- État Vide -->
                    <tr>
                        <td colspan="6" class="p-12 text-center">
                            <div class="w-16 h-16 bg-surface-container-low rounded-full flex items-center justify-center mx-auto mb-4 text-on-surface-variant">
                                <span class="material-symbols-outlined text-3xl">history_toggle_off</span>
                            </div>
                            <h4 class="font-bold text-on-surface mb-1">Aucun historique</h4>
                            <p class="text-sm text-on-surface-variant max-w-sm mx-auto">Vous n'avez effectué aucun réapprovisionnement pour le moment.</p>
                        </td>
                    </tr>
                    @endforelse
                    
                </tbody>
            </table>
        </div>
        
        <!-- Pagination (Optionnelle si vous utilisez ->paginate() dans le controller) -->
        @if(isset($approvisionnements) && method_exists($approvisionnements, 'links'))
            <div class="p-4 border-t border-outline-variant bg-surface-container-low/30">
                {{ $approvisionnements->links() }}
            </div>
        @endif
        
    </div>

</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // On cible le champ de recherche
    const searchInput = document.querySelector('input[placeholder="Chercher un produit..."]');
    
    // On cible toutes les lignes de données du tableau (qui ont la classe 'group')
    const tableRows = document.querySelectorAll('tbody tr.group');

    if(searchInput) {
        searchInput.addEventListener('keyup', function(e) {
            // Le texte tapé par l'utilisateur (en minuscules pour faciliter la comparaison)
            const term = e.target.value.toLowerCase();
            
            // On parcourt chaque ligne du tableau
            tableRows.forEach(row => {
                // On récupère tout le texte de la ligne (Nom du produit, date, quantité, motif...)
                const rowText = row.textContent.toLowerCase();
                
                // Si le texte de la ligne contient ce qu'on a tapé, on l'affiche, sinon on la cache
                if (rowText.includes(term)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});
</script>

@endsection
