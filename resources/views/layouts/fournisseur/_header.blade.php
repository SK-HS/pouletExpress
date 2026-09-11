    <header class="flex justify-between items-center w-full px-margin-mobile md:px-margin-desktop py-4 sticky top-0 z-40 lg:ml-64 lg:w-[calc(100%-16rem)] bg-surface/80 backdrop-blur-md dark:bg-surface-dim/80 border-b border-outline-variant dark:border-outline" id="top-bar">
        <!-- Add original top bar code -->
        <div class="flex items-center gap-3 md:gap-6 flex-1">
            <button class="lg:hidden p-2 text-on-surface-variant hover:bg-surface-container-high rounded-full" id="open-sidebar">
                <span class="material-symbols-outlined">menu</span>
            </button>
            <h2 class="font-headline-md text-headline-md font-bold text-on-surface dark:text-inverse-on-surface hidden sm:block truncate">Tableau de bord</h2>
            <div class="relative w-full max-w-xs md:max-w-md ml-0 sm:ml-4">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-sm md:text-base">search</span>
                <input class="w-full bg-surface-container-low border-none rounded-full py-2 pl-10 pr-4 focus:ring-2 focus:ring-primary/20 font-body-md text-sm md:text-body-md" placeholder="Rechercher..." type="text"/>
            </div>
        </div>
        <div class="flex items-center gap-2 md:gap-4">
                        <button class="w-8 h-8 md:w-10 md:h-10 flex items-center justify-center rounded-full hover:bg-surface-container transition-all" id="theme-toggle">
                <span class="material-symbols-outlined text-on-surface-variant text-xl md:text-2xl dark:hidden">dark_mode</span>
                <span class="material-symbols-outlined text-on-surface-variant text-xl md:text-2xl hidden dark:block">light_mode</span>
            </button>

               {{-- <button class="w-8 h-8 md:w-10 md:h-10 flex items-center justify-center rounded-full hover:bg-surface-container transition-all">
                <span class="material-symbols-outlined text-on-surface-variant text-xl md:text-2xl">notifications</span>
            </button> --}}

    

            {{-- menu notification --}}
            <div class="relative" id="notif-bell-wrapper">
                <button type="button" id="notif-bell-btn"
                        class="relative flex items-center justify-center w-11 h-11 rounded-full bg-surface-container-high dark:bg-slate-800 text-on-surface dark:text-white hover:bg-surface-container transition-all">
                    <span class="material-symbols-outlined text-2xl">notifications</span>
                    <span id="notif-badge"
                        class="hidden absolute -top-1 -right-1 min-w-[20px] h-5 px-1 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">
                        0
                    </span>
                </button>

                {{-- Dropdown --}}
                <div id="notif-dropdown"
                    class="hidden absolute right-0 mt-2 w-80 sm:w-96 max-h-[70vh] overflow-y-auto bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 z-50">

                    <div class="flex items-center justify-between p-4 border-b border-slate-100 dark:border-slate-700">
                        <h3 class="font-bold text-sm text-slate-900 dark:text-white">Notifications</h3>
                        <button type="button" id="notif-tout-lire" class="text-xs text-emerald-600 font-bold hover:underline">
                            Tout marquer comme lu
                        </button>
                    </div>

                    <div id="notif-list" class="divide-y divide-slate-100 dark:divide-slate-700">
                        <div class="p-8 text-center text-slate-400 text-sm">
                            <span class="material-symbols-outlined text-3xl mb-2 block">notifications_off</span>
                            Aucune notification pour le moment.
                        </div>
                    </div>
                </div>
            </div>


            <a href="{{route('Fournisseur-Profil')}}">
            <div class="flex items-center gap-2 md:gap-3 ml-2">
                <div class="text-right hidden sm:block">
                    <p class="font-body-md-bold text-on-surface text-sm leading-tight"> {{ Auth::guard('fournisseur')->user()?->nom_ferme}}</p>
                    <p class="text-[10px] md:text-xs text-on-surface-variant"> {{ Auth::guard('fournisseur')->user()?->nom}}</p>
                </div>
                <div class="w-8 h-8 md:w-10 md:h-10 rounded-full border-2 border-primary-fixed overflow-hidden flex-shrink-0">
                    @if(Auth::guard('fournisseur')->user()?->image)
                    <img class="w-full h-full object-cover" alt=" {{ Auth::guard('fournisseur')->user()?->nom}}" src="/storage/{{ Auth::guard('fournisseur')->user()?->image}}"/>
                    @else
                        <img class="w-full h-full object-cover" alt=" {{ Auth::guard('fournisseur')->user()?->nom}}" src="/storage/Logo/user.jpg"/>
                    @endif
                </div>
            </div>
        </a>
        </div>
    </header>



