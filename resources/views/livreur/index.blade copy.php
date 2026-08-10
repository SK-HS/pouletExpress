@extends('layouts.livreur.main')
@section('content')

   <main class="md:ml-64 flex-1 pb-20 md:pb-8 p-4 md:p-8 max-w-7xl mx-auto space-y-6">
    
    <!-- Welcome Hero Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-900 via-emerald-800 to-emerald-950 text-white p-6 md:p-8 shadow-xl">
      <div class="relative z-10 max-w-xl space-y-3">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-700/60 text-emerald-200 font-semibold text-xs backdrop-blur-sm border border-emerald-500/30">
          <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
          <span>Itinéraire Optimisé - Abidjan & Environs</span>
        </div>
        <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight">Bonjour {{ Auth::guard('livreur')->user()?->nom}} ! Ready pour les livraisons ?</h2>
        <p class="text-emerald-100/90 text-sm md:text-base leading-relaxed">
          Vous avez 4 collectes prévues ce matin. Prochain enlèvement à la <strong class="text-amber-400">Ferme Volaille Anyama</strong> à 10h30.
        </p>
        <div class="pt-2 flex flex-wrap gap-3">
          <a href="{{route('Localisation-Produit-livreur')}}" class="px-5 py-2.5 rounded-xl bg-orange-600 hover:bg-orange-500 text-white font-bold text-sm shadow-lg shadow-orange-900/30 transition flex items-center gap-2">
            <span class="material-symbols-outlined">map</span>
            <span>Démarrer le Guidage GPS</span>
          </a>
          <a href="{{route('Commande-Livreur')}}" class="px-5 py-2.5 rounded-xl bg-emerald-800/80 hover:bg-emerald-700 text-emerald-100 font-semibold text-sm border border-emerald-600/40 transition flex items-center gap-2">
            <span>Voir les 4 courses</span>
            <span class="material-symbols-outlined">arrow_forward</span>
          </a>
        </div>
      </div>
      <span class="material-symbols-outlined text-[180px] text-white/5 absolute -right-6 -bottom-10 pointer-events-none select-none">local_shipping</span>
    </div>

    <!-- Key Performance Metrics (Bento Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <div class="p-5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col justify-between hover:shadow-md transition">
        <div class="flex items-center justify-between">
          <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Gains du jour</span>
          <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center">
            <span class="material-symbols-outlined">payments</span>
          </div>
        </div>
        <div class="mt-4">
          <h3 class="text-2xl font-extrabold text-slate-900 dark:text-white">45,500 <span class="text-sm font-semibold text-slate-500">FCFA</span></h3>
          <p class="text-xs font-semibold text-emerald-600 mt-1 flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">trending_up</span> +18% par rapport à hier
          </p>
        </div>
      </div>
             
              
      <div class="p-5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col justify-between hover:shadow-md transition">
        <div class="flex items-center justify-between">
          <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Livraisons Effectuées</span>
          <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
            <span class="material-symbols-outlined">task_alt</span>
          </div>
        </div>
        <div class="mt-4">
          <h3 class="text-2xl font-extrabold text-slate-900 dark:text-white">8 <span class="text-sm font-semibold text-slate-500">commandes</span></h3>
          <p class="text-xs font-medium text-slate-500 mt-1">Objectif journalier: 12</p>
        </div>
      </div>

      <div class="p-5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col justify-between hover:shadow-md transition">
        <div class="flex items-center justify-between">
          <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Distance Parcourue</span>
          <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center">
            <span class="material-symbols-outlined">speed</span>
          </div>
        </div>
        <div class="mt-4">
          <h3 class="text-2xl font-extrabold text-slate-900 dark:text-white">64.2 <span class="text-sm font-semibold text-slate-500">km</span></h3>
          <p class="text-xs font-medium text-slate-500 mt-1">Secteur Abidjan Nord</p>
        </div>
      </div>

      <div class="p-5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col justify-between hover:shadow-md transition">
        <div class="flex items-center justify-between">
          <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Satisfaction Client</span>
          <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center">
            <span class="material-symbols-outlined">star</span>
          </div>
        </div>
        <div class="mt-4">
          <h3 class="text-2xl font-extrabold text-slate-900 dark:text-white">4.95 <span class="text-sm font-semibold text-slate-500">/ 5</span></h3>
          <p class="text-xs font-semibold text-amber-500 mt-1">99% avis 5 étoiles</p>
        </div>
      </div>
    </div>
   

    <!-- Active Delivery Priority Card -->
 @foreach ($commandes as $cmd )
                
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100 dark:border-slate-700">
        <div class="flex items-center gap-3">
          <span class="px-3 py-1 rounded-full badge-transit text-xs font-extrabold">COURSE PRIORITAIRE EN COURS</span>
          <span class="font-mono text-sm font-bold text-slate-500">#{{ $cmd['reference'] }}</span>
        </div>
        <span class="text-xs font-semibold text-slate-400">Échéance: Aujourd'hui 11h30</span>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 py-6">
        <div class="flex items-start gap-4">
          <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 font-bold flex items-center justify-center shrink-0">
            1
          </div>
          <div>
            <p class="text-xs font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">Point de Collecte</p>
            <h4 class="font-bold text-slate-900 dark:text-white">Ferme Volaille Anyama</h4>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Route d'Anyama - 120 Poulets de chair</p>
          </div>
        </div>

        <div class="flex items-start gap-4">
          <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 font-bold flex items-center justify-center shrink-0">
            2
          </div>
          <div>
            <p class="text-xs font-bold text-blue-700 dark:text-blue-400 uppercase tracking-wider">Trajet Estimé</p>
            <h4 class="font-bold text-slate-900 dark:text-white">18.4 km (approx. 24 min)</h4>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Autoroute du Nord & Blvd Mitterrand</p>
          </div>
        </div>

        <div class="flex items-start gap-4">
          <div class="w-10 h-10 rounded-full bg-orange-100 dark:bg-orange-900/50 text-orange-700 dark:text-orange-300 font-bold flex items-center justify-center shrink-0">
            3
          </div>
          <div>
            <p class="text-xs font-bold text-orange-700 dark:text-orange-400 uppercase tracking-wider">Destinataire Final</p>
            <h4 class="font-bold text-slate-900 dark:text-white">{{$cmd['commande']->client->nom}}</h4>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{$cmd['commande']->client->adresse}}</p>
          </div>
        </div>
      </div>

      <div class="pt-4 border-t border-slate-100 dark:border-slate-700 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-2">
          <span class="text-xs text-slate-400 font-medium">Montant à encaisser :</span>
          <span class="text-lg font-extrabold text-emerald-700 dark:text-emerald-400">{{ $cmd['total'] }} FCFA</span>
        </div>
        <form method="POST" action="{{ route('Livreur-Accepter-Commande', $cmd['id']) }}" class="accept-form">
                @csrf
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-sm shadow transition flex items-center gap-2">
                    Accepter la Commande
                     <span class="material-symbols-outlined">navigation</span>
                </button>
            </form>
        {{-- <a href="geolocalisation.html" class="px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-sm shadow transition flex items-center gap-2">
          <span class="material-symbols-outlined">navigation</span>
          <span>Ouvrir la Carte de Trajet</span>
        </a> --}}
      </div>
    </div>
    @endforeach

  </main>

@endsection