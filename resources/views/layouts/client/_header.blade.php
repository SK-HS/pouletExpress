  <header class="sticky top-0 z-50 bg-white/90 dark:bg-[#152238]/90 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 shadow-sm transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 sm:h-20 flex items-center justify-between">
            
            <!-- Brand Logo -->
            <a href="{{route('Clients-Espace')}}" class="flex items-center gap-2.5 sm:gap-3 group">
                <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-gradient-to-br from-emerald-600 to-emerald-800 flex items-center justify-center text-white shadow-md shadow-emerald-700/20 group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-xl sm:text-2xl">pets</span>
                </div>
                <div>
                    <span class="text-lg sm:text-xl font-extrabold tracking-tight text-emerald-800 dark:text-emerald-400">PouletExpress</span>
                    <span class="block text-[9px] sm:text-[10px] font-semibold tracking-widest uppercase text-amber-600 dark:text-amber-400">Ferme & Qualité CI</span>
                </div>
            </a>

            <!-- Desktop Navigation Links -->
            <nav class="hidden md:flex items-center gap-2">
                <a href="{{route('Clients-Espace')}}" class="nav-link active">
                    <span class="material-symbols-outlined text-lg align-middle mr-1">dashboard</span> Tableau de Bord
                </a>
                <a href="{{route('Suivi-Commande-Client')}}" class="nav-link">
                    <span class="material-symbols-outlined text-lg align-middle mr-1">local_shipping</span> Suivi de Commande
                </a>
                <a href="{{route('Client-Profil')}}" class="nav-link">
                    <span class="material-symbols-outlined text-lg align-middle mr-1">person_add</span> Mon Profil
                </a>
            </nav>

            <!-- Action Controls & User Options -->
            <div class="flex items-center gap-2 sm:gap-3">
                <!-- Theme Toggle Button -->
                <button id="theme-toggle-btn" class="p-2 sm:p-2.5 rounded-full text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" title="Changer le thème">
                    <span class="material-symbols-outlined text-xl sm:text-2xl">dark_mode</span>
                </button>

                <!-- Quick Buy Button -->
                {{-- <button data-open-modal="new-order" class="btn-secondary hidden sm:inline-flex text-xs uppercase tracking-wider">
                    <span class="material-symbols-outlined text-base">add_shopping_cart</span> DECONNEXION
                </button> --}}
                 <form action="{{ route('Client-Logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                       data-open-modal="new-order" class="btn-secondary hidden sm:inline-flex text-xs uppercase tracking-wider">

                        <span class="material-symbols-outlined text-lg text-on-surface-variant group-hover:text-error">
                            logout
                        </span>
                        <span> Déconnexion</span>

                    </button>
                </form>

                <!-- Mobile Drawer Toggle Icon -->
                <button id="mobile-drawer-toggle" class="md:hidden p-2 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">
                    <span class="material-symbols-outlined">menu</span>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer Navigation -->
        <div id="mobile-drawer" class="hidden md:hidden bg-white dark:bg-[#152238] border-b border-slate-200 dark:border-slate-800 px-4 py-4 space-y-2">
            <a href="{{route('Clients-Espace')}}" class="block px-4 py-3 rounded-lg font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-slate-800">
                <span class="material-symbols-outlined align-middle mr-2 text-emerald-600">dashboard</span> Tableau de Bord
            </a>
            <a href="{{route('Suivi-Commande-Client')}}" class="block px-4 py-3 rounded-lg font-semibold text-slate-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-800">
                <span class="material-symbols-outlined align-middle mr-2 text-emerald-600">local_shipping</span> Suivi de Commande
            </a>
            <a href="{{route('Client-Profil')}}" class="block px-4 py-3 rounded-lg font-semibold text-slate-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-800">
                <span class="material-symbols-outlined align-middle mr-2 text-emerald-600">person_add</span> Mon Profil
            </a>
        </div>
    </header>