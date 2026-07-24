<!-- Bottom Navigation Bar for Mobile -->
<div class="lg:hidden fixed bottom-0 left-0 right-0 bg-surface border-t border-outline-variant/50 shadow-lg z-50 px-6 py-3 flex justify-around items-center">
    <!-- Cart -->
    <a href="panier.html" class="relative flex items-center justify-center w-12 h-12 rounded-full bg-surface-container-high text-on-surface hover:bg-surface-container transition-all">
        <span class="material-symbols-outlined text-2xl">shopping_cart</span>
        <span class="absolute -top-1 -right-1 w-5 h-5 bg-secondary text-[10px] text-on-secondary flex items-center justify-center rounded-full font-bold">3</span>
    </a>
    <!-- Shipping -->
    <a href="suivi-commande.html" class="flex items-center justify-center w-12 h-12 rounded-full bg-surface-container-high text-on-surface hover:bg-surface-container transition-all">
        <span class="material-symbols-outlined text-2xl">local_shipping</span>
    </a>
    <!-- Help -->
    <a href="contact.html" class="flex items-center justify-center w-12 h-12 rounded-full bg-surface-container-high text-on-surface hover:bg-surface-container transition-all">
        <span class="material-symbols-outlined text-2xl">help_outline</span>
    </a>
</div>

<!-- Mobile Menu Drawer Overlay -->
<div id="mobile-menu" class="fixed inset-0 bg-black/50 z-50 transition-opacity duration-300 opacity-0 pointer-events-none">
    <div class="absolute right-0 top-0 bottom-0 w-64 bg-surface p-6 shadow-xl flex flex-col gap-6 transform translate-x-full transition-transform duration-300">
        <div class="flex justify-between items-center pb-4 border-b border-outline-variant">
            <span class="font-body-md-bold text-primary">Menu</span>
            <button id="close-menu-btn" class="p-1 hover:bg-surface-container rounded-full transition-all">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <nav class="flex flex-col gap-4 text-lg">
            <a class="text-primary font-bold py-2 border-b border-outline-variant/10" href="{{route('index')}}">Accueil</a>
            <a class="hover:text-primary transition-colors py-2 border-b border-outline-variant/10" href="{{route('A-Propos')}}">À-propos</a>
            <a class="hover:text-primary transition-colors py-2 border-b border-outline-variant/10" href="{{route('Services')}}">Produits</a>
            <a class="hover:text-primary transition-colors py-2" href="{{route('Contact')}}">Nous-Contactez</a>
        </nav>
        <div class="mt-auto pt-6 border-t border-outline-variant/30 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-primary-fixed">
                <img alt="User Profile" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAdswxE4KU3nMjQG0s0VZmrm1lk8oaqKrmVySPIzf6pyzqH1sArDmU-J2a06cEotJECh0LAaCK0FnThFiMUZEHWZnKB2lGIlOgi-5pxo7DPOJqJXzGOpIULZPQdyILksVOXG2GUaEaDOrNRcsKKzPNwizPQTl5q6hRqy5e5Ch9Nt6pnyTmZgPs1iapn3TyAZNlEdoSNpFA0xPLV29dH9eyZPwU0rYPX-RD281-paTuCZYGQvYreQ4O6AgmRRLopo4mMnkQn_CVt_zs">
            </div>
            <span class="font-body-md-bold text-on-surface">SK LA JOIE</span>
        </div>
    </div>
</div>