<header class="flex justify-between items-center w-full px-margin-desktop py-4 sticky top-0 z-40 bg-surface border-b border-outline-variant shadow-sm">
<div class="flex items-center gap-8">
<a href="index.html" class="flex items-center gap-2">
<img alt="AgriManager Logo" class="h-10 w-auto object-contain" src="https://lh3.googleusercontent.com/aida/AP1WRLttKA77ABGaQ_h945A0tu1wuK_db0agLA7ebq40A9Z5k_zktRzrk9Gz4Fs9VWJuIPV5lY5Ci1iavfeAe6wBhxNKUda0BvE97y3HKbV-IJ7QuMx3A8Fvn71OdnlwPvjDIUbjzy4f2LUB7jD0tm6qAzwiqYs5c70dkhiZJMJfUsd2q74zOJvG_XYBR7eNUgiDgi2n_XKWHahbPoiJTQq8NoQ4RtiCdV2IlrY1nk5l4z9Y6DlJq60uEFonFRo">
</a>
<div class="hidden md:flex items-center bg-surface-container-low px-4 py-2 rounded-full border border-outline-variant w-96">
<span class="material-symbols-outlined text-on-surface-variant mr-2">search</span>
<input class="bg-transparent border-none focus:ring-0 text-body-md w-full placeholder:text-on-surface-variant" placeholder="Rechercher des produits, fermes..." type="text">
</div>
</div>
<nav class="flex items-center gap-4 md:gap-6">
<div class="hidden lg:flex items-center gap-6">
<a class="text-primary font-bold font-body-md" href="{{route('index')}}">Accueil</a>
<a class="text-on-surface-variant hover:text-primary transition-colors font-body-md " href="{{route('A-Propos')}}">À-propos</a>
<a class="text-on-surface-variant hover:text-primary transition-colors font-body-md" href="{{route('Services')}}">Produits</a>
<a class="text-on-surface-variant hover:text-primary transition-colors font-body-md" href="{{route('Contact')}}">Nous-Contactez</a>
</div>
<div class="hidden lg:block h-6 w-[1px] bg-outline-variant mx-2"></div>
<div class="flex items-center gap-2 md:gap-4">
<a href="panier.html" class="hidden lg:block relative p-2 text-on-surface-variant hover:bg-surface-container rounded-full transition-all">
<span class="material-symbols-outlined">shopping_cart</span>
<span class="absolute top-1 right-1 w-4 h-4 bg-secondary text-[10px] text-on-secondary flex items-center justify-center rounded-full font-bold">3</span>
</a>
<a href="suivi-commande.html" class="hidden lg:block relative p-2 text-on-surface-variant hover:bg-surface-container rounded-full transition-all" title="Suivi de commande">
<span class="material-symbols-outlined">local_shipping</span>
</a>
<a href="contact.html" class="hidden lg:block p-2 text-on-surface-variant hover:bg-surface-container rounded-full transition-all" title="Aide">
<span class="material-symbols-outlined">help_outline</span>
</a>
<div class="hidden lg:flex items-center gap-2 pl-2 cursor-pointer group">
<div class="w-10 h-10 rounded-full overflow-hidden border-2 border-primary-fixed">
<img alt="User Profile" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAdswxE4KU3nMjQG0s0VZmrm1lk8oaqKrmVySPIzf6pyzqH1sArDmU-J2a06cEotJECh0LAaCK0FnThFiMUZEHWZnKB2lGIlOgi-5pxo7DPOJqJXzGOpIULZPQdyILksVOXG2GUaEaDOrNRcsKKzPNwizPQTl5q6hRqy5e5Ch9Nt6pnyTmZgPs1iapn3TyAZNlEdoSNpFA0xPLV29dH9eyZPwU0rYPX-RD281-paTuCZYGQvYreQ4O6AgmRRLopo4mMnkQn_CVt_zs">
</div>
<span class="font-body-md-bold text-on-surface hidden lg:block">Jean Dupont</span>
</div>
<button id="mobile-menu-btn" class="lg:hidden p-2 text-on-surface-variant hover:bg-surface-container rounded-full transition-all">
<span class="material-symbols-outlined">menu</span>
</button>
</div>
</nav>
</header>