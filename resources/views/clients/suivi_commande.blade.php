@extends('layouts.client.main')
@section('content')
 
 <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6 sm:space-y-8">
          @php
        // Ordre des étapes de la timeline
        $etapes = ['EN_ATTENTE', 'AFFECTEE', 'EN_ROUTE', 'LIVREE'];
        $statutActuel = $livraison->statut ?? 'EN_ATTENTE';
        $indexActuel = array_search($statutActuel, $etapes);
        $indexActuel = $indexActuel === false ? 0 : $indexActuel;

        $labels = [
            'EN_ATTENTE' => ['titre' => 'Commande reçue', 'desc' => 'Votre commande est enregistrée, en attente d\'affectation à un livreur.', 'icone' => 'receipt_long'],
            'AFFECTEE'   => ['titre' => 'Livreur affecté', 'desc' => 'Un livreur a été assigné à votre commande.', 'icone' => 'person_pin_circle'],
            'EN_ROUTE'   => ['titre' => 'En cours de livraison', 'desc' => 'Votre livreur est en route vers vous.', 'icone' => 'local_shipping'],
            'LIVREE'     => ['titre' => 'Livrée', 'desc' => 'Votre commande a été livrée avec succès.', 'icone' => 'check_circle'],
        ];
    @endphp

        <!-- Breadcrumbs & Heading -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <nav class="flex items-center gap-1.5 sm:gap-2 text-xs font-semibold text-slate-500 mb-2 overflow-x-auto">
                    <a href="{{route('Clients-Espace')}}" class="hover:text-emerald-600 shrink-0">Accueil</a>
                    <span class="material-symbols-outlined text-xs shrink-0">chevron_right</span>
                    <a href="{{route('Clients-Espace')}}" class="hover:text-emerald-600 shrink-0">Espace Client</a>
                    <span class="material-symbols-outlined text-xs shrink-0">chevron_right</span>
                    <span class="shrink-0">Suivi en direct</span>
                </nav>
                <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-emerald-800 dark:text-emerald-400">
                    Suivi de Commande #{{ $commandes->reference }}
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">Livraison de volaille estimée aujourd'hui vers {{ $commandes->reference }}</p>
            </div>

            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                <span id="tracking-status-pill" class="badge badge-warning text-xs sm:text-sm py-1.5 px-3 sm:px-4">
                    <span class="pulse-dot"></span> {{ $commandes->statut }}
                </span>
                <button id="simulate-step-btn" class="btn-outline text-xs" title="Simuler l'étape suivante pour démonstration">
                    <span class="material-symbols-outlined text-sm">play_arrow</span> <span class="hidden xs:inline">Avancer l'étape</span> (Simulateur)
                </button>
            </div>
        </div>

        <!-- Bento Grid Tracking Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8">
            
            <!-- Main Map & Progress Column (Left 8 Cols) -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- Interactive Stepper Progress -->
                <div class="bg-white dark:bg-[#152238] p-4 sm:p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                    <div class="relative flex justify-between items-start">
                        
                        <!-- Connecting Background Line -->
                        <div class="absolute top-4 sm:top-5 left-6 sm:left-8 right-6 sm:right-8 h-1 bg-slate-200 dark:bg-slate-700 z-0">
                            <div id="stepper-progress-bar" class="h-full bg-emerald-600 transition-all duration-700" style="width: 66.6%;"></div>
                        </div>

                        <!-- Step 1 -->
                        
                         @foreach ($etapes as $i => $etape)
                        <div class="stepper-step {{ $i < count($etapes) - 1 ? 'pb-8' : '' }} relative z-10 flex flex-col items-center w-1/4">
                            <div class="step-circle w-8 h-8 sm:w-10 sm:h-10 rounded-full  flex items-center justify-center shadow-md
                            {{ $i <= $indexActuel ? 'bg-emerald-600 text-white' : 'dark:bg-slate-700 text-slate-500 text-on-surface-variant' }}
                            ">
                                <span class="material-symbols-outlined text-lg sm:text-xl">{{ $labels[$etape]['icone'] }}</span>
                            </div>

                            <span class="step-text font-bold {{ $i <= $indexActuel ? 'text-emerald-700 dark:text-emerald-400' : 'font-semibold text-slate-400' }}  text-[10px] sm:text-xs mt-1.5 sm:mt-2 text-center"> {{ $labels[$etape]['titre'] }}</span>
                           
                            
                            @if ($etape === 'AFFECTEE' && $livraison?->date_affectation)
                            <span class="text-[9px] sm:text-[10px] text-slate-400">{{ $livraison->date_affectation->format('d/m/Y à H:i') }}</span>
                             
                            @endif
                            @if ($etape === 'EN_ROUTE' && $livraison?->date_depart)
                                <span class="text-[9px] sm:text-[10px] text-slate-400">{{ $livraison->date_depart->format('d/m/Y à H:i') }}</span>
                            @endif
                            @if ($etape === 'LIVREE' && $livraison?->date_arrivee)
                            <span class="text-[9px] sm:text-[10px] text-slate-400">{{ $livraison->date_arrivee->format('d/m/Y à H:i') }}</span>
              
                            @endif
                        </div>
                        @endforeach

                        {{-- <!-- Step 2 -->
                        <div class="stepper-step relative z-10 flex flex-col items-center w-1/4">
                            <div class="step-circle w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center shadow-md">
                                <span class="material-symbols-outlined text-lg sm:text-xl">inventory</span>
                            </div>
                            <span class="step-text font-bold text-emerald-700 dark:text-emerald-400 text-[10px] sm:text-xs mt-1.5 sm:mt-2 text-center">Prépa</span>
                            <span class="text-[9px] sm:text-[10px] text-slate-400">09:15</span>
                        </div>

                        <!-- Step 3 -->
                        <div class="stepper-step relative z-10 flex flex-col items-center w-1/4">
                            <div class="step-circle w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center shadow-md ring-2 sm:ring-4 ring-emerald-100 dark:ring-emerald-900/50">
                                <span class="material-symbols-outlined text-lg sm:text-xl">local_shipping</span>
                            </div>
                            <span class="step-text font-bold text-emerald-700 dark:text-emerald-400 text-[10px] sm:text-xs mt-1.5 sm:mt-2 text-center">En cours</span>
                            <span class="text-[9px] sm:text-[10px] text-slate-400">14:20</span>
                        </div>

                        <!-- Step 4 -->
                        <div class="stepper-step relative z-10 flex flex-col items-center w-1/4 opacity-40">
                            <div class="step-circle w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-500 flex items-center justify-center">
                                <span class="material-symbols-outlined text-lg sm:text-xl">home</span>
                            </div>
                            <span class="step-text font-semibold text-slate-400 text-[10px] sm:text-xs mt-1.5 sm:mt-2 text-center">Livré</span>
                            <span class="text-[9px] sm:text-[10px] text-slate-400">--:--</span>
                        </div> --}}
                    </div>
                </div>

                <!-- Satellite Live Map Display Widget -->
                <div class="relative h-[380px] sm:h-[460px] bg-slate-200 dark:bg-slate-800 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 shadow-md">
                    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1524661135-423995f22d0b?q=80&w=1200&auto=format&fit=crop');">
                        <!-- Overlay Map Effect -->
                        <div class="absolute inset-0 bg-emerald-950/20 backdrop-contrast-125"></div>
                    </div>

                    <!-- Driver Info Floating Card Overlay -->
                    <div class="absolute bottom-4 left-4 right-4 sm:bottom-6 sm:left-6 sm:right-auto sm:w-80 glass-panel p-4 sm:p-5 rounded-2xl shadow-xl border border-white/40 z-10 space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full overflow-hidden border-2 border-emerald-600 shadow-sm flex-shrink-0">
                                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop" alt="Livreur Koffi Konan" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-grow min-w-0">
                                <h4 class="font-bold text-sm sm:text-base text-slate-900 dark:text-white truncate">{{$commandes->livreur?->nom}}</h4>
                                <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-300">Contact: {{$commandes->livreur?->telephone}}</p>
                                <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-300">Livreur PouletExpress</p>
                            </div>
                            <button id="call-driver-btn" class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-emerald-700 hover:bg-emerald-800 text-white flex items-center justify-center shadow-md transition-transform active:scale-95 flex-shrink-0" title="Appeler le livreur">
                                <span class="material-symbols-outlined text-base sm:text-lg">call</span>
                            </button>
                        </div>

                        <div class="border-t border-slate-200/60 dark:border-slate-700/60 pt-2.5 sm:pt-3 space-y-1.5 sm:space-y-2">
                            <div class="flex justify-between text-xs font-semibold">
                                <span class="text-slate-600 dark:text-slate-300">Arrivée estimée dans</span>
                                <span id="live-eta-timer" class="text-emerald-700 dark:text-emerald-400 font-mono font-bold">12:45 min</span>
                            </div>
                            <div class="w-full bg-slate-200 dark:bg-slate-700 h-1.5 rounded-full overflow-hidden">
                                <div class="bg-emerald-600 h-full rounded-full animate-pulse" style="width: 75%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Sidebar Column (Right 4 Cols) -->
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white dark:bg-[#152238] p-5 sm:p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-5 sm:space-y-6">
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white border-b border-slate-100 dark:border-slate-800 pb-3">Récapitulatif de Commande</h3>
                    
                    <!-- Items list -->
                    <div class="space-y-4">
                        @foreach ($commandes->detailCommandeClients as $detail)
                        @if($detail->type === "PRODUIT")
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-emerald-700 overflow-hidden flex-shrink-0">
                                <img src="/storage/{{$detail->produitFournisseur?->produit?->image }}" alt="{{ $detail->produitFournisseur?->produit?->nom }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-grow">
                                <h4 class="font-bold text-xs sm:text-sm">{{ $detail->quantite }} x {{ $detail->produitFournisseur?->produit?->nom }}</h4>
                                <p class="text-[11px] text-slate-500">{{ $detail->produitFournisseur?->fournisseur?->nom }} {{ $detail->produitFournisseur?->fournisseur?->nom_ferme }}</p>
                                <span class="text-xs font-bold text-emerald-700 dark:text-emerald-400">{{ $detail->montant }} FCFA</span>
                            </div>
                        </div>
                        @endif
                        @endforeach

                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-amber-600 overflow-hidden flex-shrink-0">
                                <img src="https://images.unsplash.com/photo-1516467508483-a7212febe31a?q=80&w=200&auto=format&fit=crop" alt="Alvéole d'oeufs" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-grow">
                                <h4 class="font-bold text-xs sm:text-sm">1x Alvéole d'œufs frais (30)</h4>
                                <p class="text-[11px] text-slate-500">Qualité bio calibre A</p>
                                <span class="text-xs font-bold text-emerald-700 dark:text-emerald-400">2 500 FCFA</span>
                            </div>
                        </div>
                    </div>

                    <!-- Price Breakdown -->
                    <div class="border-t border-slate-100 dark:border-slate-800 pt-4 space-y-2 text-xs">
                        <div class="flex justify-between text-slate-600 dark:text-slate-400">
                            <span>Sous-total</span>
                            <span>{{ $commandes->montant_brut }} FCFA</span>
                        </div>
                         @foreach ($commandes->detailCommandeClients as $detail)
                        @if($detail->type==="SERVICE")
                        <div class="flex justify-between text-slate-600 dark:text-slate-400">
                            <span>Frais de livraison</span>
                            <span>{{ $detail->montant }}  FCFA</span>
                        </div>
                        @endif
                        @endforeach
                        <div class="flex justify-between font-bold text-sm text-slate-900 dark:text-white pt-2 border-t border-slate-100 dark:border-slate-800">
                            <span>Total Réglé</span>
                            <span class="text-emerald-700 dark:text-emerald-400">{{ $commandes->montant_ttc }}  FCFA</span>
                        </div>
                    </div>

                    <!-- Payment & Delivery Info -->
                    <div class="bg-slate-50 dark:bg-slate-800/60 p-4 rounded-xl space-y-2 text-xs">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-slate-700 dark:text-slate-300">Paiement Mobile</span>
                            <span class="badge badge-success">Orange Money ✅</span>
                        </div>
                        <p class="text-slate-500 dark:text-slate-400">
                             <strong>Adresse :</strong> Bingerville Riviera 3, Abidjan
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection