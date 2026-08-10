
@extends('layouts.fournisseur.main')

@section('content')
<main class="lg:ml-64 p-margin-mobile pb-28 md:p-margin-desktop space-y-8 animate-in fade-in duration-500">
    
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 mb-8 md:mb-10">
        <div>
            <h3 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-primary">Modifier le Produit</h3>
            <p class="text-on-surface-variant font-body-md">Mettez à jour les informations de votre offre.</p>
        </div>
        <a href="{{ route('Fournisseur-Catalogue') }}" class="flex bg-primary items-center justify-center gap-2  text-on-surface px-6 md:px-8 py-3 md:py-4 rounded-xl font-body-md-bold hover:bg-primary/800 transition-all w-full sm:w-auto border border-outline-variant">
            <span class="material-symbols-outlined">arrow_back</span>
            <span>Retour au catalogue</span>
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-error-container text-on-error-container p-4 rounded-xl border border-error/20 mb-6">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form class="space-y-4 md:space-y-gutter" method="POST" action="{{ route('Save-Edite-Fournisseur-Produit', $produit->id) }}" enctype="multipart/form-data">
        @csrf
        <!-- Important: Spécifier qu'il s'agit d'une mise à jour si vous utilisez une route PUT/PATCH -->
        @method('PUT')
        
        <!-- Section 1: Basic Info -->
        <section class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-4 md:p-card-padding form-card">
            <div class="flex items-center gap-3 mb-4 md:mb-6">
                <div class="w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined">description</span>
                </div>
                <h3 class="font-headline-md text-base md:text-headline-md">Informations de base</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                <div>
                    <label class="block font-body-md-bold text-sm md:text-on-surface mb-2">Produit</label>
                    <select required name="produit_id" class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface">
                        <option value="">Sélectionnez un produit</option>
                        @foreach ($produits as $p)
                            <option value="{{ $p->id }}" {{ old('produit_id', $produit->produit_id) == $p->id ? 'selected' : '' }}>
                                {{ $p->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block font-body-md-bold text-sm md:text-on-surface mb-2">Nom du produit (Optionnel)</label>
                    <input name="nom_produit" value="{{ old('nom_produit', $produit->nom_produit) }}" class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" placeholder="Ex: Poulets de chair (4 semaines)" type="text"/>
                </div>
                
                <div>
                    <label class="block font-body-md-bold text-sm md:text-on-surface mb-2">Catégorie</label>
                    <select name="categorie_id" required class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface">
                        <option value="">Sélectionnez une catégorie</option>
                        @foreach ($categories as $categorie)
                            <option value="{{ $categorie->id }}" {{ old('categorie_id', $produit->categorie_id) == $categorie->id ? 'selected' : '' }}>
                                {{ $categorie->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block font-body-md-bold text-sm md:text-on-surface mb-2">Poids / Taille</label>
                    <select name="taille_id"  class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface">
                        <option value="">Sélectionnez un poids</option>
                        @foreach ($tailles as $taille)
                            <option value="{{ $taille->id }}" {{ old('taille_id', $produit->taille_id) == $taille->id ? 'selected' : '' }}>
                                {{ $taille->taille }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-span-1 md:col-span-2">
                    <label class="block font-body-md-bold text-sm md:text-on-surface mb-2">Description</label>
                    <textarea name="description" class="w-full h-14 pt-4 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" rows="4">{{ old('description', $produit->description) }}</textarea>
                </div>
            </div>
        </section>
        
        <!-- Section 2: Pricing and Stock -->
        <section class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-4 md:p-card-padding form-card">
            <div class="flex items-center gap-3 mb-4 md:mb-6">
                <div class="w-10 h-10 rounded-lg bg-secondary-container/20 text-on-secondary-container flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined">payments</span>
                </div>
                <h3 class="font-headline-md text-base md:text-headline-md">Prix et Stock</h3>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 md:gap-6">
                <div class="relative">
                    <label class="block font-body-md-bold text-sm md:text-on-surface mb-2">Prix par unité</label>
                    <div class="relative">
                        <input required name="prix" value="{{ old('prix', $produit->prix) }}" class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" type="number"/>
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 font-label-caps text-[10px] md:text-label-caps text-on-surface-variant">FCFA</span>
                    </div>
                </div>
                <div>
                    <label class="block font-body-md-bold text-sm md:text-on-surface mb-2">Quantité totale en stock</label>
                    <input required name="quantite" value="{{ old('quantite', $produit->quantite) }}" class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" type="number"/>
                </div>
                <div class="sm:col-span-2 md:col-span-1">
                    <label class="block font-body-md-bold text-sm md:text-on-surface mb-2">Commande min.</label>
                    <input name="commande_min" value="{{ old('commande_min', $produit->commande_min) }}" class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" type="number"/>
                </div>
            </div>
        </section>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-gutter">
            
            <!-- Section 3: Media -->
            <section class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-4 md:p-card-padding form-card flex flex-col h-full">
                <div class="flex items-center justify-between mb-4 md:mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-status-info/10 text-status-info flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined">image</span>
                        </div>
                        <h3 class="font-headline-md text-base md:text-headline-md">Photos du produit</h3>
                    </div>
                </div>

                <!-- Affichage des images existantes -->
                @php
                    $imagesArray = $produit->images;
                    $hasImages = is_array($imagesArray) && count($imagesArray) > 0;
                @endphp

                @if($hasImages)
                <div class="mb-4">
                    <p class="text-xs text-on-surface-variant mb-2">Images actuelles :</p>
                    <div class="flex gap-2 overflow-x-auto">
                        @foreach($imagesArray as $img)
                            <img src="{{ asset('storage/'.$img) }}" class="w-16 h-16 object-cover rounded-lg border border-outline-variant" alt="Image produit">
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="flex-1 border-2 border-dashed border-outline-variant rounded-2xl flex flex-col items-center justify-center p-6 md:p-8 text-center cursor-pointer hover:bg-surface-container-low transition-all group min-h-[180px]" id="drop-zone">
                    <div class="w-12 h-12 md:w-16 md:h-16 rounded-full bg-surface-container-high flex items-center justify-center mb-3 md:mb-4 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-2xl md:text-3xl text-on-surface-variant">upload_file</span>
                    </div>
                    <p class="font-body-md-bold text-sm md:text-base text-on-surface">Ajouter de nouvelles photos</p>
                    <p class="text-on-surface-variant text-[10px] md:text-sm mt-1">Laissez vide pour conserver les images actuelles.</p>
                    <button class="mt-4 px-4 py-2 bg-surface-variant rounded-lg text-xs md:text-sm font-bold hover:bg-outline-variant transition-colors" type="button">Parcourir</button>
                    <!-- Input file caché -->
                    <input name="images[]" class="w-full h-14 " multiple type="file"/>
                </div>
            </section>
            
            <!-- Section 4: Delivery Details -->
            <section class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-4 md:p-card-padding form-card">
                <div class="flex items-center gap-3 mb-4 md:mb-6">
                    <div class="w-10 h-10 rounded-lg bg-primary-container text-on-primary-container flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">local_shipping</span>
                    </div>
                    <h3 class="font-headline-md text-base md:text-headline-md">Logistique</h3>
                </div>
                
                <div class="space-y-4 md:space-y-6">
                    <div>
                        <label class="block font-body-md-bold text-sm md:text-on-surface mb-2">Temps de préparation</label>
                        <div class="flex items-center border border-outline-variant bg-surface-container-low rounded-xl px-4 py-3 focus-within:border-primary focus-within:ring-4 focus-within:ring-primary/10 transition-all">
                            <input name="temps_preparation" value="{{ old('temps_preparation', $produit->temps_preparation) }}" class="w-full border-none bg-transparent p-0 focus:ring-0 text-sm md:text-base" placeholder="Ex: 24h" type="text"/>
                        </div>
                    </div>
                    <div class="p-3 md:p-4 bg-primary/5 rounded-xl border border-primary/10">
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-primary text-lg md:text-xl shrink-0">info</span>
                            <div>
                                <p class="font-body-md-bold text-primary text-xs md:text-sm">Conseil</p>
                                <p class="text-[10px] md:text-xs text-on-surface-variant leading-relaxed">Mettez à jour vos prix régulièrement pour rester compétitif sur le marché.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        
        <!-- Action Footer -->
        <div class="pt-6 md:pt-8 border-t border-outline-variant flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4 w-full md:w-auto">
                <label class="relative inline-flex items-center cursor-pointer">
                    @php 
                        // Vérifie si le produit est actif (ajustez selon votre base)
                        $isActif = old('etat', ($produit->etat == 1 || $produit->statuts == 1) ? true : false);
                    @endphp
                    <input name="etat" class="sr-only peer" type="checkbox" value="1" {{ $isActif ? 'checked' : '' }}/>
                    <div class="w-11 h-6 bg-surface-variant peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                    <span class="ml-3 font-body-md-bold text-sm md:text-on-surface">Produit Actif / Visible</span>
                </label>
            </div>
            <div class="flex items-center gap-3 w-full md:w-auto">
                <a href="{{ route('Fournisseur-Catalogue') }}" class="flex-1 text-center md:flex-none px-6 py-3 rounded-xl font-body-md-bold text-on-surface-variant border border-outline hover:bg-surface-container-high transition-all text-sm md:text-base">
                    Annuler
                </a>
                <button class="flex-[2] md:flex-none px-6 md:px-8 py-3 rounded-xl font-body-md-bold bg-primary text-on-primary hover:opacity-90 shadow-lg shadow-primary/20 transition-all text-sm md:text-base" type="submit">
                    Enregistrer les modifications
                </button>
            </div>
        </div>
    </form>
</main>
@endsection