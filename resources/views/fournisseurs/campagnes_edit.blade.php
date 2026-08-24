@extends('layouts.fournisseur.main')
@section('content')

   <main class="lg:ml-64 p-margin-mobile pb-28 md:p-margin-desktop space-y-8 animate-in fade-in duration-500 max-w-5xl">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 md:mb-8">
            <div>
                <h2 class="font-headline-lg-mobile md:font-headline-lg text-primary">Modifier la Campagne</h2>
                <p class="text-on-surface-variant text-sm md:text-body-md mt-1">Mettez à jour les détails de votre promotion.</p>
            </div>
            <div class="flex gap-2 md:gap-3">
                <a href="{{ route('Liste-Campagnes') }}" class="flex-1 md:flex-none px-4 md:px-6 py-2.5 rounded-xl font-body-md-bold text-on-surface-variant border border-outline hover:bg-surface-container-high transition-all text-sm md:text-base text-center">Annuler</a>
                <button form="form-campagne" type="submit" class="flex-[2] md:flex-none px-4 md:px-6 py-2.5 rounded-xl font-body-md-bold bg-primary text-on-primary hover:opacity-90 shadow-lg shadow-primary/10 transition-all flex items-center justify-center gap-2 text-sm md:text-base">
                    <span class="material-symbols-outlined text-lg">save</span>
                    Mettre à jour
                </button>
            </div>
        </div>
        
    <!-- FORMULAIRE DE MISE A JOUR -->
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
    <form id="form-campagne" class="space-y-4 md:space-y-gutter" method="POST" action="{{ route('Update-Campagne-Fournisseur', $campagne->id) }}">
        @csrf
        @method('PUT') <!-- Très important pour les updates dans Laravel -->
        
        <!-- Section 1: Informations -->
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
                    <input name="titre" value="{{ old('titre', $campagne->titre) }}" class="w-full h-14 px-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 bg-surface-container-lowest text-on-surface" required type="text"/>
                </div>

                <div class="relative">
                    <label class="block font-body-md-bold text-sm md:text-on-surface mb-2">Taux de remise</label>
                    <div class="relative">
                        <input required name="taux_remise" value="{{ old('taux_remise', $campagne->taux_remise) }}" class="w-full h-14 px-4 pr-12 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 bg-surface-container-lowest text-on-surface" type="number" step="0.01" min="0.1" max="100"/>
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 font-body-lg-bold text-on-surface-variant">%</span>
                    </div>
                </div>

                <div>
                    <label class="block font-body-md-bold text-sm md:text-on-surface mb-2">Seuil de quantité (Optionnel)</label>
                    <input name="seuil_quantite" value="{{ old('seuil_quantite', $campagne->seuil_quantite) }}" class="w-full h-14 px-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 bg-surface-container-lowest text-on-surface" type="number" min="1"/>
                </div>
            </div>
        </section>
        
        <!-- Section 2: Cible (Le Script JS est inclus en bas) -->
        <section class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-4 md:p-card-padding form-card">
            
            <!-- Choix du Type -->
            <div class="mb-6">
                <label class="block font-body-md-bold text-sm md:text-on-surface mb-2">Cible de la promotion</label>
                <select name="type" id="type_cible" class="w-full h-14 px-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 bg-surface-container-lowest text-on-surface cursor-pointer">
                    <!-- Si la campagne a des produits, on présélectionne SPECIFIQUE -->
                    @php 
                        $hasProducts = count($produits_selectionnes) > 0;
                    @endphp
                    <option value="TOUT" {{ old('type_cible', $hasProducts ? 'SPECIFIQUE' : 'TOUT') == 'TOUT' ? 'selected' : '' }}>TOUS LES PRODUITS (Boutique entière)</option>
                    <option value="SPECIFIQUE" {{ old('type_cible', $hasProducts ? 'SPECIFIQUE' : 'TOUT') == 'SPECIFIQUE' ? 'selected' : '' }}>PRODUITS SPÉCIFIQUES</option>
                </select>
            </div>

            <!-- Cases à cocher -->
            <div id="zone_produits" class="transition-all duration-300">
                <label class="block font-body-md-bold text-sm md:text-on-surface mb-2">Sélectionnez les produits concernés</label>
                <div class="w-full max-h-[250px] overflow-y-auto p-2 rounded-xl border-2 border-outline-variant bg-surface-container-lowest">
                    @forelse ($produits as $produit)
                        @php
                            // Est-ce que le produit est coché (soit parce qu'il l'est en base, soit à cause d'une erreur de form)
                            $isChecked = is_array(old('produits')) 
                                ? in_array($produit->id, old('produits')) 
                                : in_array($produit->id, $produits_selectionnes);
                        @endphp
                        <label class="flex items-center gap-3 p-3 hover:bg-surface-container-low rounded-lg cursor-pointer border-b border-outline-variant/30 last:border-0">
                            <input type="checkbox" name="produits[]" value="{{ $produit->id }}" 
                                   class="w-5 h-5 text-primary border-2 border-outline-variant rounded focus:ring-primary"
                                   {{ $isChecked ? 'checked' : '' }}>
                            <span class="text-on-surface font-body-md-bold text-sm">
                                {{ $produit->produit->nom }} <span class="text-on-surface-variant font-normal ml-1">({{ number_format($produit->prix, 0, ',', ' ') }} CFA)</span>
                            </span>
                        </label>
                    @empty
                        <div class="p-4 text-center"><p class="text-sm text-on-surface-variant italic">Aucun produit disponible.</p></div>
                    @endforelse
                </div>
            </div>
        </section>
        
        <!-- Section 3: Dates -->
        <section class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-4 md:p-card-padding form-card">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                <div>
                    <label class="block font-body-md-bold text-sm md:text-on-surface mb-2">Date de début</label>
                    <input required name="date_debut" value="{{ old('date_debut', \Carbon\Carbon::parse($campagne->date_debut)->format('Y-m-d\TH:i')) }}" class="w-full h-14 px-4 rounded-xl border-2 border-outline-variant focus:border-primary bg-surface-container-lowest text-on-surface" type="datetime-local"/>
                </div>
                <div>
                    <label class="block font-body-md-bold text-sm md:text-on-surface mb-2">Date de fin</label>
                    <input required name="date_fin" value="{{ old('date_fin', \Carbon\Carbon::parse($campagne->date_fin)->format('Y-m-d\TH:i')) }}" class="w-full h-14 px-4 rounded-xl border-2 border-outline-variant focus:border-primary bg-surface-container-lowest text-on-surface" type="datetime-local"/>
                </div>
            </div>
        </section>
        
        <!-- Activer/Désactiver -->
        <div class="pt-6 md:pt-8 border-t border-outline-variant flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4 w-full md:w-auto">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input name="est_active" class="sr-only peer" type="checkbox" value="1" {{ old('est_active', $campagne->est_active) ? 'checked' : '' }}/>
                    <div class="w-11 h-6 bg-surface-variant peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                    <span class="ml-3 font-body-md-bold text-sm md:text-on-surface">Campagne Active</span>
                </label>
            </div>
        </div>
        
    </form>
    </main>

    <!-- Le script pour gérer le menu déroulant TOUT / SPECIFIQUE -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const selectType = document.getElementById("type_cible");
            const zoneProduits = document.getElementById("zone_produits");
            const checkboxes = zoneProduits.querySelectorAll("input[type='checkbox']");

            function basculerAffichage() {
                if (selectType.value === "SPECIFIQUE") {
                    zoneProduits.style.display = "block";
                } else {
                    zoneProduits.style.display = "none";
                    checkboxes.forEach(cb => cb.checked = false);
                }
            }

            selectType.addEventListener("change", basculerAffichage);
            basculerAffichage(); // Exécution au chargement
        });
    </script>
@endsection