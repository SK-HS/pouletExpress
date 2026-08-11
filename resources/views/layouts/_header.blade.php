<header class="flex justify-between items-center w-full px-margin-desktop py-4 sticky top-0 z-40 bg-surface border-b border-outline-variant shadow-sm">
    <div class="flex items-center gap-4 lg:gap-8 shrink-0">
        <a href="{{route('index')}}" class="flex items-center gap-2 shrink-0">
            {{-- min-w-[120px] empêche le "saut" au chargement du logo --}}
            <img alt="POULETXPRESS" class="h-10 w-auto object-contain min-w-[120px]" src="#">
        </a>
        {{-- Réduit un peu la taille sur écran moyen pour éviter de compresser les onglets --}}
        <div class="hidden md:flex items-center bg-surface-container-low px-4 py-2 rounded-full border border-outline-variant w-64 lg:w-80 shrink-0">
            <span class="material-symbols-outlined text-on-surface-variant mr-2">search</span>
            <input class="bg-transparent border-none focus:ring-0 text-body-md w-full placeholder:text-on-surface-variant outline-none" placeholder="Rechercher des produits, fermes..." type="text">
        </div>
    </div>
    
    <nav class="flex items-center gap-2 md:gap-4 lg:gap-6 shrink-0">
        {{-- whitespace-nowrap garantit que les mots ne passent JAMAIS sur 2 lignes --}}
        <div class="hidden lg:flex items-center gap-6 shrink-0 whitespace-nowrap">
            <a class="text-primary font-bold font-body-md" href="{{route('index')}}">Accueil</a>
            <a class="text-on-surface-variant hover:text-primary transition-colors font-body-md " href="{{route('A-Propos')}}">À-propos</a>
            <a class="text-on-surface-variant hover:text-primary transition-colors font-body-md" href="{{route('Services')}}">Produits</a>
            <a class="text-on-surface-variant hover:text-primary transition-colors font-body-md" href="{{route('Contact')}}">Nous contacter</a>
        </div>
        
        <div class="hidden lg:block h-6 w-[1px] bg-outline-variant mx-2 shrink-0"></div>
        
        <div class="flex items-center gap-2 md:gap-4 shrink-0">
            <a href="{{ route('Panier-Produit')}}" id="cart-link" class="relative flex items-center justify-center w-10 h-10 lg:w-12 lg:h-12 rounded-full bg-surface-container-high text-on-surface hover:bg-surface-container transition-all shrink-0">
                <span class="material-symbols-outlined text-xl lg:text-2xl">shopping_cart</span>
                <span id="cartCountBadge" class=" cart-badge absolute -top-1 -right-1 w-5 h-5 bg-secondary text-[10px] text-on-secondary flex items-center justify-center rounded-full font-bold transition-transform duration-200 {{ $cartCount > 0 ? '' : 'hidden' }}">
                    {{ $cartCount }}
                </span>
            </a>
            
            <a href="{{route('Livreur-Espace')}}" class="hidden lg:flex w-10 h-10 items-center justify-center text-on-surface-variant hover:bg-surface-container rounded-full transition-all shrink-0" title="Suivi de commande">
                <span class="material-symbols-outlined">local_shipping</span>
            </a>
            
            <a href="{{route('Fournisseur-Espace')}}" class="hidden lg:flex w-10 h-10 items-center justify-center text-on-surface-variant hover:bg-surface-container rounded-full transition-all shrink-0" title="Aide">
                <span class="material-symbols-outlined">help_outline</span>
            </a>

            @if(Auth::guard('client')->check())
                <div id="user-menu" class="hidden lg:flex items-center gap-2 pl-2 cursor-pointer group shrink-0 relative">
                    <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-primary-fixed shrink-0 bg-surface-container">
                        @if(Auth::guard('client')->user()?->image)
                            <img alt="User Profile" class="w-full h-full object-cover" src="/storage/{{Auth::guard('client')->user()?->image}}">
                        @else
                            <img alt="User Profile" class="w-full h-full object-cover" src="/storage/Logo/user.jpg">
                        @endif
                    </div>
                    {{-- truncate empêche un nom très long de casser le design --}}
                    <span class="font-body-md-bold text-on-surface hidden lg:block whitespace-nowrap max-w-[120px] truncate">
                        {{ Auth::guard('client')->user()?->nom }}
                    </span>
                    <span id="user-chevron" class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-transform duration-300 shrink-0">expand_more</span>
                    
                    <div id="user-dropdown" class="hidden absolute top-full right-0 mt-2 w-48 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-lg overflow-hidden z-50">
                        <a href="{{route('Clients-Espace')}}" class="flex items-center gap-3 px-4 py-3 hover:bg-surface-container transition-colors text-on-surface">
                            <span class="material-symbols-outlined text-lg">person</span><span class="font-body-md">Mon Compte</span>
                        </a>
                        <form action="{{ route('Client-Logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="group w-full flex items-center gap-3 px-4 py-3 hover:bg-error/10 transition-colors">
                                <span class="material-symbols-outlined text-lg text-on-surface-variant group-hover:text-error">logout</span>
                                <span class="font-body-md text-on-surface group-hover:text-error">Déconnexion</span>
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="hidden lg:flex items-center gap-2 pl-2 shrink-0">
                    <a href="{{route('Login-Client')}}" class="w-10 h-10 flex items-center justify-center rounded-full overflow-hidden border-2 border-primary-fixed text-on-surface-variant bg-surface-container-low hover:bg-surface-container transition-all" title="Connexion">
                        <span class="material-symbols-outlined text-lg">lock</span>
                    </a>
                </div>
            @endif

            <button id="mobile-menu-btn" class="lg:hidden w-10 h-10 flex items-center justify-center text-on-surface-variant hover:bg-surface-container rounded-full transition-all shrink-0">
                <span class="material-symbols-outlined">menu</span>
            </button>
        </div>
    </nav>
</header>