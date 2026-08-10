@extends('layouts.fournisseur.main')
@section('content')

 <main class="lg:ml-64 p-margin-mobile pb-28 md:p-margin-desktop space-y-8 animate-in fade-in duration-500">
     <div class="w-full">
        <!-- Mobile Header -->
        <div class="lg:hidden flex items-center justify-between mb-8">
            <img alt="AgriManager Logo" class="h-10" src="https://lh3.googleusercontent.com/aida/AP1WRLttKA77ABGaQ_h945A0tu1wuK_db0agLA7ebq40A9Z5k_zktRzrk9Gz4Fs9VWJuIPV5lY5Ci1iavfeAe6wBhxNKUda0BvE97y3HKbV-IJ7QuMx3A8Fvn71OdnlwPvjDIUbjzy4f2LUB7jD0tm6qAzwiqYs5c70dkhiZJMJfUsd2q74zOJvG_XYBR7eNUgiDgi2n_XKWHahbPoiJTQq8NoQ4RtiCdV2IlrY1nk5l4z9Y6DlJq60uEFonFRo"/>
            <span class="font-label-sm text-label-sm uppercase tracking-widest text-primary">Modifier Profil</span>
        </div>
        
        <div class="mb-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline-lg text-headline-lg text-on-surface mb-2">Modifier mon Profil</h2>
                <p class="text-on-surface-variant font-body-md">Mettez à jour les informations de votre compte et de votre ferme.</p>
            </div>
            <a href="{{ route('Fournisseur-Espace') }}" class="px-5 py-2.5 bg-surface-container border border-outline-variant text-on-surface rounded-xl font-label-md hover:bg-surface-container-highest transition-colors flex items-center gap-2 whitespace-nowrap">
                <span class="material-symbols-outlined text-sm">close</span>
                Annuler
            </a>
        </div>

        @if ($errors->any())
        <div class="bg-error/10 border border-error text-error rounded-xl p-4 mb-8 text-sm">
            @foreach ($errors->all() as $error)
                <p>• {{ $error }}</p>
            @endforeach
        </div>
        @endif

        @if(session('success'))
        <div class="flex items-center gap-2 mb-8 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
            <span class="material-symbols-outlined">check_circle</span>
            <span class="text-sm font-semibold">{{ session('success') }}</span>
        </div>
        @endif

        <form method="POST" action="{{route('Update-Fournisseu-Profil')}}" enctype="multipart/form-data" class="space-y-8" id="regForm">
            @csrf
              @method('PUT')
            <!-- SECTION 1: Informations Personnelles -->
            <div class="bg-surface-container-lowest p-6 sm:p-8 rounded-2xl border border-outline-variant space-y-6">
                <h3 class="font-headline-sm text-primary border-b border-outline-variant pb-3 flex items-center gap-2">
                    <span class="material-symbols-outlined">person</span> Contact & Identité
                </h3>
                
                <div class="space-y-2">
                    <label class="font-label-caps text-label-caps text-on-surface-variant">Nom Complet</label>
                    <input name="nom" value="{{ old('nom', $fournisseur->nom) }}" required class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" type="text"/>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="font-label-caps text-label-caps text-on-surface-variant">Téléphone</label>
                        <div class="flex">
                            <span class="h-14 px-4 flex items-center bg-surface-container border-2 border-r-0 border-outline-variant rounded-l-lg text-on-surface-variant">+225</span>
                            <input name="telephone" value="{{ old('telephone', $fournisseur->telephone) }}" required class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" type="tel"/>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="font-label-caps text-label-caps text-on-surface-variant">Contact Secondaire</label>
                        <div class="flex">
                            <span class="h-14 px-4 flex items-center bg-surface-container border-2 border-r-0 border-outline-variant rounded-l-lg text-on-surface-variant">+225</span>
                            <input name="contact" value="{{ old('contact', $fournisseur->contact) }}" class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" type="text"/>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="font-label-caps text-label-caps text-on-surface-variant">Email</label>
                        <input name="email" value="{{ old('email', $fournisseur->email) }}" class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" type="email"/>
                    </div>
                    <div class="space-y-2">
                        <label class="font-label-caps text-label-caps text-on-surface-variant">Adresse</label>
                        <input name="adresse" value="{{ old('adresse', $fournisseur->adresse) }}" required class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" type="text"/>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="font-label-caps text-label-caps text-on-surface-variant">Type Fournisseur</label>
                        @php $type = old('type', $fournisseur->type); @endphp
                        <select name="type" required class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface">
                            <option value="Éleveur" {{ $type == 'Éleveur' ? 'selected' : '' }}>Éleveur</option>
                            <option value="Grossiste" {{ $type == 'Grossiste' ? 'selected' : '' }}>Grossiste</option>
                            <option value="Transporteur" {{ $type == 'Transporteur' ? 'selected' : '' }}>Transporteur</option>
                            <option value="Prestataire" {{ $type == 'Prestataire' ? 'selected' : '' }}>Prestataire</option>
                            <option value="AUTRE" {{ $type == 'AUTRE' ? 'selected' : '' }}>AUTRE</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="font-label-caps text-label-caps text-on-surface-variant">Mot de Passe (Laisser vide pour ne pas modifier)</label>
                        <div class="relative">
                            <input id="passwordInput" name="password" class="w-full h-14 pl-12 pr-12 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" placeholder="••••••••" type="password"/>
                            <button type="button" id="togglePasswordBtn" class="absolute right-2 top-1/2 -translate-y-1/2 p-2 text-on-surface-variant hover:text-primary transition-colors flex items-center justify-center outline-none">
                                <span class="material-symbols-outlined select-none" id="togglePasswordIcon">visibility</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- SECTION 2: Ferme & Exploitation -->
            <div class="bg-surface-container-lowest p-6 sm:p-8 rounded-2xl border border-outline-variant space-y-6">
                <h3 class="font-headline-sm text-primary border-b border-outline-variant pb-3 flex items-center gap-2">
                    <span class="material-symbols-outlined">agriculture</span> Ferme & Exploitation
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="font-label-caps text-label-caps text-on-surface-variant">Nom de l'Exploitation</label>
                        <input name="nom_ferme" value="{{ old('nom_ferme', $fournisseur->nom_ferme) }}" required class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" type="text"/>
                    </div>
                    <div class="space-y-2">
                        <label class="font-label-caps text-label-caps text-on-surface-variant">Nom du Gérant</label>
                        <input name="nom_gerant" value="{{ old('nom_gerant', $fournisseur->nom_gerant) }}" required class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" type="text"/>
                    </div>
                    <div class="space-y-2">
                        <label class="font-label-caps text-label-caps text-on-surface-variant">Ville / Localité</label>
                        <select id="quartier_id" name="quartier_id" required class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface">
                            @php $qId = old('quartier_id', $fournisseur->quartier_id); @endphp
                            @foreach($quartiers as $quartier)
                                <option value="{{ $quartier->id }}" {{ $qId == $quartier->id ? 'selected' : '' }}>{{ $quartier->nom_quartier}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="font-label-caps text-label-caps text-on-surface-variant">Capacité Mensuelle (Têtes)</label>
                        <input name="capacite_ferme" value="{{ old('capacite_ferme', $fournisseur->capacite_ferme) }}" class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" type="number"/>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="font-label-caps text-label-caps text-on-surface-variant">Type de Volaille</label>
                    @php $typesProd = old('type_produit', is_array($fournisseur->type_produit) ? $fournisseur->type_produit : []); @endphp
                    <select multiple name="type_produit[]" required class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface">
                        <option value="POULET CHAIRE" {{ in_array('POULET CHAIRE', $typesProd) ? 'selected' : '' }}>POULET CHAIRE</option>
                        <option value="PONDEUSES" {{ in_array('PONDEUSES', $typesProd) ? 'selected' : '' }}>PONDEUSES</option>
                        <option value="OEUF" {{ in_array('OEUF', $typesProd) ? 'selected' : '' }}>OEUF</option>
                        <option value="POULETS BICYCLETTE" {{ in_array('POULETS BICYCLETTE', $typesProd) ? 'selected' : '' }}>POULETS BICYCLETTE</option>
                        <option value="AUTRE" {{ in_array('AUTRE', $typesProd) ? 'selected' : '' }}>AUTRE</option>
                    </select>
                </div>

                <!-- GPS Coordinates Block avec le bouton Automatique -->
                <div class="space-y-4 p-5 border-2 border-outline-variant rounded-xl bg-surface-container-low/50 relative overflow-hidden mt-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <label class="font-label-caps text-label-caps text-on-surface-variant block mb-1">Coordonnées GPS de la ferme</label>
                            <p class="text-sm text-on-surface-variant">Mettez à jour votre position actuelle en un clic si vous êtes sur la ferme.</p>
                        </div>
                        <button type="button" id="getLocationBtn" class="flex items-center justify-center gap-2 px-6 py-2.5 bg-primary text-on-primary font-body-md-bold rounded-lg shadow-lg shadow-primary/20 hover:opacity-90 active:scale-95 transition-all whitespace-nowrap">
                            <span class="material-symbols-outlined text-lg">my_location</span>
                            Mettre à jour la position
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input type="text" id="latitude" name="latitude" value="{{ old('latitude', $fournisseur->latitude) }}" readonly class="w-full h-12 px-4 rounded-lg border-2 border-outline-variant bg-surface-container-lowest text-on-surface-variant outline-none cursor-not-allowed" placeholder="Latitude"/>
                        <input type="text" id="longitude" name="longitude" value="{{ old('longitude', $fournisseur->longitude) }}" readonly class="w-full h-12 px-4 rounded-lg border-2 border-outline-variant bg-surface-container-lowest text-on-surface-variant outline-none cursor-not-allowed" placeholder="Longitude"/>
                    </div>
                    <p id="locationError" class="text-sm text-error hidden font-body-md-bold"></p>
                </div>
                <div class="space-y-2">
                <label class="font-body-md-bold text-on-surface-variant ml-1" for="description">Description</label>
                <textarea class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" id="description" name="description" placeholder="Description de votre ferme" required rows="3">{{ old('description', $fournisseur->description) }}</textarea>
                </div>
            </div>
            
            <!-- SECTION 3: Documents et Images -->
            <div class="bg-surface-container-lowest p-6 sm:p-8 rounded-2xl border border-outline-variant space-y-6">
                <h3 class="font-headline-sm text-primary border-b border-outline-variant pb-3 flex items-center gap-2">
                    <span class="material-symbols-outlined">folder</span> Documents & Images
                </h3>
                
                <div class="bg-primary/10 text-primary p-4 rounded-xl flex gap-3 text-sm mb-6">
                    <span class="material-symbols-outlined">info</span>
                    <p>Laissez les champs vides si vous ne souhaitez pas modifier les documents/images actuels.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-6 border-2 border-dashed border-outline-variant rounded-xl bg-surface-container-low flex flex-col items-center justify-center text-center">
                        <span class="material-symbols-outlined text-primary text-2xl mb-2">badge</span>
                        <h4 class="font-body-md-bold text-body-md-bold mb-1">Nouvelle Pièce d'identité</h4>
                        <p class="text-xs text-on-surface-variant mb-4">Format JPG, PNG, PDF. Max 5MB.</p>
                        <input class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" name="piece_fournisseur" type="file"/>
                    </div>
                    <div class="p-6 border-2 border-dashed border-outline-variant rounded-xl bg-surface-container-low flex flex-col items-center justify-center text-center">
                        <span class="material-symbols-outlined text-primary text-2xl mb-2">health_and_safety</span>
                        <h4 class="font-body-md-bold text-body-md-bold mb-1">Nouvelle Certif. Sanitaire</h4>
                        <p class="text-xs text-on-surface-variant mb-4">Format JPG, PNG, PDF. Max 5MB.</p>
                        <input class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" name="certification_sanitaire" type="file"/>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-6 border-2 border-dashed border-outline-variant rounded-xl bg-surface-container-low flex flex-col items-center justify-center text-center">
                        <span class="material-symbols-outlined text-primary text-2xl mb-2">landscape</span>
                        <h4 class="font-body-md-bold text-body-md-bold mb-1">Nouvelle Photo Ferme</h4>
                        <p class="text-xs text-on-surface-variant mb-4">Remplace l'image actuelle de la ferme.</p>
                        <input class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" name="image_ferme" type="file"/>
                    </div>
                    <div class="p-6 border-2 border-dashed border-outline-variant rounded-xl bg-surface-container-low flex flex-col items-center justify-center text-center">
                        <span class="material-symbols-outlined text-primary text-2xl mb-2">face</span>
                        <h4 class="font-body-md-bold text-body-md-bold mb-1">Nouvelle Photo Gérant</h4>
                        <p class="text-xs text-on-surface-variant mb-4">Remplace votre photo de profil actuelle.</p>
                        <input class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" name="image" type="file"/>
                    </div>
                </div>
            </div>

            <!-- Bouton Sauvegarder Final -->
            <div class="pt-4 pb-10">
                <button type="submit" class="w-full flex items-center justify-center gap-2 h-14 bg-primary text-on-primary font-headline-sm rounded-xl hover:opacity-90 active:scale-[0.98] transition-all shadow-xl shadow-primary/20">
                    <span class="material-symbols-outlined">save</span>
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</main>

<!-- Script pour le bouton GPS automatique et le mot de passe (MÊME QUE PRÉCÉDEMMENT) -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle Mot de passe
        const togglePasswordBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('passwordInput');
        const togglePasswordIcon = document.getElementById('togglePasswordIcon');
        
        if(togglePasswordBtn) {
            togglePasswordBtn.addEventListener('click', function() {
                if(passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    togglePasswordIcon.textContent = 'visibility_off';
                } else {
                    passwordInput.type = 'password';
                    togglePasswordIcon.textContent = 'visibility';
                }
            });
        }

        // Géolocalisation GPS
        const getLocationBtn = document.getElementById('getLocationBtn');
        if(getLocationBtn) {
            getLocationBtn.addEventListener('click', function() {
                const btn = this;
                const latInput = document.getElementById('latitude');
                const lngInput = document.getElementById('longitude');
                const errorMsg = document.getElementById('locationError');
                
                errorMsg.classList.add('hidden');
                errorMsg.textContent = '';
                
                const originalText = btn.innerHTML;
                btn.innerHTML = '<span class="material-symbols-outlined text-sm animate-spin">sync</span> Recherche...';
                btn.disabled = true;

                if ("geolocation" in navigator) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            latInput.value = position.coords.latitude;
                            lngInput.value = position.coords.longitude;
                            
                            btn.innerHTML = '<span class="material-symbols-outlined text-sm">check_circle</span> Position trouvée';
                            btn.classList.remove('bg-primary', 'text-on-primary');
                            btn.classList.add('bg-green-600', 'text-white');
                            
                            setTimeout(() => {
                                btn.innerHTML = originalText;
                                btn.disabled = false;
                                btn.classList.add('bg-primary', 'text-on-primary');
                                btn.classList.remove('bg-green-600', 'text-white');
                            }, 3000);
                        },
                        function(error) {
                            btn.innerHTML = originalText;
                            btn.disabled = false;
                            errorMsg.classList.remove('hidden');
                            errorMsg.textContent = "Vous avez refusé la demande ou le GPS est indisponible.";
                        },
                        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                    );
                } else {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    errorMsg.classList.remove('hidden');
                    errorMsg.textContent = "La géolocalisation n'est pas supportée par votre navigateur.";
                }
            });
        }
    });
</script>


@endsection