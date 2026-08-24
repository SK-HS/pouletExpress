@extends('layouts.fournisseur.main')
@section('content')

   <main class="lg:ml-64 p-margin-mobile pb-28 md:p-margin-desktop space-y-8 animate-in fade-in duration-500 max-w-5xl">
        
        <!-- En-tête de la page -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 md:mb-8">
            <div>
                <h2 class="font-headline-lg-mobile md:font-headline-lg text-primary">Nouvelle Campagne Promo</h2>
                <p class="text-on-surface-variant text-sm md:text-body-md mt-1">Créez des réductions globales ou ciblées pour booster vos ventes.</p>
            </div>
            <div class="flex gap-2 md:gap-3">
                <a href="{{ route('Liste-Campagnes') }}" class="flex-1 md:flex-none px-4 md:px-6 py-2.5 rounded-xl font-body-md-bold text-on-surface-variant border border-outline hover:bg-surface-container-high transition-all text-sm md:text-base text-center">Annuler</a>
                <button form="form-campagne" type="submit" class="flex-[2] md:flex-none px-4 md:px-6 py-2.5 rounded-xl font-body-md-bold bg-primary text-on-primary hover:opacity-90 shadow-lg shadow-primary/10 transition-all flex items-center justify-center gap-2 text-sm md:text-base">
                    <span class="material-symbols-outlined text-lg">campaign</span>
                    Lancer la promo
                </button>
            </div>
        </div>
        
    <!-- Début du formulaire -->
    @if ($errors->any())
                    <div class="bg-error/10 border border-error text-error rounded-xl p-4 mb-6 text-sm">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                    @endif
                     @if(session('success'))
                            <div class="flex items-center gap-2 mb-4 p-4 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="material-symbols-outlined">
                                    check_circle
                                </span>

                                <span class="text-sm font-semibold">
                                    {{ session('success') }}
                                </span>
                            </div>
                        @endif
         @if(session('error'))
        <div class="flex items-center gap-2 mb-8 p-4 rounded-xl bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800">
            <span class="material-symbols-outlined">error</span>
            <span class="text-sm font-semibold">{{ session('error') }}</span>
        </div>
        @endif
    <form id="form-campagne" class="space-y-4 md:space-y-gutter" method="POST" action="{{ route('Save-Campagne-Fournisseur') }}">
        @csrf
        
        <!-- Section 1: Informations de la campagne -->
        <section class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-4 md:p-card-padding form-card">
            <div class="flex items-center gap-3 mb-4 md:mb-6">
                <div class="w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined">percent</span>
                </div>
                <h3 class="font-headline-md text-base md:text-headline-md">Détails de la réduction</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                
                <div class="col-span-1 md:col-span-2">
                    <label class="block font-body-md-bold text-sm md:text-on-surface mb-2">Titre de la campagne</label>
                    <input name="titre" value="{{ old('titre') }}" class="w-full h-14 px-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" placeholder="Ex: Grand déstockage de Noël, Soldes d'été..." required type="text"/>
                </div>

                <div class="relative">
                    <label class="block font-body-md-bold text-sm md:text-on-surface mb-2">Taux de remise</label>
                    <div class="relative">
                        <input required name="taux_remise" value="{{ old('taux_remise') }}" class="w-full h-14 px-4 pr-12 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" placeholder="Ex: 10" type="number" step="0.01" min="0.1" max="100"/>
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 font-body-lg-bold text-on-surface-variant">%</span>
                    </div>
                </div>

                <div>
                    <label class="block font-body-md-bold text-sm md:text-on-surface mb-2">Seuil de quantité (Optionnel)</label>
                    <div class="relative">
                        <input name="seuil_quantite" value="{{ old('seuil_quantite') }}" class="w-full h-14 px-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" placeholder="Ex: 15" type="number" min="1"/>
                        <p class="text-on-surface-variant text-[10px] md:text-xs mt-2 italic">Laissez vide si la remise s'applique dès le 1er article.</p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Section 2: Ciblage des produits -->
        <section class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-4 md:p-card-padding form-card">
            <div class="flex items-center gap-3 mb-4 md:mb-6">
                <div class="w-10 h-10 rounded-lg bg-secondary-container/20 text-on-secondary-container flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined">inventory_2</span>
                </div>
                <h3 class="font-headline-md text-base md:text-headline-md">Produits ciblés</h3>
            </div>
            
            <div class="grid grid-cols-1 gap-4 md:gap-6">
                <div class="p-3 md:p-4 bg-primary/5 rounded-xl border border-primary/10 mb-2">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary text-lg md:text-xl shrink-0">info</span>
                        <div>
                            <p class="font-body-md-bold text-primary text-xs md:text-sm">Conseil d'application</p>
                            <p class="text-[10px] md:text-xs text-on-surface-variant leading-relaxed">Si vous ne sélectionnez aucun produit, cette promotion s'appliquera automatiquement à <strong>toute votre boutique</strong>.</p>
                        </div>
                    </div>
                </div>
                
            <!-- 1. Le sélecteur du type de promotion -->
