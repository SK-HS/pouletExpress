@extends('layouts.fournisseur.main')

@section('content')
<main class="lg:ml-64 p-margin-mobile pb-28 md:p-margin-desktop space-y-8 animate-in fade-in duration-500">
    
    <!-- En-tête de la page -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 mb-4 md:mb-6">
        <div>
            <h3 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-3xl">shopping_cart</span>
                Mes Commandes
            </h3>
            <p class="text-on-surface-variant font-body-md mt-1">Gérez, suivez et expédiez les commandes de vos clients.</p>
        </div>
    </div>

    <!-- Filtres / Onglets -->
    <div class="flex gap-3 overflow-x-auto hide-scrollbar pb-2">
        <a href="#" class="whitespace-nowrap px-5 py-2.5 rounded-xl bg-primary text-on-primary font-body-md-bold shadow-md shadow-primary/20 transition-all active:scale-95">
            Toutes les commandes
        </a>
        <a href="#" class="whitespace-nowrap px-5 py-2.5 rounded-xl bg-surface-container border border-outline-variant text-on-surface hover:bg-surface-container-highest font-body-md-bold transition-all active:scale-95 flex items-center gap-2">
            Nouvelles 
            <span class="bg-error text-white text-[10px] px-2 py-0.5 rounded-full font-bold">3</span>
        </a>
        <a href="#" class="whitespace-nowrap px-5 py-2.5 rounded-xl bg-surface-container border border-outline-variant text-on-surface hover:bg-surface-container-highest font-body-md-bold transition-all active:scale-95">
            En cours
        </a>
        <a href="#" class="whitespace-nowrap px-5 py-2.5 rounded-xl bg-surface-container border border-outline-variant text-on-surface hover:bg-surface-container-highest font-body-md-bold transition-all active:scale-95">
            Livrées
        </a>
    </div>

    <!-- Liste des commandes -->
    <div class="space-y-4">
        
        <!-- CARTE COMMANDE 1 (En Cours - Expédiée) -->
        @foreach ($commandes as $commande)
            
      
        <div class="bg-surface-container-lowest rounded-2xl p-4 sm:p-5 shadow-sm border border-outline-variant hover:border-primary/50 hover:shadow-md transition-all flex flex-col md:flex-row gap-4 justify-between items-start md:items-center group">
            
            <div class="flex gap-4 items-start w-full md:w-auto">
                <div class="w-12 h-12 rounded-xl bg-secondary-container/50 text-secondary flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined">local_shipping</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <h4 class="font-headline-sm font-bold text-on-surface">#{{$commande->reference}}</h4>
                        <span class="bg-secondary-container text-on-secondary-container px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border border-secondary-container">En Cours</span>
                    </div>
                    <p class="text-sm font-body-md-bold text-on-surface truncate">{{$commande->client->nom}} ({{$commande->client->telephone}})</p>
                    <p class="text-xs text-on-surface-variant mt-0.5 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[12px]">schedule</span> 
                        {{$commande->date_commande}} • 
                        @foreach ($commande->detailCommandeClients as $quantite )
                        {{count($quantite->quantite)}} articles
                        @endforeach
                    </p>
                </div>
            </div>

            <div class="flex items-center justify-between w-full md:w-auto gap-4 lg:gap-8 border-t border-outline-variant md:border-t-0 pt-3 md:pt-0">
                <div class="text-left md:text-right flex-1 md:flex-none">
                    <p class="text-[10px] text-on-surface-variant uppercase tracking-wider mb-0.5">Total TTC</p>
                    <p class="font-headline-md text-primary font-black">{{number_format($commande->montant_ttc, 3, ',', ' ')}} F</p>
                </div>
                
                <!-- Lien vers la page de suivi détaillé (votre code précédent) -->
                <a href="#" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-surface-container-highest hover:bg-primary hover:text-on-primary text-on-surface rounded-xl font-body-md-bold transition-colors">
                    <span class="material-symbols-outlined text-sm">visibility</span>
                    <span class="hidden sm:inline">Suivi</span>
                </a>
            </div>
        </div>
          @endforeach

        <!-- CARTE COMMANDE 2 (Nouvelle - En attente) -->
        <div class="bg-surface-container-lowest rounded-2xl p-4 sm:p-5 shadow-sm border border-outline-variant hover:border-primary/50 hover:shadow-md transition-all flex flex-col md:flex-row gap-4 justify-between items-start md:items-center group">
            
            <div class="flex gap-4 items-start w-full md:w-auto">
                <div class="w-12 h-12 rounded-xl bg-error/10 text-error flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform animate-pulse">
                    <span class="material-symbols-outlined">notifications_active</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <h4 class="font-headline-sm font-bold text-on-surface">#CMD-4093</h4>
                        <span class="bg-error-container text-error px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border border-error/20">Nouvelle</span>
                    </div>
                    <p class="text-sm font-body-md-bold text-on-surface truncate">Maquis Le Tonton, Yopougon</p>
                    <p class="text-xs text-on-surface-variant mt-0.5 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[12px]">schedule</span> 
                        Il y a 10 min • 50 Poulets de chair
                    </p>
                </div>
            </div>

            <div class="flex items-center justify-between w-full md:w-auto gap-4 lg:gap-8 border-t border-outline-variant md:border-t-0 pt-3 md:pt-0">
                <div class="text-left md:text-right flex-1 md:flex-none">
                    <p class="text-[10px] text-on-surface-variant uppercase tracking-wider mb-0.5">Total TTC</p>
                    <p class="font-headline-md text-primary font-black">175.000 F</p>
                </div>
                
                <a href="#" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-primary text-on-primary rounded-xl font-body-md-bold shadow-lg shadow-primary/20 hover:opacity-90 active:scale-95 transition-all">
                    <span class="material-symbols-outlined text-sm">check_circle</span>
                    <span class="hidden sm:inline">Traiter</span>
                </a>
            </div>
        </div>

        <!-- CARTE COMMANDE 3 (Livrée) -->
        <div class="bg-surface-container-lowest rounded-2xl p-4 sm:p-5 shadow-sm border border-outline-variant hover:border-primary/50 hover:shadow-md transition-all flex flex-col md:flex-row gap-4 justify-between items-start md:items-center group opacity-75 hover:opacity-100">
            
            <div class="flex gap-4 items-start w-full md:w-auto">
                <div class="w-12 h-12 rounded-xl bg-status-success/10 text-status-success flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined">task_alt</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <h4 class="font-headline-sm font-bold text-on-surface line-through decoration-outline-variant">#CMD-4088</h4>
                        <span class="bg-status-success/10 text-status-success px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border border-status-success/20">Livrée</span>
                    </div>
                    <p class="text-sm font-body-md-bold text-on-surface truncate">Restaurant La Cascade</p>
                    <p class="text-xs text-on-surface-variant mt-0.5 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[12px]">event</span> 
                        Hier, 10:15 • Payé en espèces
                    </p>
                </div>
            </div>

            <div class="flex items-center justify-between w-full md:w-auto gap-4 lg:gap-8 border-t border-outline-variant md:border-t-0 pt-3 md:pt-0">
                <div class="text-left md:text-right flex-1 md:flex-none text-on-surface-variant">
                    <p class="text-[10px] uppercase tracking-wider mb-0.5">Total TTC</p>
                    <p class="font-headline-md font-bold">95.000 F</p>
                </div>
                
                <a href="#" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-surface-container hover:bg-surface-container-highest text-on-surface rounded-xl font-body-md-bold transition-colors">
                    <span class="material-symbols-outlined text-sm">receipt</span>
                    <span class="hidden sm:inline">Facture</span>
                </a>
            </div>
        </div>

    </div>
</main>
@endsection