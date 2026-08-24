<!-- Bottom Navigation Bar for Mobile -->
<div class="lg:hidden fixed bottom-0 left-0 right-0 bg-surface border-t border-outline-variant/50 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] z-50 px-6 py-3 flex justify-around items-center">
    
    <!-- User Profile / Login -->
       @if(Auth::guard('client')->check())
        <div class="relative flex items-center group">
            <button type="button" class="flex items-center gap-2 focus:outline-none cursor-pointer p-1">
                <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-primary-fixed">
                    @if(Auth::guard('client')->user()?->image)
                        <img alt="Profil" class="w-full h-full object-cover" src="/storage/{{ Auth::guard('client')->user()->image }}">
                    @else
                        <img alt="Profil" class="w-full h-full object-cover" src="/storage/Logo/user.jpg">
                    @endif
                </div>
                <span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">expand_less</span>
            </button>

            <!-- LE PONT INVISIBLE (pb-2 remplace mb-3) -->
            <div class="absolute bottom-full left-0 pb-2 w-48 z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 origin-bottom-left">
                
                <!-- LA BOÎTE VISIBLE DU MENU -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-xl overflow-hidden">
                    <a href="{{ route('Clients-Espace') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-primary/10 transition-colors text-on-surface">
                        <span class="material-symbols-outlined text-primary">person</span>
                        <span class="text-sm font-bold">Mon Compte</span>
                    </a>
                    <form action="{{ route('Client-Logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-error/10 transition-colors text-error text-left">
                            <span class="material-symbols-outlined text-error">logout</span>
                            <span class="text-sm font-bold">Déconnexion</span>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    @else

        <a href="{{ route('Login-Client') }}" class="flex items-center justify-center w-11 h-11 rounded-full bg-surface-container-high text-on-surface hover:bg-surface-container transition-all shadow-sm">
            <span class="material-symbols-outlined">lock</span>
        </a>
    @endif

    <!-- Shipping -->
    <a href="{{ route('Livreur-Espace') }}" class="flex items-center justify-center w-11 h-11 rounded-full bg-surface-container-high text-on-surface hover:bg-surface-container transition-all shadow-sm">
        <span class="material-symbols-outlined text-xl">local_shipping</span>
    </a>
    
    <!-- Help -->
    <a href="{{route('Fournisseur-Espace')}}" class="flex items-center justify-center w-11 h-11 rounded-full bg-surface-container-high text-on-surface hover:bg-surface-container transition-all shadow-sm">
        <span class="material-symbols-outlined text-xl">help_outline</span>
    </a>
</div>

<!-- Mobile Menu Drawer Overlay -->
<div id="mobile-menu" class="fixed inset-0 bg-black/50 z-50 transition-opacity duration-300 opacity-0 pointer-events-none">
    <div class="absolute right-0 top-0 bottom-0 w-72 bg-surface p-6 shadow-xl flex flex-col transform translate-x-full transition-transform duration-300 overflow-y-auto">
        
        <!-- Header du Menu -->
        <div class="flex justify-between items-center pb-4 border-b border-outline-variant/30 mb-6">
            <span class="font-headline-md text-primary text-xl">Menu</span>
            <button id="close-menu-btn" class="p-2 hover:bg-surface-container rounded-full transition-all text-on-surface-variant">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        <!-- Liens Principaux -->
        <nav class="flex flex-col gap-2 text-lg mb-6">
            <a class="flex items-center gap-3 text-primary font-bold px-4 py-3 rounded-xl bg-primary/10" href="{{ route('index') }}">
                <span class="material-symbols-outlined">home</span> Accueil
            </a>
            <a class="flex items-center gap-3 hover:text-primary hover:bg-surface-container transition-colors px-4 py-3 rounded-xl text-on-surface" href="{{ route('A-Propos') }}">
                <span class="material-symbols-outlined">info</span> À-propos
            </a>
            <a class="flex items-center gap-3 hover:text-primary hover:bg-surface-container transition-colors px-4 py-3 rounded-xl text-on-surface" href="{{ route('Services') }}">
                <span class="material-symbols-outlined">storefront</span> Produits
            </a>
            <a class="flex items-center gap-3 hover:text-primary hover:bg-surface-container transition-colors px-4 py-3 rounded-xl text-on-surface" href="{{ route('Contact') }}">
                <span class="material-symbols-outlined">mail</span> Nous Contacter
            </a>
        </nav>

        <!-- Section Utilisateur (Bas du menu) -->
        <div class="mt-auto pt-6 border-t border-outline-variant/30 flex flex-col gap-4">
            @if(Auth::guard('client')->check())
                <!-- Profil Utilisateur -->
                <div class="flex items-center gap-3 mb-2 px-2">
                    <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-primary-fixed shrink-0">
                        @if(Auth::guard('client')->user()?->image)
                            <img alt="Profil" class="w-full h-full object-cover" src="/storage/{{ Auth::guard('client')->user()->image }}">
                        @else
                            <img alt="Profil" class="w-full h-full object-cover" src="/storage/Logo/user.jpg">
                        @endif
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-on-surface-variant">Connecté en tant que</span>
                        <span class="font-body-md-bold text-on-surface line-clamp-1">{{ Auth::guard('client')->user()->nom }}</span>
                    </div>
                </div>
                
                <!-- Boutons d'action -->
                <a href="{{ route('Clients-Espace') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-surface-container hover:bg-primary/10 transition-colors text-on-surface">
                    <span class="material-symbols-outlined text-primary">person</span>
                    <span class="text-sm font-bold">Mon Compte</span>
                </a>
                <form action="{{ route('Client-Logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-error/10 hover:bg-error/20 transition-colors text-error text-left">
                        <span class="material-symbols-outlined">logout</span>
                        <span class="text-sm font-bold">Déconnexion</span>
                    </button>
                </form>
            @else
                <!-- Bouton Connexion -->
                <a href="{{ route('Login-Client') }}" class="flex items-center justify-center gap-2 px-6 py-3.5 rounded-full bg-primary text-white hover:bg-primary-fixed hover:text-primary font-bold transition-all w-full shadow-md">
                    <span class="material-symbols-outlined">lock</span>
                    Se connecter
                </a>
            @endif
        </div>

    </div>
</div>
