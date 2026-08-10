<!-- Bottom Navigation Bar for Mobile -->
<div class="lg:hidden fixed bottom-0 left-0 right-0 bg-surface border-t border-outline-variant/50 shadow-lg z-50 px-6 py-3 flex justify-around items-center">
    <!-- Cart -->
 <a href="{{ $cartCount > 0 ? route('Panier-Produit') : '#' }}"
   id="cart-link"
   class="relative flex items-center justify-center w-12 h-12 rounded-full
          bg-surface-container-high text-on-surface hover:bg-surface-container transition-all">

    <span class="material-symbols-outlined text-2xl">shopping_cart</span>

    {{-- Badge toujours présent dans le DOM, caché si vide --}}
    <span id="cartCountBadge"
          class="absolute -top-1 -right-1 w-5 h-5 bg-secondary text-[10px] text-on-secondary
                 flex items-center justify-center rounded-full font-bold transition-transform duration-200
                 {{ $cartCount > 0 ? '' : 'hidden' }}">
        {{ $cartCount }}
    </span>
</a>
    <!-- Shipping -->
    <a href="{{route('Livreur-Espace')}}" class="flex items-center justify-center w-12 h-12 rounded-full bg-surface-container-high text-on-surface hover:bg-surface-container transition-all">
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


        @if(Auth::guard('client')->check())
        <div id="user-menud" class="mt-auto pt-6 border-t border-outline-variant/30 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-primary-fixed">
                @if(Auth::guard('client')->user()?->image)
                <img alt="User Profile" class="w-full h-full object-cover" src="/storage/{{Auth::guard('client')->user()?->image}}">
                @else
                <img alt="User Profile" class="w-full h-full object-cover" src="/storage/Logo/user.jpg">

                @endif
            </div>
             <span class="font-body-md-bold text-on-surface">{{ Auth::guard('client')->user()?->nom}}</span>
            <span id="user-chevrond" class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-transform duration-300">expand_more</span>
            <div id="user-dropdownd" class="top-full right-0 mt-2 w-40 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-lg overflow-hidden z-50">
                <a href="{{route('Clients-Espace')}}" class="relative group flex items-center justify-center w-10 h-10 rounded-full hover:bg-primary/10 transition-colors">
               <span class="material-symbols-outlined text-on-surface-variant">person</span>
               <span class="absolute left-1 top-1/2 -translate-y-1/2
                 whitespace-nowrap
                 bg-gray-900 text-white text-xs
                 px-2 py-1 rounded
                 opacity-0 invisible
                 group-hover:opacity-100 group-hover:visible
                 transition-all duration-200">Mon Compte</span></a>
             <form action="{{ route('Client-Logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="group w-full flex items-center gap-3 px-4 py-3 hover:bg-error/10 transition-colors">

                        <span class="material-symbols-outlined text-lg text-on-surface-variant group-hover:text-error">
                            logout
                        </span>

                        <span class="absolute left-1 top-1/2 -translate-y-1/2
                 whitespace-nowrap
                 bg-gray-900 text-white text-xs
                 px-2 py-1 rounded
                 opacity-0 invisible
                 group-hover:opacity-100 group-hover:visible
                 transition-all duration-200">
                            Déconnexion
                        </span>

                    </button>
                </form>
        </div>
        </div>
        @else
            <div class="mt-auto pt-6 border-t border-outline-variant/30  flex items-center gap-3">
            <a href="{{route('Login-Client')}}" class="font-body-md-bold w-12 h-12 border-2 border-primary-fixed text-on-surface flex items-center justify-center">
            <span class="material-symbols-outlined ">lock</span>
            </a>
            </div>
        @endif

    </div>
</div>