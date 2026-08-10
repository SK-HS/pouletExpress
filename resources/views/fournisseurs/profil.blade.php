@extends('layouts.fournisseur.main')
@section('content')

    <!-- Main Content Area (PROFIL) -->
    <main class="lg:ml-64 p-margin-mobile pb-28 md:p-margin-desktop space-y-8 animate-in fade-in duration-500">
        
        <!-- En-tête du profil (Cover + Avatar) -->
        <section class="bg-surface-container-lowest rounded-2xl border border-outline-variant overflow-hidden group">
            <div class="h-32 md:h-48 bg-gradient-to-r from-primary to-primary-container relative">
                @if($fournisseur->image_ferme)
                    <img src="{{ asset('storage/' . $fournisseur->image_ferme) }}" class="w-full h-full object-cover opacity-60 mix-blend-overlay" alt="Cover Ferme">
                @endif
                
                <!-- Badge Statut -->
                <div class="absolute top-4 right-4">
                    @if($fournisseur->etat === 0)
                        <span class="bg-secondary-container text-on-secondary-container px-3 py-1 rounded-full font-label-sm border border-secondary-container flex items-center gap-1 shadow-sm">
                            <span class="material-symbols-outlined text-sm">pending</span> En validation
                        </span>
                    @else
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full font-label-sm border border-green-200 flex items-center gap-1 shadow-sm">
                            <span class="material-symbols-outlined text-sm">check_circle</span> Compte Actif
                        </span>
                    @endif
                </div>
            </div>
            
            <div class="px-6 pb-6">
                <div class="flex flex-col sm:flex-row items-center sm:items-end gap-4 sm:gap-6 -mt-16 sm:-mt-20 relative z-10">
                    <div class="w-32 h-32 sm:w-40 sm:h-40 rounded-full border-4 border-surface-container-lowest bg-surface-container overflow-hidden shadow-lg flex items-center justify-center">
                        @if($fournisseur->image)
                            <img src="{{ asset('storage/' . $fournisseur->image) }}" class="w-full h-full object-cover" alt="Photo {{ $fournisseur->nom }}">
                        @else
                            <span class="material-symbols-outlined text-6xl text-on-surface-variant">person</span>
                        @endif
                    </div>
                    
                    <div class="flex-1 text-center sm:text-left mb-2">
                        <h1 class="font-headline-lg text-on-surface mb-1">{{ $fournisseur->nom }}</h1>
                        <p class="font-body-md text-on-surface-variant flex items-center justify-center sm:justify-start gap-1">
                            <span class="material-symbols-outlined text-sm">badge</span> 
                            Référence : <span class="font-bold text-primary">{{ $fournisseur->reference }}</span>
                        </p>
                    </div>
                    
                    <div class="mb-2 w-full sm:w-auto flex flex-col sm:flex-row gap-2">
                        {{-- <button class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-6 py-2.5 bg-surface-container-highest text-on-surface rounded-full font-body-md-bold hover:bg-surface-variant transition-all">
                            <span class="material-symbols-outlined text-sm">settings</span>
                            Paramètres
                        </button> --}}
                        <a href="{{route('Modification-Profil')}}" class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-6 py-2.5 bg-primary text-on-primary rounded-full font-body-md-bold shadow-lg shadow-primary/20 hover:opacity-90 active:scale-95 transition-all">
                            <span class="material-symbols-outlined text-sm">edit</span>
                            Modifier
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">
            <!-- Colonne de gauche (Infos personnelles) -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Carte Informations Personnelles -->
                <div class="bg-surface-container-lowest p-card-padding rounded-2xl border border-outline-variant hover:border-primary transition-colors">
                    <h2 class="font-headline-sm text-on-surface mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">contact_page</span>
                        Contact & Identité
                    </h2>
                    
                    <ul class="space-y-5">
                        <li class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-lg bg-surface-container-highest flex items-center justify-center text-on-surface flex-shrink-0">
                                <span class="material-symbols-outlined">call</span>
                            </div>
                            <div>
                                <p class="font-label-caps text-label-caps text-on-surface-variant mb-1 text-[10px]">Téléphone Principal</p>
                                <p class="font-body-md-bold text-on-surface">{{ $fournisseur->telephone }}</p>
                            </div>
                        </li>
                        
                        @if($fournisseur->contact)
                        <li class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-lg bg-surface-container-highest flex items-center justify-center text-on-surface flex-shrink-0">
                                <span class="material-symbols-outlined">phone_iphone</span>
                            </div>
                            <div>
                                <p class="font-label-caps text-label-caps text-on-surface-variant mb-1 text-[10px]">Contact Secondaire</p>
                                <p class="font-body-md-bold text-on-surface">{{ $fournisseur->contact }}</p>
                            </div>
                        </li>
                        @endif

                        @if($fournisseur->email)
                        <li class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-lg bg-surface-container-highest flex items-center justify-center text-on-surface flex-shrink-0">
                                <span class="material-symbols-outlined">mail</span>
                            </div>
                            <div>
                                <p class="font-label-caps text-label-caps text-on-surface-variant mb-1 text-[10px]">Email</p>
                                <p class="font-body-md-bold text-on-surface break-all">{{ $fournisseur->email }}</p>
                            </div>
                        </li>
                        @endif
                        
                        <li class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-lg bg-surface-container-highest flex items-center justify-center text-on-surface flex-shrink-0">
                                <span class="material-symbols-outlined">location_on</span>
                            </div>
                            <div>
                                <p class="font-label-caps text-label-caps text-on-surface-variant mb-1 text-[10px]">Adresse</p>
                                <p class="font-body-md text-on-surface">{{ $fournisseur->adresse }}</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Colonne principale (Exploitation, GPS & Documents) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Carte Détails Exploitation -->
                <div class="bg-surface-container-lowest p-card-padding rounded-2xl border border-outline-variant hover:border-primary transition-colors">
                    <h2 class="font-headline-sm text-on-surface mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">agriculture</span>
                        Informations de l'Exploitation
                    </h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        <div class="bg-surface-container-low/50 border border-outline-variant p-4 rounded-xl">
                            <p class="font-label-caps text-label-caps text-on-surface-variant mb-1 text-[10px]">Nom de la Ferme</p>
                            <p class="font-headline-md text-on-surface">{{ $fournisseur->nom_ferme }}</p>
                        </div>
                        
                        <div class="bg-surface-container-low/50 border border-outline-variant p-4 rounded-xl">
                            <p class="font-label-caps text-label-caps text-on-surface-variant mb-1 text-[10px]">Gérant de la Ferme</p>
                            <p class="font-headline-md text-on-surface">{{ $fournisseur->nom_gerant }}</p>
                        </div>
                        
                        <div class="bg-surface-container-low/50 border border-outline-variant p-4 rounded-xl">
                            <p class="font-label-caps text-label-caps text-on-surface-variant mb-1 text-[10px]">Type d'Activité</p>
                            <span class="inline-block bg-primary/10 text-primary px-3 py-1 rounded-lg font-label-md mt-1">
                                {{ $fournisseur->type }}
                            </span>
                        </div>
                        
                        <div class="bg-surface-container-low/50 border border-outline-variant p-4 rounded-xl">
                            <p class="font-label-caps text-label-caps text-on-surface-variant mb-1 text-[10px]">Capacité Mensuelle</p>
                            <p class="font-headline-md text-on-surface">
                                {{ $fournisseur->capacite_ferme }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <p class="font-label-caps text-label-caps text-on-surface-variant mb-3 text-[10px]">Types de Volailles produits</p>
                        <div class="flex flex-wrap gap-2">
                            @if(is_array($fournisseur->type_produit))
                                @foreach($fournisseur->type_produit as $produit)
                                    <span class="bg-surface-container border border-outline-variant text-on-surface-variant px-4 py-2 rounded-full font-label-md flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">pets</span>
                                        {{ $produit }}
                                    </span>
                                @endforeach
                            @else
                                <span class="text-on-surface-variant text-sm italic">Aucun type renseigné</span>
                            @endif
                        </div>
                    </div>

                    @if($fournisseur->description)
                    <div class="mt-6 pt-6 border-t border-outline-variant">
                        <p class="font-label-caps text-label-caps text-on-surface-variant mb-3 text-[10px]">Description</p>
                        <p class="font-body-md text-on-surface">{{ $fournisseur->description }}</p>
                    </div>
                    @endif

                    <!-- Coordonnées GPS -->
                    <div class="mt-6 pt-6 border-t border-outline-variant">
                        <p class="font-label-caps text-label-caps text-on-surface-variant mb-4 text-[10px]">Localisation (Coordonnées GPS)</p>
                        
                        @if($fournisseur->latitude && $fournisseur->longitude)
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-surface-container-low/50 border border-outline-variant p-4 rounded-xl hover:border-primary/50 transition-colors">
    <div class="flex items-start sm:items-center gap-3 sm:gap-4 overflow-hidden w-full sm:w-auto">
        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center flex-shrink-0 mt-1 sm:mt-0">
            <span class="material-symbols-outlined text-lg sm:text-xl">my_location</span>
        </div>
        <div class="min-w-0 flex-1">
            <div class="flex flex-col sm:flex-row sm:gap-4 font-body-md-bold text-on-surface text-sm sm:text-base">
                <span class="truncate">Lat : <span class="font-medium text-primary">{{ $fournisseur->latitude }}</span></span>
                <span class="truncate">Lng : <span class="font-medium text-primary">{{ $fournisseur->longitude }}</span></span>
            </div>
            <p class="text-[11px] sm:text-xs text-on-surface-variant mt-0.5 sm:mt-1 truncate">Position géographique de la ferme</p>
        </div>
    </div>
    
    <!-- Bouton vers Google Maps -->
    <a href="https://www.google.com/maps?q={{ $fournisseur->latitude }},{{ $fournisseur->longitude }}" target="_blank" class="w-full sm:w-auto flex items-center justify-center gap-2 px-4 py-2.5 bg-surface-container-highest text-on-surface hover:text-primary hover:bg-primary/10 rounded-lg font-label-md transition-colors whitespace-nowrap flex-shrink-0">
        <span class="material-symbols-outlined text-sm">map</span>
        Voir la carte
    </a>
</div>

                        @else
                        <!-- Si aucune coordonnée, on affiche ce bloc avec le bouton d'ajout AJAX -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-surface-container/30 border border-outline-variant p-4 rounded-xl">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-surface-container-highest text-on-surface-variant flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined">location_disabled</span>
                                </div>
                                <div>
                                    <p class="text-sm font-body-md-bold text-on-surface">Coordonnées manquantes</p>
                                    <p class="text-[11px] text-on-surface-variant">Si vous êtes actuellement sur la ferme, ajoutez-les d'un clic.</p>
                                </div>
                            </div>
                            
                            <button id="updateLocationBtn" class="flex items-center justify-center gap-2 px-4 py-2 bg-primary/10 text-primary hover:bg-primary/20 rounded-lg font-label-md transition-colors whitespace-nowrap">
                                <span class="material-symbols-outlined text-sm">my_location</span>
                                Enregistrer ma position
                            </button>
                        </div>
                        <!-- Message de feedback caché par défaut -->
                        <p id="locationFeedback" class="text-xs font-body-md-bold mt-2 hidden"></p>
                        @endif
                    </div>
                </div>

                <!-- Documents et Justificatifs -->
                <div class="bg-surface-container-lowest p-card-padding rounded-2xl border border-outline-variant hover:border-secondary transition-colors">
                    <h2 class="font-headline-sm text-on-surface mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary">folder_open</span>
                        Documents & Justificatifs
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Pièce d'identité -->
                        <div class="flex items-start gap-4 p-4 border border-outline-variant rounded-xl {{ $fournisseur->piece_fournisseur ? 'bg-surface-container-low/50 hover:border-primary transition-colors group' : 'bg-surface-container/30' }}">
                            <div class="w-12 h-12 rounded-xl {{ $fournisseur->piece_fournisseur ? 'bg-primary/10 text-primary' : 'bg-surface-container-highest text-on-surface-variant' }} flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined">badge</span>
                            </div>
                            <div class="flex-1">
                                <p class="font-body-md-bold text-on-surface">Pièce d'Identité</p>
                                @if($fournisseur->piece_fournisseur)
                                    <p class="text-xs text-status-success flex items-center gap-1 mt-1 mb-2">
                                        <span class="material-symbols-outlined text-[14px]">check_circle</span> Document fourni
                                    </p>
                                    <a href="{{ asset('storage/' . $fournisseur->piece_fournisseur) }}" target="_blank" class="inline-flex items-center gap-1 text-sm font-label-md text-primary group-hover:underline">
                                        Voir le fichier <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                                    </a>
                                @else
                                    <p class="text-sm text-error mt-1 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">error</span> Manquant
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Certification sanitaire -->
                        <div class="flex items-start gap-4 p-4 border border-outline-variant rounded-xl {{ $fournisseur->certification_sanitaire ? 'bg-surface-container-low/50 hover:border-primary transition-colors group' : 'bg-surface-container/30' }}">
                            <div class="w-12 h-12 rounded-xl {{ $fournisseur->certification_sanitaire ? 'bg-primary/10 text-primary' : 'bg-surface-container-highest text-on-surface-variant' }} flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined">health_and_safety</span>
                            </div>
                            <div class="flex-1">
                                <p class="font-body-md-bold text-on-surface">Certificat Sanitaire</p>
                                @if($fournisseur->certification_sanitaire)
                                    <p class="text-xs text-status-success flex items-center gap-1 mt-1 mb-2">
                                        <span class="material-symbols-outlined text-[14px]">check_circle</span> Document fourni
                                    </p>
                                    <a href="{{ asset('storage/' . $fournisseur->certification_sanitaire) }}" target="_blank" class="inline-flex items-center gap-1 text-sm font-label-md text-primary group-hover:underline">
                                        Voir le fichier <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                                    </a>
                                @else
                                    <p class="text-sm text-on-surface-variant mt-1 italic">Optionnel (Non fourni)</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Pièce d'identité -->
                        <div class="flex items-start gap-4 p-4 border border-outline-variant rounded-xl {{ $fournisseur->image_ferme ? 'bg-surface-container-low/50 hover:border-primary transition-colors group' : 'bg-surface-container/30' }}">
                            <div class="w-12 h-12 rounded-xl {{ $fournisseur->image_ferme ? 'bg-primary/10 text-primary' : 'bg-surface-container-highest text-on-surface-variant' }} flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined">badge</span>
                            </div>
                            <div class="flex-1">
                                <p class="font-body-md-bold text-on-surface">Image de la Ferme</p>
                                @if($fournisseur->image_ferme)
                                    <p class="text-xs text-status-success flex items-center gap-1 mt-1 mb-2">
                                        <span class="material-symbols-outlined text-[14px]">check_circle</span> Image de la ferme fournie
                                    </p>
                                    <a href="{{ asset('storage/' . $fournisseur->image_ferme) }}" target="_blank" class="inline-flex items-center gap-1 text-sm font-label-md text-primary group-hover:underline">
                                        Voir le fichier <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                                    </a>
                                @else
                                    <p class="text-sm text-error mt-1 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">error</span> Manquant
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Certification sanitaire -->
                        <div class="flex items-start gap-4 p-4 border border-outline-variant rounded-xl {{ $fournisseur->image ? 'bg-surface-container-low/50 hover:border-primary transition-colors group' : 'bg-surface-container/30' }}">
                            <div class="w-12 h-12 rounded-xl {{ $fournisseur->image ? 'bg-primary/10 text-primary' : 'bg-surface-container-highest text-on-surface-variant' }} flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined">health_and_safety</span>
                            </div>
                            <div class="flex-1">
                                <p class="font-body-md-bold text-on-surface"> Image du Gérant</p>
                                @if($fournisseur->image)
                                    <p class="text-xs text-status-success flex items-center gap-1 mt-1 mb-2">
                                        <span class="material-symbols-outlined text-[14px]">check_circle</span> Image du Gérant de la ferme
                                    </p>
                                    <a href="{{ asset('storage/' . $fournisseur->image) }}" target="_blank" class="inline-flex items-center gap-1 text-sm font-label-md text-primary group-hover:underline">
                                        Voir le fichier <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                                    </a>
                                @else
                                    <p class="text-sm text-on-surface-variant mt-1 italic">Optionnel (Non fourni)</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

   
    <!-- Script pour la Géolocalisation AJAX -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const updateLocationBtn = document.getElementById('updateLocationBtn');
            
            if(updateLocationBtn) {
                updateLocationBtn.addEventListener('click', function() {
                    const btn = this;
                    const feedbackMsg = document.getElementById('locationFeedback');
                    
                    feedbackMsg.classList.add('hidden');
                    
                    // On met le bouton en mode chargement
                    const originalText = btn.innerHTML;
                    btn.innerHTML = '<span class="material-symbols-outlined text-sm animate-spin">sync</span> Enregistrement...';
                    btn.disabled = true;

                    if ("geolocation" in navigator) {
                        navigator.geolocation.getCurrentPosition(
                            function(position) {
                                const lat = position.coords.latitude;
                                const lng = position.coords.longitude;
                                
                                // Envoi AJAX vers Laravel
                                fetch("#", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
                                        "X-CSRF-TOKEN": "{{ csrf_token() }}" // Jeton de sécurité Laravel
                                    },
                                    body: JSON.stringify({ latitude: lat, longitude: lng })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if(data.success) {
                                        // Succès visuel
                                        btn.innerHTML = '<span class="material-symbols-outlined text-sm">check_circle</span> Enregistré !';
                                        btn.classList.replace('text-primary', 'text-green-700');
                                        btn.classList.replace('bg-primary/10', 'bg-green-100');
                                        
                                        // Recharge la page pour afficher la nouvelle UI avec la carte Google Maps
                                        setTimeout(() => {
                                            window.location.reload(); 
                                        }, 1500);
                                    } else {
                                        throw new Error("Erreur serveur");
                                    }
                                })
                                .catch(error => {
                                    btn.innerHTML = originalText;
                                    btn.disabled = false;
                                    feedbackMsg.classList.remove('hidden');
                                    feedbackMsg.className = "text-xs mt-2 text-error font-body-md-bold";
                                    feedbackMsg.textContent = "Erreur lors de l'enregistrement de la position en base de données.";
                                });
                            },
                            function(error) {
                                btn.innerHTML = originalText;
                                btn.disabled = false;
                                feedbackMsg.classList.remove('hidden');
                                feedbackMsg.className = "text-xs mt-2 text-error font-body-md-bold";
                                feedbackMsg.textContent = "Vous avez refusé la localisation ou elle est indisponible.";
                            },
                            { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                        );
                    } else {
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                        feedbackMsg.classList.remove('hidden');
                        feedbackMsg.className = "text-xs mt-2 text-error font-body-md-bold";
                        feedbackMsg.textContent = "Géolocalisation non supportée par votre navigateur.";
                    }
                });
            }
        });
    </script>

    @endsection