 <header class="md:ml-64 h-16 sticky top-0 z-30 bg-white/90 dark:bg-slate-800/90 backdrop-blur-md border-b border-slate-200 dark:border-slate-700/60 px-4 md:px-8 flex items-center justify-between shadow-sm">
    <div class="flex items-center gap-3">
      <div class="flex items-center gap-2 md:hidden">
        <div class="w-8 h-8 rounded-lg bg-emerald-700 flex items-center justify-center text-white font-bold">
          <span class="material-symbols-outlined text-xl">local_shipping</span>
        </div>
        <span class="font-extrabold text-base text-emerald-900 dark:text-emerald-400">PouletExpress</span>
      </div>
      <h2 class="hidden md:block font-bold text-lg text-slate-800 dark:text-slate-100">Mon Espace Personnel</h2>
    </div>

    <div class="flex items-center gap-2 md:gap-4">
      <button onclick="toggleDriverStatus()" class="md:hidden driver-status-badge flex items-center gap-1.5 px-2.5 py-1 rounded-full badge-online text-xs font-bold">
        <span class="pulse-dot"></span><span>En ligne</span>
      </button>
      
      <button onclick="toggleTheme()" class="theme-toggle-btn p-2 rounded-full text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
        <span class="material-symbols-outlined">light_mode</span>
      </button>

      {{-- <button class="p-2 rounded-full text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 transition relative">
        <span class="material-symbols-outlined">notifications</span>
        <span class="absolute top-1 right-1 w-2 h-2 rounded-full bg-orange-600"></span>
      </button> --}}

<div class="relative inline-block">
    
    <!-- Votre bouton original (avec l'ajout du onclick) -->
    <button onclick="document.getElementById('notif-dropdown-livreur').classList.toggle('hidden')" class="p-2 rounded-full text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 transition relative cursor-pointer">
        <span class="material-symbols-outlined">notifications</span>
        
        <!-- Votre pastille Orange (modifiée pour afficher le chiffre) -->
        <span id="badge-notif-livreur" class="absolute top-0 right-0 inline-flex items-center justify-center w-4 h-4 text-[10px] font-bold text-white bg-orange-600 rounded-full hidden">
            0
        </span>
    </button>

    <!-- Le Menu Déroulant (Adapté avec vos couleurs Slate) -->
    <div id="notif-dropdown-livreur" class="absolute right-0 mt-2 w-64 bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 hidden z-50 overflow-hidden">
        
        <div class="p-3 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900">
            <h3 class="font-bold text-sm text-slate-800 dark:text-white">Missions disponibles</h3>
        </div>
        
        <div class="p-4 text-center">
            <p id="notif-message-livreur" class="text-sm text-slate-500 dark:text-slate-400">
                Aucune livraison disponible pour le moment.
            </p>
        </div>
        
        <a href="{{route('Livreur-Espace')}}" class="block w-full text-center p-3 text-sm font-bold text-orange-600 dark:text-orange-500 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
            Voir livraison disponible
        </a>
    </div>
</div>


    </div>
  </header>