<div class="mb-6">
    <label class="block font-body-md-bold text-sm md:text-on-surface mb-2">Cible de la promotion</label>
    <select name="type" id="type_cible" class="w-full h-14 px-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface cursor-pointer">
        <option value="TOUT" {{ old('type') == 'TOUT' ? 'selected' : '' }}>TOUS LES PRODUITS (Boutique entière)</option>
        <option value="SPECIFIQUE" {{ old('type') == 'SPECIFIQUE' ? 'selected' : '' }}>PRODUITS SPÉCIFIQUES</option>
    </select>
</div>

<!-- 2. La zone des produits (Cachée par défaut via Javascript) -->
<div id="zone_produits" class="transition-all duration-300">
    <label class="block font-body-md-bold text-sm md:text-on-surface mb-2">Sélectionnez les produits concernés</label>
    
    <div class="w-full max-h-[250px] overflow-y-auto p-2 rounded-xl border-2 border-outline-variant bg-surface-container-lowest">
        @forelse ($produits as $produit)
            <label class="flex items-center gap-3 p-3 hover:bg-surface-container-low rounded-lg cursor-pointer transition-colors border-b border-outline-variant/30 last:border-0">
                <input type="checkbox" name="produits[]" value="{{ $produit->id }}" 
                       class="w-5 h-5 text-primary border-2 border-outline-variant rounded focus:ring-primary focus:ring-2 transition-all cursor-pointer"
                       {{ (is_array(old('produits')) && in_array($produit->id, old('produits'))) ? 'checked' : '' }}>
                
                <span class="text-on-surface font-body-md-bold text-sm">
                    {{ $produit->produit?->nom }} 
                    <span class="text-on-surface-variant font-normal ml-1">({{ number_format($produit->prix, 0, ',', ' ') }} CFA)</span>
                </span>
            </label>
        @empty
            <div class="p-4 text-center">
                <p class="text-sm text-on-surface-variant italic">Vous n'avez enregistré aucun produit pour le moment.</p>
            </div>
        @endforelse
    </div>
</div>


            </div>
        </section>
        
        <!-- Section 3: Planification (Dates) -->
        <section class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-4 md:p-card-padding form-card">
            <div class="flex items-center gap-3 mb-4 md:mb-6">
                <div class="w-10 h-10 rounded-lg bg-status-info/10 text-status-info flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined">calendar_month</span>
                </div>
                <h3 class="font-headline-md text-base md:text-headline-md">Période de validité</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                <div>
                    <label class="block font-body-md-bold text-sm md:text-on-surface mb-2">Date de début</label>
                    <input required name="date_debut" value="{{ old('date_debut') }}" class="w-full h-14 px-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" type="datetime-local"/>
                </div>
                
                <div>
                    <label class="block font-body-md-bold text-sm md:text-on-surface mb-2">Date de fin</label>
                    <input required name="date_fin" value="{{ old('date_fin') }}" class="w-full h-14 px-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" type="datetime-local"/>
                </div>
            </div>
        </section>
        
        <!-- Action Footer -->
        <div class="pt-6 md:pt-8 border-t border-outline-variant flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4 w-full md:w-auto">
                <label class="relative inline-flex items-center cursor-pointer">
                    <!-- Statut actif/inactif de la campagne -->
                    <input name="est_active" class="sr-only peer" type="checkbox" value="1" {{ old('est_active', true) ? 'checked' : '' }}/>
                    <div class="w-11 h-6 bg-surface-variant peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                    <span class="ml-3 font-body-md-bold text-sm md:text-on-surface">Activer la campagne</span>
                </label>
            </div>
            
            <div class="flex items-center gap-3 w-full md:w-auto">
                <button class="flex-1 md:flex-none px-6 py-3 rounded-xl font-body-md-bold text-on-surface-variant border border-outline hover:bg-surface-container-high transition-all text-sm md:text-base" type="reset">Réinitialiser</button>
                <button class="flex-[2] md:flex-none px-6 md:px-8 py-3 rounded-xl font-body-md-bold bg-primary text-on-primary hover:opacity-90 shadow-lg shadow-primary/20 transition-all text-sm md:text-base" type="submit">Sauvegarder la promo</button>
            </div>
        </div>
        
    </form>
    </main>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const selectType = document.getElementById("type_cible");
        const zoneProduits = document.getElementById("zone_produits");
        const checkboxes = zoneProduits.querySelectorAll("input[type='checkbox']");

        function basculerAffichage() {
            if (selectType.value === "SPECIFIQUE") {
                // On affiche la zone
                zoneProduits.style.display = "block";
            } else {
                // On cache la zone
                zoneProduits.style.display = "none";
                
                // Très important : On décoche toutes les cases pour ne pas 
                // envoyer de faux produits au Controller si on repasse sur "TOUT"
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = false;
                });
            }
        }

        // On écoute chaque changement du menu déroulant
        selectType.addEventListener("change", basculerAffichage);
        
        // On lance la fonction une fois au démarrage de la page
        // (Utile si Laravel recharge la page avec une erreur et que le old('type_cible') est mémorisé)
        basculerAffichage();
    });
</script>


@endsection
