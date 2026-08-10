  <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-surface border-t border-outline-variant flex justify-around items-center h-bottom-nav z-50 px-2 pb-safe shadow-[0_-4px_20px_-5px_rgba(0,0,0,0.1)]">
        <!-- Accueil -->
<a href="{{route('Fournisseur-Espace')}}" class="flex flex-col items-center p-2 {{ request()->routeIs('Fournisseur-Espace') ? 'text-primary' : 'text-on-surface-variant hover:text-primary transition-colors' }}">
    <span class="material-symbols-outlined text-[24px]">dashboard</span>
    <span class="text-[10px] font-body-md-bold mt-1">Accueil</span>
</a>

<!-- Catalogue -->
<a href="{{route('Fournisseur-Catalogue')}}" class="flex flex-col items-center p-2 {{ request()->routeIs('Fournisseur-Catalogue') ? 'text-primary' : 'text-on-surface-variant hover:text-primary transition-colors' }}">
    <span class="material-symbols-outlined text-[24px]">inventory_2</span>
    <span class="text-[10px] font-body-md-bold mt-1">Catalogue</span>
</a>

<!-- Bouton Ajouter (Toujours en surbrillance) -->
<a href="{{route('Fournisseur-Produit')}}" class="flex flex-col items-center p-2 -mt-6 group">
    <div class="bg-primary text-on-primary w-14 h-14 rounded-full flex items-center justify-center shadow-lg border-4 border-surface group-hover:scale-110 transition-transform">
        <span class="material-symbols-outlined text-2xl">add</span>
    </div>
    <span class="text-[10px] font-body-md-bold mt-1 text-primary hidden sm:block">Ajouter</span>
</a>

<!-- Commandes -->
<a href="{{route('Fournisseur-Commande')}}" class="flex flex-col items-center p-2 {{ request()->routeIs('Fournisseur-Commande') ? 'text-primary' : 'text-on-surface-variant hover:text-primary transition-colors' }}">
    <span class="material-symbols-outlined text-[24px]">shopping_cart</span>
    <span class="text-[10px] font-body-md-bold mt-1">Commandes</span>
</a>

<!-- Profil -->
<a href="{{route('Fournisseur-Profil')}}" class="flex flex-col items-center p-2 {{ request()->routeIs('Fournisseur-Profil') ? 'text-primary' : 'text-on-surface-variant hover:text-primary transition-colors' }}">
    <span class="material-symbols-outlined text-[24px]">person</span>
    <span class="text-[10px] font-body-md-bold mt-1">Profil</span>
</a>

    </nav>