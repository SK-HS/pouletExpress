  <aside class="hidden md:flex flex-col h-screen w-64 fixed left-0 top-0 bg-white dark:bg-slate-800 border-r border-slate-200 dark:border-slate-700/60 z-40 py-6 px-4 shadow-sm">
    <!-- Brand Logo -->
    <div class="flex items-center gap-3 px-3 mb-8">
      <div class="w-10 h-10 rounded-xl bg-emerald-700 flex items-center justify-center text-white shadow-md shadow-emerald-900/20">
        <span class="material-symbols-outlined text-2xl">local_shipping</span>
      </div>
      <div>
        <h1 class="font-extrabold text-lg text-emerald-900 dark:text-emerald-400 tracking-tight leading-none">PouletExpress</h1>
        <span class="text-[11px] font-semibold text-amber-600 uppercase tracking-widest">Espace Livreur</span>
      </div>
    </div>

    <!-- Driver Mini Profile -->
   

    <!-- Navigation Links -->
    <nav class="flex-grow flex flex-col gap-1.5">
      <a href="{{route('Livreur-Espace')}}" class="nav-link active">
        <span class="material-symbols-outlined">dashboard</span>
        <span>Tableau de bord</span>
      </a>
      <a href="{{route('Commande-Livreur')}}" class="nav-link">
        <span class="material-symbols-outlined">package_2</span>
        <span>Mes Commandes</span>
      </a>
      <a href="{{route('Localisation-Produit-livreur')}}" class="nav-link">
        <span class="material-symbols-outlined">distance</span>
        <span>Géolocalisation & Trajet</span>
      </a>

       <form method="POST" action="{{ route('Livreur-Logout') }}" class="accept-form">
                @csrf
                <button type="submit" class="nav-link flex items-center gap-2 px-4 py-2 rounded-lg bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors font-semibold w-full sm:w-auto">
                 <span class="material-symbols-outlined">lock</span>
                <span>Déconnexion</span>
                </button>
            </form>
    {{-- <a href="{{route('Livreur-Login')}}" class="nav-link flex items-center gap-2 px-4 py-2 rounded-lg bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors font-semibold w-full sm:w-auto">
    
</a> --}}


    </nav>

    <!-- Bottom Actions -->
    <div class="mt-auto flex flex-col gap-2 pt-4 border-t border-slate-200 dark:border-slate-700">
      <button onclick="toggleDriverStatus()" class="w-full py-2.5 px-3 rounded-xl border border-emerald-600/30 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-300 font-bold text-xs flex items-center justify-between hover:bg-emerald-100 transition">
        <span>Statut Service</span>
        <span class="driver-status-badge flex items-center gap-1.5 px-2.5 py-0.5 rounded-full badge-online text-[11px]">
          <span class="pulse-dot"></span><span>En ligne</span>
        </span>
      </button>
      
      <button onclick="toggleTheme()" class="theme-toggle-btn w-full py-2.5 px-3 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 font-semibold text-xs flex items-center justify-between hover:bg-slate-100 dark:hover:bg-slate-700 transition">
        <span>Mode Sombre</span>
        <span class="material-symbols-outlined text-lg">dark_mode</span>
      </button>
       <a href="{{route('Profil-Livreur')}}">
    <div  class="flex items-center gap-3 p-3 mb-6 bg-slate-100 dark:bg-slate-700/50 rounded-xl border border-slate-200/60 dark:border-slate-600/40">
      
      <div class="w-10 h-10 rounded-full bg-emerald-600 text-white font-bold flex items-center justify-center text-sm shadow">
        @php
          $nomComplet = Auth::guard('livreur')->user()?->nom ?? '';
          $mots = array_filter(explode(' ', trim($nomComplet)));
          $initiales = collect($mots)->take(2)->map(fn($mot) => strtoupper($mot[0]))->implode('');
      @endphp

      <div class="w-10 h-10 rounded-full bg-emerald-600 text-white font-bold flex items-center justify-center text-sm shadow">
          {{ $initiales ?: '?' }}
      </div>
      </div>
      <div class="overflow-hidden">
        <p class="font-bold text-sm truncate text-slate-900 dark:text-white">{{ Auth::guard('livreur')->user()?->nom}}</p>
        <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Livreur Certifié #892</p>
      </div>
    </div>
    </a>
    </div>
  </aside>

