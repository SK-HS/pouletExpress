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

      <button class="p-2 rounded-full text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 transition relative">
        <span class="material-symbols-outlined">notifications</span>
        <span class="absolute top-1 right-1 w-2 h-2 rounded-full bg-orange-600"></span>
      </button>
    </div>
  </header>