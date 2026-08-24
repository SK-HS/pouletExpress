<aside class="flex flex-col h-[100dvh] py-gutter px-4 bg-surface-container-low dark:bg-inverse-surface w-64 fixed left-0 top-0 z-50 transition-transform duration-300 -translate-x-full lg:translate-x-0" id="sidebar">

    <div class="mb-10 px-2 flex items-center justify-between lg:justify-start gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-primary flex items-center justify-center overflow-hidden">
                    <img class="w-full h-full object-cover" alt="AgriManager" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC0xAcjj1fl21fSFyp8MKYJQzAMgpoSWPu1wq17ncK2lS2bv1tqaqZ9uLEouRMJCoG5S66sT4KZmlPGtnzuCI0HFC-bipiCbMTA0urq7cAI1KTyLj2wBC_2FrRCvLIJwGJjmHyW7fvOFa0ugYdJQXcXkdvFDBEap_ALJ9rxzSENOFKgiiS2-bdQ2voVfQmQBjTcacTXee14oZnXrqttMW-qOGwIGsHZ-GGd4_vIRwxTUuulgOrR3NEaaoQ-DtlsuGvWZm5as_ohX9E"/>
                </div>
                <div>
                    <h1 class="font-headline-md text-headline-md text-primary dark:text-primary-fixed-dim font-extrabold leading-tight">AgriManager</h1>
                    <p class="font-label-sm text-label-sm text-on-surface-variant opacity-70 uppercase tracking-widest">Console Admin</p>
                </div>
            </div>
            <button class="lg:hidden p-2 text-on-surface-variant hover:bg-surface-container-high rounded-full" id="close-sidebar">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        <nav class="flex-1 space-y-2 overflow-y-auto">
           <!-- Lien Tableau de bord -->
        <a class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-150 
            {{ request()->routeIs('Fournisseur-Espace') 
                ? 'bg-surface-container-high dark:bg-surface-variant text-primary dark:text-primary-fixed-dim font-bold border-r-4 border-primary dark:border-primary-fixed-dim' 
                : 'hover:bg-surface-container-high dark:hover:bg-surface-variant text-on-surface-variant dark:text-surface-variant' }}" 
            href="{{route('Fournisseur-Espace')}}">
            
            <span class="material-symbols-outlined {{ request()->routeIs('Fournisseur-Espace') ? 'active-pill' : '' }}">dashboard</span>
            <span class="font-body-md text-body-md">Tableau de bord</span>
        </a>

        <!-- Lien Catalogue -->
        <a class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-150 
            {{ request()->routeIs('Fournisseur-Catalogue') 
                ? 'bg-surface-container-high dark:bg-surface-variant text-primary dark:text-primary-fixed-dim font-bold border-r-4 border-primary dark:border-primary-fixed-dim' 
                : 'hover:bg-surface-container-high dark:hover:bg-surface-variant text-on-surface-variant dark:text-surface-variant' }}" 
            href="{{route('Fournisseur-Catalogue')}}">
            
            <span class="material-symbols-outlined {{ request()->routeIs('Fournisseur-Catalogue') ? 'active-pill' : '' }}">inventory_2</span>
            <span class="font-body-md text-body-md">Catalogue</span>
        </a>
        <!-- Lien Ajouter Produit -->
        <a class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-150 
            {{ request()->routeIs('Fournisseur-Produit') 
                ? 'bg-surface-container-high dark:bg-surface-variant text-primary dark:text-primary-fixed-dim font-bold border-r-4 border-primary dark:border-primary-fixed-dim' 
                : 'hover:bg-surface-container-high dark:hover:bg-surface-variant text-on-surface-variant dark:text-surface-variant' }}" 
            href="{{route('Fournisseur-Produit')}}">
            
            <span class="material-symbols-outlined {{ request()->routeIs('Fournisseur-Produit') ? 'active-pill' : '' }}">add_circle</span>
            <span class="font-body-md text-body-md">Ajouter Produit</span>
        </a>

        <!-- Lien Commandes -->
        <a class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-150 
            {{ request()->routeIs('Fournisseur-Commande') 
                ? 'bg-surface-container-high dark:bg-surface-variant text-primary dark:text-primary-fixed-dim font-bold border-r-4 border-primary dark:border-primary-fixed-dim' 
                : 'hover:bg-surface-container-high dark:hover:bg-surface-variant text-on-surface-variant dark:text-surface-variant' }}" 
            href="{{route('Fournisseur-Commande')}}">
            
            <span class="material-symbols-outlined {{ request()->routeIs('Fournisseur-Commande') ? 'active-pill' : '' }}">shopping_cart</span>
            <span class="font-body-md text-body-md">Commandes</span>
        </a>

        <!-- Lien Approvisionnement -->
        <a class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-150 
            {{ request()->routeIs('Approvisionnement-Produit') 
                ? 'bg-surface-container-high dark:bg-surface-variant text-primary dark:text-primary-fixed-dim font-bold border-r-4 border-primary dark:border-primary-fixed-dim' 
                : 'hover:bg-surface-container-high dark:hover:bg-surface-variant text-on-surface-variant dark:text-surface-variant' }}" 
            href="{{route('Approvisionnement-Produit')}}">
            
            <span class="material-symbols-outlined {{ request()->routeIs('Approvisionnement-Produit') ? 'active-pill' : '' }}">local_shipping</span>
            <span class="font-body-md text-body-md">Approvisionnement</span>
        </a>

        <!-- Lien Historique Approvisionnement -->
        <a class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-150 
            {{ request()->routeIs('Approvisionnement-Historique') 
                ? 'bg-surface-container-high dark:bg-surface-variant text-primary dark:text-primary-fixed-dim font-bold border-r-4 border-primary dark:border-primary-fixed-dim' 
                : 'hover:bg-surface-container-high dark:hover:bg-surface-variant text-on-surface-variant dark:text-surface-variant' }}" 
            href="{{route('Approvisionnement-Historique')}}">
            
            <span class="material-symbols-outlined {{ request()->routeIs('Approvisionnement-Historique') ? 'active-pill' : '' }}">history</span>
            <span class="font-body-md text-body-md">Historique Approvisionnement</span>
        </a>
        <!-- Lien pub -->
        <a class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-150 
            {{ request()->routeIs('Liste-Campagnes') 
                ? 'bg-surface-container-high dark:bg-surface-variant text-primary dark:text-primary-fixed-dim font-bold border-r-4 border-primary dark:border-primary-fixed-dim' 
                : 'hover:bg-surface-container-high dark:hover:bg-surface-variant text-on-surface-variant dark:text-surface-variant' }}" 
            href="{{route('Liste-Campagnes')}}">
            
            <span class="material-symbols-outlined {{ request()->routeIs('Liste-Campagnes') ? 'active-pill' : '' }}">percent</span>
            <span class="font-body-md text-body-md">Campagne Pub</span>
        </a>
        </nav>
        
      <div class="mt-auto border-t border-outline-variant pt-4 pb-8 lg:pb-4">
    <form action="{{ route('Fournisseur-Logout') }}" method="POST" class="w-full">
        @csrf
        
        <button type="submit" class="w-full bg-red-600 text-white px-4 py-3 lg:py-2.5 rounded-xl font-body-md-bold flex items-center justify-center gap-2 hover:bg-red-700 shadow-sm transition-all active:scale-95">
            <span class="material-symbols-outlined text-[20px]">logout</span>
            <span>Déconnexion</span>
        </button>
        
    </form>
</div>

    </aside>