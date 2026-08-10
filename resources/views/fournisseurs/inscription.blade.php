
<!DOCTYPE html>
<html class="light" lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>{{config('app.name')}} | Inscription Éleveur</title>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@100..900&family=JetBrains+Mono:wght@100..900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <script src="{{asset('assets/fournisseur/js/tailwind-config.js')}}"></script>
    <link rel="stylesheet" href="{{asset('assets/fournisseur/css/main.css')}}">
</head>
<body class="bg-background font-body-md text-on-surface min-h-screen overflow-x-hidden">
    <div class="flex min-h-screen">
        <!-- Side Illustration / Branding Canvas -->
        <div class="hidden lg:flex w-5/12 relative bg-primary-container overflow-hidden items-center justify-center">
            <div class="absolute inset-0 z-0">
                <div class="w-full h-full bg-cover bg-center opacity-40 mix-blend-overlay" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuC9_qKvLrOrL9sHSqBTMvUgk1zA-k2izbdoRjdObb4QRj8IcL1wEVk4QwVOSWN5LW477-6OjlBE471nO1OrEdTnUVPTYGE8G0M3FN9bGH_vAC1IZeew770WE3oLbI5PIbYRMTGZeWR7pNMAg7X4zmxZobWE0luuL693bwGyGq3QxpHfr79OGmrv6ilpW6Rw96UBTYM9FUN8QSuuAQoYZSRoiU_LdU7t1qP55x2-UaXUGAnEf8qb6jTyQebuJsD_rbW2ejMlOT57iBg')"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-primary-container via-transparent to-transparent opacity-80"></div>
            </div>
            <div class="relative z-10 p-12 text-on-primary">
                <img alt="AgriManager Logo" class="h-16 mb-8 filter brightness-0 invert" src="https://lh3.googleusercontent.com/aida/AP1WRLttKA77ABGaQ_h945A0tu1wuK_db0agLA7ebq40A9Z5k_zktRzrk9Gz4Fs9VWJuIPV5lY5Ci1iavfeAe6wBhxNKUda0BvE97y3HKbV-IJ7QuMx3A8Fvn71OdnlwPvjDIUbjzy4f2LUB7jD0tm6qAzwiqYs5c70dkhiZJMJfUsd2q74zOJvG_XYBR7eNUgiDgi2n_XKWHahbPoiJTQq8NoQ4RtiCdV2IlrY1nk5l4z9Y6DlJq60uEFonFRo"/>
                <h1 class="font-headline-lg text-headline-lg mb-4">Devenez partenaire de la révolution avicole.</h1>
                <p class="font-body-lg text-body-lg text-primary-fixed mb-8">Rejoignez PouletExpress et connectez directement votre production aux marchés urbains avec une logistique optimisée par AgriManager.</p>
                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <span class="material-symbols-outlined p-2 bg-on-primary/10 rounded-lg">payments</span>
                        <div>
                            <h3 class="font-body-md-bold text-body-md-bold">Paiements Garantis</h3>
                            <p class="text-sm opacity-80">Recevez vos fonds via MTN, Orange ou Moov dès la livraison.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <span class="material-symbols-outlined p-2 bg-on-primary/10 rounded-lg">insights</span>
                        <div>
                            <h3 class="font-body-md-bold text-body-md-bold">Suivi en Temps Réel</h3>
                            <p class="text-sm opacity-80">Visualisez vos ventes et stocks depuis votre mobile.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Registration Form Side -->
        <main class="w-full lg:w-7/12 flex flex-col p-6 md:p-12 lg:p-16 justify-center items-center bg-surface">
            <div class="w-full max-w-xl">
                <!-- Mobile Header -->
                <div class="lg:hidden flex items-center justify-between mb-8">
                    <img alt="AgriManager Logo" class="h-10" src="https://lh3.googleusercontent.com/aida/AP1WRLttKA77ABGaQ_h945A0tu1wuK_db0agLA7ebq40A9Z5k_zktRzrk9Gz4Fs9VWJuIPV5lY5Ci1iavfeAe6wBhxNKUda0BvE97y3HKbV-IJ7QuMx3A8Fvn71OdnlwPvjDIUbjzy4f2LUB7jD0tm6qAzwiqYs5c70dkhiZJMJfUsd2q74zOJvG_XYBR7eNUgiDgi2n_XKWHahbPoiJTQq8NoQ4RtiCdV2IlrY1nk5l4z9Y6DlJq60uEFonFRo"/>
                    <span class="font-label-sm text-label-sm uppercase tracking-widest text-primary">Inscription Éleveur</span>
                </div>
                <div class="mb-10">
                    <h2 class="font-headline-lg text-headline-lg text-on-surface mb-2">Inscription Éleveur</h2>
                    <p class="text-on-surface-variant font-body-md">Créez votre compte professionnel en 3 étapes simples.</p>
                </div>
                
                <!-- Step Progress -->
                <div class="flex items-center gap-4 mb-12">
                    <div class="step-indicator active w-10 h-10 rounded-full flex items-center justify-center font-body-md-bold border-2 border-primary transition-all" id="ind-1">1</div>
                    <div class="h-px bg-outline-variant flex-1"></div>
                    <div class="step-indicator w-10 h-10 rounded-full flex items-center justify-center font-body-md-bold border-2 border-outline-variant text-on-surface-variant transition-all" id="ind-2">2</div>
                    <div class="h-px bg-outline-variant flex-1"></div>
                    <div class="step-indicator w-10 h-10 rounded-full flex items-center justify-center font-body-md-bold border-2 border-outline-variant text-on-surface-variant transition-all" id="ind-3">3</div>
                </div>
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
                <form method="POST" action="{{route('Save-fournisseur-Inscription')}}" enctype="multipart/form-data" class="space-y-8" id="regForm">
                    @csrf
                    <!-- Step 1: Personal Info -->
                    <div class="form-step active animate-in fade-in slide-in-from-bottom-4 duration-500" id="step-1">
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="font-label-caps text-label-caps text-on-surface-variant">Nom Complet</label>
                                <input name="nom" value="{{ old('nom') }}" required class="w-full h-14 px-4 rounded-lg border-2 border-outline-variant focus:border-primary focus:ring-0 transition-colors bg-white" placeholder="ex: Jean Kouassi" type="text"/>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="font-label-caps text-label-caps text-on-surface-variant">Téléphone</label>
                                    <div class="flex">
                                        <span class="h-14 px-4 flex items-center bg-surface-container border-2 border-r-0 border-outline-variant rounded-l-lg text-on-surface-variant">+225</span>
                                        <input name="telephone" value="{{ old('telephone') }}" required class="w-full h-14 px-4 rounded-r-lg border-2 border-outline-variant focus:border-primary focus:ring-0 transition-colors bg-white" placeholder="07 00 00 00 00" type="tel"/>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="font-label-caps text-label-caps text-on-surface-variant">Contact</label>
                                    <div class="flex">
                                        <span class="h-14 px-4 flex items-center bg-surface-container border-2 border-r-0 border-outline-variant rounded-l-lg text-on-surface-variant">+225</span>
                                        <input name="contact" value="{{ old('contact') }}" class="w-full h-14 px-4 rounded-r-lg border-2 border-outline-variant focus:border-primary focus:ring-0 transition-colors bg-white" placeholder="05 01 00 00 00" type="text"/>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="font-label-caps text-label-caps text-on-surface-variant">Email</label>
                                    <input name="email" value="{{ old('email') }}" class="w-full h-14 px-4 rounded-lg border-2 border-outline-variant focus:border-primary focus:ring-0 transition-colors bg-white" placeholder="jean.kouassi@exemple.com" type="email"/>
                                </div>
                                <div class="space-y-2">
                                    <label class="font-label-caps text-label-caps text-on-surface-variant">Adresse</label>
                                    <input name="adresse" value="{{ old('adresse') }}" required class="w-full h-14 px-4 rounded-lg border-2 border-outline-variant focus:border-primary focus:ring-0 transition-colors bg-white" placeholder="Cocody riviera 2" type="text"/>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="font-label-caps text-label-caps text-on-surface-variant">Type Fournisseur</label>
                                <!-- Retrait de 'multiple' et '[]' car le champ 'type' n'est pas casté en array dans le Model -->
                                <select name="type" required class="w-full h-14 px-4 rounded-lg border-2 border-outline-variant focus:border-primary focus:ring-0 transition-colors bg-white">
                                    <option value="" disabled {{ old('type') ? '' : 'selected' }}>Sélectionnez un type</option>
                                    <option value="Éleveur" {{ old('type') == 'Éleveur' ? 'selected' : '' }}>Éleveur</option>
                                    <option value="Grossiste" {{ old('type') == 'Grossiste' ? 'selected' : '' }}>Grossiste</option>
                                    <option value="Transporteur" {{ old('type') == 'Transporteur' ? 'selected' : '' }}>Transporteur</option>
                                    <option value="Prestataire" {{ old('type') == 'Prestataire' ? 'selected' : '' }}>Prestataire</option>
                                    <option value="AUTRE" {{ old('type') == 'AUTRE' ? 'selected' : '' }}>AUTRE</option>
                                </select>
                            </div>
                           <div class="space-y-2">
                            <label class="font-label-caps text-label-caps text-on-surface-variant">Mot de Passe</label>
                            <div class="relative">
                                <input id="passwordInput" name="password" required class="w-full h-14 pl-4 pr-12 rounded-lg border-2 border-outline-variant focus:border-primary focus:ring-0 transition-colors bg-white" placeholder="••••••••" type="password"/>
                                <button type="button" id="togglePasswordBtn" class="absolute right-2 top-1/2 -translate-y-1/2 p-2 text-on-surface-variant hover:text-primary transition-colors flex items-center justify-center outline-none">
                                    <span class="material-symbols-outlined select-none" id="togglePasswordIcon">visibility</span>
                                </button>
                            </div>
                        </div>

                        </div>
                    </div>
                    
                    <!-- Step 2: Farm Details -->
                    <div class="form-step animate-in fade-in slide-in-from-bottom-4 duration-500" id="step-2">
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="font-label-caps text-label-caps text-on-surface-variant">Nom de l'Exploitation</label>
                                <input name="nom_ferme" value="{{ old('nom_ferme') }}" required class="w-full h-14 px-4 rounded-lg border-2 border-outline-variant focus:border-primary focus:ring-0 transition-colors bg-white" placeholder="Ferme Avicole Espoir" type="text"/>
                            </div>
                            <div class="space-y-2">
                                <label class="font-label-caps text-label-caps text-on-surface-variant">Nom du Dérant</label>
                                <input name="nom_gerant" value="{{ old('nom_gerant') }}" required class="w-full h-14 px-4 rounded-lg border-2 border-outline-variant focus:border-primary focus:ring-0 transition-colors bg-white" placeholder="Kouame Ange" type="text"/>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="font-label-caps text-label-caps text-on-surface-variant">Ville / Localité</label>
                                    <select id="quartier_id" name="quartier_id" required class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 transition appearance-none">
                                        <option value="">Choisissez votre zone</option>
                                        @foreach($quartiers as $quartier)
                                            <option value="{{ $quartier->id }}" {{ old('quartier_id') == $quartier->id ? 'selected' : '' }}>{{ $quartier->nom_quartier}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="font-label-caps text-label-caps text-on-surface-variant">Type de Volaille</label>
                                    <!-- Ici 'type_produit' est casté en array, donc on garde 'multiple' et les crochets '[]' -->
                                    <select multiple name="type_produit[]" required class="w-full h-14 px-4 rounded-lg border-2 border-outline-variant focus:border-primary focus:ring-0 transition-colors bg-white">
                                        <option value="POULET CHAIRE" {{ (is_array(old('type_produit')) && in_array('POULET CHAIRE', old('type_produit'))) ? 'selected' : '' }}>POULET CHAIRE</option>
                                        <option value="PONDEUSES" {{ (is_array(old('type_produit')) && in_array('PONDEUSES', old('type_produit'))) ? 'selected' : '' }}>PONDEUSES</option>
                                        <option value="OEUF" {{ (is_array(old('type_produit')) && in_array('OEUF', old('type_produit'))) ? 'selected' : '' }}>OEUF</option>
                                        <option value="POULETS BICYCLETTE" {{ (is_array(old('type_produit')) && in_array('POULETS BICYCLETTE', old('type_produit'))) ? 'selected' : '' }}>POULETS BICYCLETTE</option>
                                        <option value="AUTRE" {{ (is_array(old('type_produit')) && in_array('AUTRE', old('type_produit'))) ? 'selected' : '' }}>AUTRE</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- GPS Coordinates Block -->
                            <div class="space-y-4 p-5 border-2 border-outline-variant rounded-xl bg-surface-container-low/50 relative overflow-hidden">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div>
                                        <label class="font-label-caps text-label-caps text-on-surface-variant block mb-1">Coordonnées GPS de la ferme</label>
                                        <p class="text-sm text-on-surface-variant">Si vous êtes actuellement sur votre ferme, récupérez votre position exacte.</p>
                                    </div>
                                    <button type="button" id="getLocationBtn" class="flex items-center justify-center gap-2 px-4 py-2 bg-primary/10 text-primary font-body-md-bold rounded-lg hover:bg-primary/20 transition-colors whitespace-nowrap">
                                        <span class="material-symbols-outlined text-lg">my_location</span>
                                        Ma position
                                    </button>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <input type="text" id="latitude" name="latitude" value="{{ old('latitude') }}" readonly class="w-full h-12 px-4 rounded-lg border-2 border-outline-variant bg-surface-container-low text-on-surface-variant outline-none" placeholder="Latitude (ex: 5.345317)"/>
                                    <input type="text" id="longitude" name="longitude" value="{{ old('longitude') }}" readonly class="w-full h-12 px-4 rounded-lg border-2 border-outline-variant bg-surface-container-low text-on-surface-variant outline-none" placeholder="Longitude (ex: -4.024429)"/>
                                </div>
                                <p id="locationError" class="text-sm text-error hidden font-body-md-bold"></p>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="font-label-caps text-label-caps text-on-surface-variant">Capacité de Production Mensuelle</label>
                                <input name="capacite_ferme" value="{{ old('capacite_ferme') }}" class="w-full h-14 px-4 rounded-lg border-2 border-outline-variant focus:border-primary focus:ring-0 transition-colors bg-white" placeholder="ex: 5000" type="number"/>
                            </div>
                             <div class="space-y-2">
                                <label class="font-body-md-bold text-on-surface-variant ml-1" for="description">Description</label>
                                <textarea class="w-full h-14 px-4 rounded-lg border-2 border-outline-variant focus:border-primary focus:ring-0 transition-colors bg-surface dark:bg-surface-container-highest text-on-surface" id="description" name="description" placeholder="Description de votre ferme" required rows="3">{{ old('description') }}</textarea>
                                </div>
                        </div>
                    </div>
                    
                    <!-- Step 3: Documents -->
                    <div class="form-step animate-in fade-in slide-in-from-bottom-4 duration-500" id="step-3">
                        <div class="space-y-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="p-8 border-2 border-dashed border-outline rounded-xl bg-surface-container-low flex flex-col items-center justify-center text-center">
                                    <span class="material-symbols-outlined text-primary text-2xl mb-4">badge</span>
                                    <h4 class="font-body-md-bold text-body-md-bold mb-2">Pièce d'identité (CNI / Passeport)</h4>
                                    <p class="text-sm text-on-surface-variant mb-6">Format JPG, PNG ou PDF. Max 5MB.</p>
                                    <input required class="w-full h-14 px-4 rounded-lg border-2 border-outline-variant focus:border-primary focus:ring-0 transition-colors bg-white"  name="piece_fournisseur" type="file"/>
                                </div>
                                <div class="p-8 border-2 border-dashed border-outline rounded-xl bg-surface-container-low flex flex-col items-center justify-center text-center">
                                    <span class="material-symbols-outlined text-primary text-2xl mb-4">verified_user</span>
                                    <h4 class="font-body-md-bold text-body-md-bold mb-2">Certifications Sanitaires (Optionnel)</h4>
                                    <p class="text-sm text-on-surface-variant mb-6">Ajoutez vos certificats pour obtenir le badge 'Vérifié'.</p>
                                    <input class="w-full h-14 px-4 rounded-lg border-2 border-outline-variant focus:border-primary focus:ring-0 transition-colors bg-white"  name="certification_sanitaire" type="file"/>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="p-8 border-2 border-dashed border-outline rounded-xl bg-surface-container-low flex flex-col items-center justify-center text-center">
                                    <span class="material-symbols-outlined text-primary text-2xl mb-4">verified_user</span>
                                    <h4 class="font-body-md-bold text-body-md-bold mb-2">Photo Ferme (Optionnel)</h4>
                                    <p class="text-sm text-on-surface-variant mb-6">Ajoutez une image de la ferme.</p>
                                    <input class="w-full h-14 px-4 rounded-lg border-2 border-outline-variant focus:border-primary focus:ring-0 transition-colors bg-white"  name="image_ferme" type="file"/>
                                </div>
                                <div class="p-8 border-2 border-dashed border-outline rounded-xl bg-surface-container-low flex flex-col items-center justify-center text-center">
                                    <span class="material-symbols-outlined text-primary text-2xl mb-4">verified_user</span>
                                    <h4 class="font-body-md-bold text-body-md-bold mb-2">Photo Gérant (Optionnel)</h4>
                                    <p class="text-sm text-on-surface-variant mb-6">Ajoutez une image du Gérant de la ferme.</p>
                                    <input class="w-full h-14 px-4 rounded-lg border-2 border-outline-variant focus:border-primary focus:ring-0 transition-colors bg-white"  name="image" type="file"/>
                                </div>
                            </div>
                            
                            <label class="flex items-start gap-3 cursor-pointer group">
                                <input class="mt-1 w-5 h-5 rounded border-2 border-outline text-primary focus:ring-primary" type="checkbox" name="cgu" required {{ old('cgu') ? 'checked' : '' }}/>
                                <span class="text-sm text-on-surface-variant leading-relaxed">
                                    J'accepte les <a class="text-primary font-bold hover:underline" href="#">Conditions Générales d'Utilisation</a> et la <a class="text-primary font-bold hover:underline" href="#">Politique de Confidentialité</a> de PouletExpress.
                                </span>
                            </label>

                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex gap-4 pt-4">
                        <button class="hidden flex-1 h-14 border-2 border-outline-variant text-on-surface font-body-md-bold rounded-lg hover:bg-surface-container-high transition-colors" id="prevBtn" type="button">Précédent</button>
                        <button class="flex-1 h-14 bg-primary text-on-primary font-body-md-bold rounded-lg hover:opacity-90 active:scale-[0.98] transition-all shadow-lg shadow-primary/20" id="nextBtn" type="button">Suivant</button>
                    </div>
                </form>
                
                <p class="mt-8 text-center text-on-surface-variant font-body-md">
                    Vous avez déjà un compte ? <a class="text-primary font-body-md-bold hover:underline" href="{{route('Fournisseur-Login')}}">Se connecter</a>
                </p>
            </div>
        </main>
    </div>

    <script src="{{asset('assets/fournisseur/js/inscription.js')}}"></script>
    
    <!-- Script pour la Géolocalisation -->
    <script>
        document.getElementById('getLocationBtn').addEventListener('click', function() {
            const btn = this;
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            const errorMsg = document.getElementById('locationError');
            
            errorMsg.classList.add('hidden');
            errorMsg.textContent = '';
            
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span class="material-symbols-outlined text-lg animate-spin">sync</span> Recherche...';
            btn.disabled = true;

            if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        latInput.value = position.coords.latitude;
                        lngInput.value = position.coords.longitude;
                        
                        btn.innerHTML = '<span class="material-symbols-outlined text-lg">check_circle</span> Position trouvée';
                        btn.classList.remove('bg-primary/10', 'text-primary');
                        btn.classList.add('bg-green-100', 'text-green-700');
                        
                        setTimeout(() => {
                            btn.innerHTML = originalText;
                            btn.disabled = false;
                            btn.classList.add('bg-primary/10', 'text-primary');
                            btn.classList.remove('bg-green-100', 'text-green-700');
                        }, 4000);
                    },
                    function(error) {
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                        errorMsg.classList.remove('hidden');
                        
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                errorMsg.textContent = "Vous avez refusé la demande de géolocalisation. Veuillez l'autoriser dans votre navigateur.";
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMsg.textContent = "Les informations de localisation sont indisponibles.";
                                break;
                            case error.TIMEOUT:
                                errorMsg.textContent = "La demande pour obtenir votre position a expiré.";
                                break;
                            default:
                                errorMsg.textContent = "Une erreur inconnue s'est produite lors de la géolocalisation.";
                                break;
                        }
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            } else {
                btn.innerHTML = originalText;
                btn.disabled = false;
                errorMsg.classList.remove('hidden');
                errorMsg.textContent = "La géolocalisation n'est pas supportée par votre navigateur.";
            }
        });

    </script>
    <!-- Script pour afficher/masquer le mot de passe -->
<script>
    document.getElementById('togglePasswordBtn').addEventListener('click', function() {
        const passwordInput = document.getElementById('passwordInput');
        const icon = document.getElementById('togglePasswordIcon');
        
        // Bascule le type de l'input entre 'password' et 'text'
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.textContent = 'visibility_off'; // Change l'icône (œil barré)
        } else {
            passwordInput.type = 'password';
            icon.textContent = 'visibility'; // Change l'icône (œil normal)
        }
    });
</script>

</body>
</html>