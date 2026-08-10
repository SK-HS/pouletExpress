  <nav class="md:hidden fixed bottom-0 left-0 right-0 h-16 bg-white/95 dark:bg-slate-800/95 backdrop-blur-md border-t border-slate-200 dark:border-slate-700/80 z-40 grid grid-cols-4 px-2 shadow-lg">
    <a href="{{route('Livreur-Espace')}}" class="mobile-nav-item active">
      <span class="material-symbols-outlined text-2xl">dashboard</span>
      <span>Accueil</span>
    </a>
    <a href="{{route('Commande-Livreur')}}" class="mobile-nav-item">
      <span class="material-symbols-outlined text-2xl">package_2</span>
      <span>Commandes</span>
    </a>
    <a href="{{route('Localisation-Produit-livreur')}}" class="mobile-nav-item">
      <span class="material-symbols-outlined text-2xl">map</span>
      <span>Trajet GPS</span>
    </a>
    <a href="{{route('Profil-Livreur')}}" class="mobile-nav-item">
      <span class="material-symbols-outlined text-2xl">person</span>
      <span>Compte</span>
    </a>
    <form method="POST" action="{{ route('Livreur-Logout') }}" class="accept-form">
      @csrf
      <button type="submit" class="nav-link flex items-center gap-2 px-4 py-2 rounded-lg bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors font-semibold w-full sm:w-auto">
        <span class="material-symbols-outlined  text-2xl">lock</span>
      <span>Déconnexion</span>
      </button>
  </form>
  </nav>