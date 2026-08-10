@extends('layouts.fournisseur.main')
@section('content')

   <main class="lg:ml-64 p-margin-mobile pb-28 md:p-margin-desktop space-y-8 animate-in fade-in duration-500 max-w-5xl">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 md:mb-8">
            <div>
                <h2 class="font-headline-lg-mobile md:font-headline-lg text-primary">Ajouter un Produit</h2>
                <p class="text-on-surface-variant text-sm md:text-body-md mt-1">Enregistrez un nouveau produit dans votre catalogue de ferme.</p>
            </div>
            <div class="flex gap-2 md:gap-3">
                <button class="flex-1 md:flex-none px-4 md:px-6 py-2.5 rounded-xl font-body-md-bold text-on-surface-variant border border-outline hover:bg-surface-container-high transition-all text-sm md:text-base">Annuler</button>
                <button class="flex-[2] md:flex-none px-4 md:px-6 py-2.5 rounded-xl font-body-md-bold bg-primary text-on-primary hover:opacity-90 shadow-lg shadow-primary/10 transition-all flex items-center justify-center gap-2 text-sm md:text-base">
                    <span class="material-symbols-outlined text-lg">save</span>
                    Publier
                </button>
            </div>
        </div>
        
    <form class="space-y-4 md:space-y-gutter" method="POST" action="{{route('Save-Produit-Fournisseur')}}" enctype="multipart/form-data">
    @csrf
    
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
                    @foreach ($produits as $produit)
                        <option value="{{ $produit->id }}" {{ old('produit_id') == $produit->id ? 'selected' : '' }}>
                            {{ $produit->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-body-md-bold text-sm md:text-on-surface mb-2">Nom du produit</label>
                <!-- J'ai ajouté name="nom_produit" ici -->
                <input name="nom_produit" value="{{ old('nom_produit') }}" class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" placeholder="Ex: Poulets de chair (4 semaines)" required type="text"/>
            </div>
            
            <div>
                <label class="block font-body-md-bold text-sm md:text-on-surface mb-2">Catégorie</label>
                <select name="categorie_id" required class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface">
                    <option value="">Sélectionnez une catégorie</option>
                    @foreach ($categories as $categorie)
                        <option value="{{ $categorie->id }}" {{ old('categorie_id') == $categorie->id ? 'selected' : '' }}>
                            {{ $categorie->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-body-md-bold text-sm md:text-on-surface mb-2">Poids / Taille</label>
                <select name="taille_id" class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface">
                    <option value="">Sélectionnez un poids</option>
                    @foreach ($tailles as $taille)
                        <option value="{{ $taille->id }}" {{ old('taille_id') == $taille->id ? 'selected' : '' }}>
                            {{ $taille->taille }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-span-1 md:col-span-2">
                <label class="block font-body-md-bold text-sm md:text-on-surface mb-2">Description</label>
                <!-- Pour un textarea, le old() se met entre les balises, pas dans un attribut value -->
                <textarea name="description" class="w-full h-14 pt-4 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" placeholder="Décrivez les caractéristiques du produit..." rows="4">{{ old('description') }}</textarea>
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
                    <input required name="prix" value="{{ old('prix') }}" class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" placeholder="0" type="number"/>
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 font-label-caps text-[10px] md:text-label-caps text-on-surface-variant">FCFA</span>
                </div>
            </div>
            <div>
                <label class="block font-body-md-bold text-sm md:text-on-surface mb-2">Quantité totale</label>
                <input required name="quantite" value="{{ old('quantite') }}" class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" placeholder="Ex: 500" type="number"/>
            </div>
            <div class="sm:col-span-2 md:col-span-1">
                <label class="block font-body-md-bold text-sm md:text-on-surface mb-2">Commande min.</label>
                <input name="commande_min" value="{{ old('commande_min') }}" class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" placeholder="Ex: 10" type="number"/>
            </div>
        </div>
    </section>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-gutter">
        <!-- Section 3: Media -->
        <section class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-4 md:p-card-padding form-card flex flex-col h-full">
            <div class="flex items-center gap-3 mb-4 md:mb-6">
                <div class="w-10 h-10 rounded-lg bg-status-info/10 text-status-info flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined">image</span>
                </div>
                <h3 class="font-headline-md text-base md:text-headline-md">Photos du produit</h3>
            </div>
            <div class="flex-1 border-2 border-dashed border-outline-variant rounded-2xl flex flex-col items-center justify-center p-6 md:p-8 text-center cursor-pointer hover:bg-surface-container-low transition-all group min-h-[180px]" id="drop-zone">
                <div class="w-12 h-12 md:w-16 md:h-16 rounded-full bg-surface-container-high flex items-center justify-center mb-3 md:mb-4 group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-2xl md:text-3xl text-on-surface-variant">upload_file</span>
                </div>
                <p class="font-body-md-bold text-sm md:text-base text-on-surface">Glissez vos photos ici</p>
                <p class="text-on-surface-variant text-[10px] md:text-sm mt-1">PNG, JPG (max. 5MB)</p>
                <button class="mt-4 px-4 py-2 bg-surface-variant rounded-lg text-xs md:text-sm font-bold hover:bg-outline-variant transition-colors" type="button">Parcourir</button>
                <!-- Note : Les champs type="file" ne peuvent pas garder leur valeur via old() pour des raisons de sécurité du navigateur -->
                <input name="images[]" class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface " multiple type="file"/>
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
                        <input name="temps_preparation" value="{{ old('temps_preparation') }}" class="w-full border-none bg-transparent p-0 focus:ring-0 text-sm md:text-base" placeholder="30 min, 1 heure, 1 semaine" type="text"/>
                    </div>
                    <p class="text-on-surface-variant text-[10px] md:text-xs mt-2 italic">Délai moyen commande/expédition.</p>
                </div>
                <div class="p-3 md:p-4 bg-primary/5 rounded-xl border border-primary/10">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary text-lg md:text-xl shrink-0">info</span>
                        <div>
                            <p class="font-body-md-bold text-primary text-xs md:text-sm">Conseil de vente</p>
                            <p class="text-[10px] md:text-xs text-on-surface-variant leading-relaxed">Les produits avec des photos claires et des descriptions précises reçoivent en moyenne 40% de commandes en plus.</p>
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
                <!-- La case reste cochée si on la coche et qu'il y a une erreur, ou elle est cochée par défaut (true) -->
                <input name="etat" class="sr-only peer" type="checkbox" value="1" {{ old('etat', true) ? 'checked' : '' }}/>
                <div class="w-11 h-6 bg-surface-variant peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                <span class="ml-3 font-body-md-bold text-sm md:text-on-surface">Activer immédiatement</span>
            </label>
        </div>
        <div class="flex items-center gap-3 w-full md:w-auto">
            <button class="flex-1 md:flex-none px-6 py-3 rounded-xl font-body-md-bold text-on-surface-variant border border-outline hover:bg-surface-container-high transition-all text-sm md:text-base" type="reset">Effacer</button>
            <button class="flex-[2] md:flex-none px-6 md:px-8 py-3 rounded-xl font-body-md-bold bg-primary text-on-primary hover:opacity-90 shadow-lg shadow-primary/20 transition-all text-sm md:text-base" type="submit">Enregistrer le produit</button>
        </div>
    </div>
</form>

    </main>
    

@endsection