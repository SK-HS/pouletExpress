 <!-- Mobile Bottom Fixed Navigation Bar -->
    <div class="mobile-bottom-nav">
        <a href="{{route('Clients-Espace')}}" class="mobile-nav-item">
            <span class="material-symbols-outlined">dashboard</span>
            <span>Accueil</span>
        </a>
        <a href="{{route('Suivi-Commande-Client')}}" class="mobile-nav-item">
            <span class="material-symbols-outlined">local_shipping</span>
            <span>Suivi</span>
        </a>
        <a href="{{route('Client-Profil')}}" class="mobile-nav-item active">
            <span class="material-symbols-outlined">person</span>
            <span>Profil</span>
        </a>
        {{-- <a href="{{route('Login-Client')}}" class="mobile-nav-item text-xs px-2 py-1 min-h-0 h-35 btn-md btn-secondary">
            <span class="material-symbols-outlined">lock</span>
            <span>Quitez...</span>
        </a> --}}
         <form action="{{ route('Client-Logout') }}" method="POST">
                    @csrf
           <button
    type="submit"
    class="mobile-nav-item  bg-red-500 text-white hover:bg-red-700">
                        <span class="material-symbols-outlined ">
                            logout
                        </span>

                        <span > Déconnexion </span>

                    </button>
                </form>
    </div>

    <!-- Application Script (Separated) -->