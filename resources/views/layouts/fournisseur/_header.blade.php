    <header class="flex justify-between items-center w-full px-margin-mobile md:px-margin-desktop py-4 sticky top-0 z-40 lg:ml-64 lg:w-[calc(100%-16rem)] bg-surface/80 backdrop-blur-md dark:bg-surface-dim/80 border-b border-outline-variant dark:border-outline" id="top-bar">
        <!-- Add original top bar code -->
        <div class="flex items-center gap-3 md:gap-6 flex-1">
            <button class="lg:hidden p-2 text-on-surface-variant hover:bg-surface-container-high rounded-full" id="open-sidebar">
                <span class="material-symbols-outlined">menu</span>
            </button>
            <h2 class="font-headline-md text-headline-md font-bold text-on-surface dark:text-inverse-on-surface hidden sm:block truncate">Tableau de bord</h2>
            <div class="relative w-full max-w-xs md:max-w-md ml-0 sm:ml-4">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-sm md:text-base">search</span>
                <input class="w-full bg-surface-container-low border-none rounded-full py-2 pl-10 pr-4 focus:ring-2 focus:ring-primary/20 font-body-md text-sm md:text-body-md" placeholder="Rechercher..." type="text"/>
            </div>
        </div>
        <div class="flex items-center gap-2 md:gap-4">
                        <button class="w-8 h-8 md:w-10 md:h-10 flex items-center justify-center rounded-full hover:bg-surface-container transition-all" id="theme-toggle">
                <span class="material-symbols-outlined text-on-surface-variant text-xl md:text-2xl dark:hidden">dark_mode</span>
                <span class="material-symbols-outlined text-on-surface-variant text-xl md:text-2xl hidden dark:block">light_mode</span>
            </button>

               {{-- <button class="w-8 h-8 md:w-10 md:h-10 flex items-center justify-center rounded-full hover:bg-surface-container transition-all">
                <span class="material-symbols-outlined text-on-surface-variant text-xl md:text-2xl">notifications</span>
            </button> --}}

           <!-- On englobe le bouton et le menu dans un div "relative" -->
            <div class="relative inline-block">
                
                <!-- Le Bouton (avec onclick pour ouvrir/fermer le menu) -->
                <button onclick="document.getElementById('notif-dropdown').classList.toggle('hidden')" class="relative w-8 h-8 md:w-10 md:h-10 flex items-center justify-center rounded-full hover:bg-surface-container transition-all cursor-pointer">
                    <span class="material-symbols-outlined text-on-surface-variant text-xl md:text-2xl">notifications</span>
                    
                    <!-- La pastille rouge (Badge) -->
                    <span id="badge-notif" class="absolute top-0 right-0 inline-flex items-center justify-center w-4 h-4 md:w-5 md:h-5 text-[10px] font-bold text-white bg-red-500 rounded-full hidden">
                        0
                    </span>
                </button>

                <!-- Le Menu Déroulant (Caché par défaut avec "hidden") -->
                <div id="notif-dropdown" class="absolute right-0 mt-2 w-64 bg-surface-container-lowest rounded-xl shadow-lg border border-outline-variant hidden z-50 overflow-hidden">
                    
                    <div class="p-3 border-b border-outline-variant/50 bg-surface-container-low">
                        <h3 class="font-bold text-sm text-primary">Notifications</h3>
                    </div>
                    
                    <div class="p-4 text-center">
                        <!-- C'est ici que le texte changera dynamiquement ! -->
                        <p id="notif-message" class="text-sm text-on-surface-variant">
                            Vous n'avez aucune nouvelle commande.
                        </p>
                    </div>
                    
                    <!-- Optionnel : Un bouton pour aller sur la page des commandes -->
                    <a href="{{route('Fournisseur-Commande')}}" class="block w-full text-center p-3 text-sm font-bold text-primary hover:bg-primary/10 transition-colors">
                        Voir mes commandes
                    </a>
                </div>
                
            </div>


            <a href="{{route('Fournisseur-Profil')}}">
            <div class="flex items-center gap-2 md:gap-3 ml-2">
                <div class="text-right hidden sm:block">
                    <p class="font-body-md-bold text-on-surface text-sm leading-tight"> {{ Auth::guard('fournisseur')->user()?->nom_ferme}}</p>
                    <p class="text-[10px] md:text-xs text-on-surface-variant"> {{ Auth::guard('fournisseur')->user()?->nom}}</p>
                </div>
                <div class="w-8 h-8 md:w-10 md:h-10 rounded-full border-2 border-primary-fixed overflow-hidden flex-shrink-0">
                    @if(Auth::guard('fournisseur')->user()?->image)
                    <img class="w-full h-full object-cover" alt=" {{ Auth::guard('fournisseur')->user()?->nom}}" src="/storage/{{ Auth::guard('fournisseur')->user()?->image}}"/>
                    @else
                        <img class="w-full h-full object-cover" alt=" {{ Auth::guard('fournisseur')->user()?->nom}}" src="/storage/Logo/user.jpg"/>
                    @endif
                </div>
            </div>
        </a>
        </div>
    </header>



