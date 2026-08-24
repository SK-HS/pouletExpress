@extends('layouts.client.main')
@section('content')

<!-- Librairies Leaflet pour la Carte Interactive -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6 sm:space-y-8 animate-in fade-in duration-500">

@php
    $etapes = ['EN_ATTENTE', 'AFFECTEE','RECUPEREE', 'EN_ROUTE', 'LIVREE','RECEPTIONNEE'];
    $statutActuel = $livraison->statut ?? 'EN_ATTENTE';
    $indexActuel = array_search($statutActuel, $etapes);
    $indexActuel = $indexActuel === false ? 0 : $indexActuel;
    $progressPct = ($indexActuel / (count($etapes) - 1)) * 100;

    $labels = [
        'EN_ATTENTE' => ['titre' => 'Commande reçue',       'desc' => 'En attente d\'affectation.', 'icone' => 'receipt_long'],
        'AFFECTEE'   => ['titre' => 'Livreur affecté',      'desc' => 'Un livreur a été assigné.',  'icone' => 'person_pin_circle'],
        'RECUPEREE'  => ['titre' => 'Colis récupéré',       'desc' => 'Récupéré chez le fournisseur.', 'icone' => 'shopping_bag_speed'],
        'EN_ROUTE'   => ['titre' => 'En cours de livraison','desc' => 'Le livreur est en route.',   'icone' => 'local_shipping'],
        'LIVREE'     => ['titre' => 'Livrée',               'desc' => 'Livrée avec succès.',        'icone' => 'check_circle'],
        'RECEPTIONNEE'     => ['titre' => 'Réceptionnéé',    'desc' => 'Réceptionnéé avec succès.',  'icone' => 'store'],
    ];

    $fraisLivraison = $commandes->detailCommandeClients->firstWhere('type', 'SERVICE');
@endphp

{{-- ──────────────────────────────────────────────────────── --}}
{{-- EN-TÊTE                                                  --}}
{{-- ──────────────────────────────────────────────────────── --}}
<div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
    <div>
        <nav class="flex items-center gap-1.5 text-xs font-semibold text-slate-500 dark:text-slate-400 mb-2 overflow-x-auto">
            <a href="{{ route('Clients-Espace') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400 shrink-0">Accueil</a>
            <span class="material-symbols-outlined text-xs shrink-0">chevron_right</span>
            <a href="{{ route('Clients-Espace') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400 shrink-0">Espace Client</a>
            <span class="material-symbols-outlined text-xs shrink-0">chevron_right</span>
            <span class="shrink-0 text-emerald-700 dark:text-emerald-400 font-bold">Suivi en direct</span>
        </nav>
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-emerald-800 dark:text-emerald-400">
            Suivi de Commande #{{ $commandes->reference }}
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 font-medium">
            Passée le {{ \Carbon\Carbon::parse($commandes->date_commande)->format('d/m/Y à H:i') }}
        </p>
    </div>

    <div class="flex flex-wrap items-center gap-3">
        @if($statutActuel == 'LIVREE' && $commandes->commande_recu == 0)
            <form method="POST" action="{{ route('Valider-Livraison-Commande', $commandes->id) }}">
                @csrf
                @method('PUT')
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm flex items-center gap-2 shadow-lg shadow-emerald-600/20 transition-all active:scale-95">
                    <span class="material-symbols-outlined text-lg">check_circle</span>
                    Marquer comme reçue
                </button>
            </form>
        @elseif($commandes->commande_recu)
            <div class="px-5 py-2.5 rounded-xl bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 font-bold text-sm flex items-center gap-2 shadow-sm">
                <span class="material-symbols-outlined text-lg">check_circle</span>
                Commande confirmée reçue
            </div>
        @endif

        <span id="statut-pill" class="inline-flex items-center gap-2 text-sm font-bold py-2 px-5 rounded-full border shadow-sm
            {{ $statutActuel === 'LIVREE' ? 'bg-emerald-100 text-emerald-700 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800' : 'bg-amber-100 text-amber-700 border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800' }}">
            @if($statutActuel !== 'LIVREE')
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
            @else
                <span class="material-symbols-outlined text-sm">check_circle</span>
            @endif
            <span id="statut-label">{{ str_replace('_', ' ', $statutActuel) }}</span>
        </span>
    </div>
</div>

{{-- ──────────────────────────────────────────────────────── --}}
{{-- GRILLE PRINCIPALE                                        --}}
{{-- ──────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 mt-4">

    {{-- ────────────────────────────────────────────────── --}}
    {{-- COLONNE GAUCHE : Stepper + Carte                 --}}
    {{-- ────────────────────────────────────────────────── --}}
    <div class="lg:col-span-7 space-y-6">

        {{-- STEPPER --}}
        <div class="bg-white dark:bg-[#152238] p-4 sm:p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="relative flex justify-between items-start">
                <div class="absolute top-5 left-8 right-8 h-1 bg-slate-100 dark:bg-slate-800 z-0 rounded-full">
                    <div id="stepper-progress-bar" class="h-full bg-emerald-600 transition-all duration-700 rounded-full" style="width: {{ $progressPct }}%;"></div>
                </div>
                @foreach ($etapes as $i => $etape)
                <div class="stepper-step relative z-10 flex flex-col items-center w-1/5" data-step="{{ $i }}" data-statut="{{ $etape }}">
                    <div class="step-circle w-10 h-10 sm:w-12 sm:h-12 rounded-full flex items-center justify-center shadow-md transition-all duration-500 
                        {{ $i <= $indexActuel ? 'bg-emerald-600 text-white ring-4 ring-emerald-600/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500' }}">
                        <span class="material-symbols-outlined text-lg sm:text-xl">{{ $labels[$etape]['icone'] }}</span>
                    </div>
                    <span class="step-text text-[10px] sm:text-xs mt-2 text-center font-bold leading-tight {{ $i <= $indexActuel ? 'text-emerald-700 dark:text-emerald-400' : 'text-slate-500 dark:text-slate-400' }}">
                        {{ $labels[$etape]['titre'] }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- CARTE INTERACTIVE LEAFLET --}}
        <div class="relative h-[400px] sm:h-[500px] bg-slate-200 dark:bg-slate-800 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 shadow-md">
            
            <!-- Conteneur de la carte -->
            <div id="map" class="absolute inset-0 z-0 filter dark:brightness-75 dark:contrast-125 dark:hue-rotate-180 dark:invert"></div>

            {{-- Carte livreur flottante --}}
            <div id="livreur-card" class="absolute bottom-4 left-4 right-4 sm:bottom-6 sm:left-6 sm:right-auto sm:w-80 bg-white/95 dark:bg-[#152238]/95 backdrop-blur-md p-4 sm:p-5 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 z-10 space-y-3 {{ !$livraison?->livreur_id ? 'hidden' : '' }}">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-emerald-600 shadow-sm flex-shrink-0 bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-700 dark:text-emerald-400">
                        <span class="material-symbols-outlined text-2xl">sports_motorsports</span>
                    </div>
                    <div class="flex-grow min-w-0">
                        <p class="text-[10px] uppercase font-bold tracking-wider text-slate-500 dark:text-slate-400 mb-0.5">Votre livreur</p>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white truncate" id="livreur-nom">{{ $commandes->livreur?->nom ?? 'Livreur inconnu' }}</h4>
                        <p class="text-[11px] font-medium text-slate-500 dark:text-slate-400" id="livreur-telephone">{{ $commandes->livreur?->telephone ?? '' }}</p>
                    </div>
                    <a id="call-livreur-btn" href="tel:{{ $commandes->livreur?->telephone }}" class="w-10 h-10 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white flex items-center justify-center shadow-lg transition-transform active:scale-95 flex-shrink-0 {{ !$commandes->livreur?->telephone ? 'pointer-events-none opacity-40' : '' }}">
                        <span class="material-symbols-outlined text-xl">call</span>
                    </a>
                </div>
                @if($livraison?->distance)
                <div class="border-t border-slate-200 dark:border-slate-700 pt-3 mt-3 flex justify-between items-center text-xs">
                    <span class="text-slate-600 dark:text-slate-300 font-bold">Distance estimée</span>
                    <span class="text-emerald-700 dark:text-emerald-400 font-bold bg-emerald-50 dark:bg-emerald-900/30 px-2 py-0.5 rounded-full">{{ $livraison->distance }} km</span>
                </div>
                @endif
            </div>

            {{-- Message d'attente (si pas encore de livreur) --}}
            <div id="no-livreur-msg" class="absolute bottom-4 left-4 right-4 sm:bottom-6 sm:left-6 sm:right-auto sm:w-80 bg-white/95 dark:bg-[#152238]/95 backdrop-blur-md p-5 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 z-10 text-center {{ $livraison?->livreur_id ? 'hidden' : '' }}">
                <div class="w-12 h-12 bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400 rounded-full flex items-center justify-center mx-auto mb-3 animate-pulse">
                    <span class="material-symbols-outlined text-2xl">hourglass_top</span>
                </div>
                <h4 class="text-sm font-bold text-slate-900 dark:text-white">En attente d'un livreur</h4>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">Un livreur proche de votre zone va bientôt prendre en charge votre commande.</p>
            </div>
        </div>
    </div>

    {{-- ────────────────────────────────────────────────── --}}
    {{-- COLONNE DROITE : RÉCAPITULATIF                     --}}
    {{-- ────────────────────────────────────────────────── --}}
    <div class="lg:col-span-5 space-y-6">
        
        <div class="bg-white dark:bg-[#152238] p-5 sm:p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-5">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-slate-800 pb-4">
                <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-2xl">receipt_long</span>
                Détail de la commande
            </h3>

            {{-- Articles --}}
            <div class="space-y-3 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                @forelse ($commandes->detailCommandeClients->where('type', 'PRODUIT') as $detail)
                <div class="flex items-center gap-4 bg-slate-50 dark:bg-slate-800/50 p-3 rounded-xl border border-slate-100 dark:border-slate-700/50">
                    <div class="w-14 h-14 rounded-lg bg-slate-200 dark:bg-slate-900 overflow-hidden flex-shrink-0 border border-slate-200 dark:border-slate-800">
                        @if($detail->produitFournisseur?->produit?->image)
                            <img src="/storage/{{ $detail->produitFournisseur->produit->image }}" alt="Produit" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400 dark:text-slate-500">
                                <span class="material-symbols-outlined">image</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-grow min-w-0">
                        <h4 class="font-bold text-sm text-slate-900 dark:text-white leading-tight truncate">
                            {{ $detail->produitFournisseur?->produit?->nom ?? 'Produit inconnu' }}
                        </h4>
                        <p class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold uppercase tracking-wider mt-0.5 truncate">
                            Vendu par {{ $detail->produitFournisseur?->fournisseur?->nom_ferme ?? 'AgriManager' }}
                        </p>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 font-medium">Qté: {{ $detail->quantite }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <span class="text-sm font-bold text-slate-900 dark:text-white block">
                            {{ number_format($detail->montant, 0, ',', ' ') }} F
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                    <p class="text-sm text-slate-500 dark:text-slate-400">Aucun produit trouvé.</p>
                </div>
                @endforelse
            </div>

            {{-- Section des Totaux --}}
            <div class="border-t-2 border-dashed border-slate-200 dark:border-slate-700 pt-4 space-y-2.5">
                <div class="flex justify-between font-medium text-sm text-slate-600 dark:text-slate-400">
                    <span>Sous-total articles</span>
                    <span>{{ number_format($commandes->montant_brut, 0, ',', ' ') }} FCFA</span>
                </div>
                
                @if($fraisLivraison)
                <div class="flex justify-between font-medium text-sm text-slate-600 dark:text-slate-400">
                    <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">local_shipping</span> 
                        Frais de livraison
                    </span>
                    <span>{{ number_format($fraisLivraison->montant, 0, ',', ' ') }} FCFA</span>
                </div>
                @endif
                
                @if($commandes->remise > 0)
                <div class="flex justify-between font-medium text-sm text-red-500 dark:text-red-400">
                    <span>Remise appliquée</span>
                    <span>-{{ number_format($commandes->remise, 0, ',', ' ') }} FCFA</span>
                </div>
                @endif
                
                <div class="flex justify-between items-end text-slate-900 dark:text-white pt-3 border-t border-slate-200 dark:border-slate-700">
                    <span class="font-bold">Total Payé TTC</span>
                    <span class="text-xl text-emerald-700 dark:text-emerald-400 font-black">{{ number_format($commandes->montant_ttc, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>

            {{-- Informations de livraison statiques --}}
            <div class="bg-emerald-50 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-800/50 p-4 rounded-xl space-y-2 mt-2">
                <p class="font-bold text-xs text-emerald-700 dark:text-emerald-400 uppercase tracking-wider mb-2">Lieu et Contact</p>
                <div class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-[16px] text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5">location_on</span>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                        <strong class="text-slate-900 dark:text-white block mb-0.5">Quartier: {{ $commandes->quartier?->nom_quartier ?? 'Non défini' }}</strong>
                        Détails: {{ $commandes->lieu_livraison ?? 'Non défini' }}
                    </p>
                </div>
                <div class="flex items-start gap-2 mt-2">
                    <span class="material-symbols-outlined text-[16px] text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5">phone_iphone</span>
                    <p class="text-xs text-slate-600 dark:text-slate-300">
                        <strong class="text-slate-900 dark:text-white block mb-0.5">Tél. de réception:</strong>
                        {{ $commandes->telephone_livraison }}
                    </p>
                </div>
                <div class="flex items-start gap-2 mt-2 border-t border-emerald-200 dark:border-emerald-800/50 pt-2">
                     <span class="material-symbols-outlined text-[16px] text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5">storefront</span>
                    <p class="text-xs text-slate-600 dark:text-slate-300">
                        <strong class="text-slate-900 dark:text-white block mb-0.5">Fournisseur:</strong>
                        {{ $commandes->fournisseur?->nom_ferme ?? 'AgriManager' }}<br>
                        {{ $commandes->fournisseur?->telephone ?? '' }}
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
</main>

{{-- ──────────────────────────────────────────────────────── --}}
{{-- SCRIPT DE LA CARTE LEAFLET ET DU POLLING AJAX            --}}
{{-- ──────────────────────────────────────────────────────── --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* --- 1. INITIALISATION DE LA CARTE LEAFLET --- */
    let map = L.map('map').setView([5.30966, -4.01266], 12); 
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Style des icônes de la carte
    const iconClient = L.divIcon({ html: '<div class="w-10 h-10 bg-orange-500 text-white rounded-full flex items-center justify-center shadow-lg border-2 border-white"><span class="material-symbols-outlined text-[20px]">home</span></div>', className: '' });
    const iconFournisseur = L.divIcon({ html: '<div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center shadow-lg border-2 border-white"><span class="material-symbols-outlined text-[20px]">storefront</span></div>', className: '' });
    const iconLivreur = L.divIcon({ html: '<div class="w-12 h-12 bg-black text-white rounded-full flex items-center justify-center shadow-xl border-2 border-white animate-bounce"><span class="material-symbols-outlined text-[24px]">sports_motorsports</span></div>', className: '' });

    // Les Popups enrichis (nom, tel, lieu)
    const clientPopupHtml = `
        <div style="font-family: sans-serif; color: #1e293b;">
            <strong style="display:block; font-size: 14px; margin-bottom: 4px; color: #047857;">Livraison Client</strong>
            <span style="font-size: 12px; font-weight: bold;">{{ $commandes->client?->nom ?? 'Client' }}</span><br>
            <span style="font-size: 12px; color: #475569;">Tél: {{ $commandes->telephone_livraison }}</span><br>
            <span style="font-size: 11px; color: #64748b; margin-top:4px; display:block;">{{ $commandes->quartier?->nom_quartier }}</span>
        </div>
    `;

    const fournisseurPopupHtml = `
        <div style="font-family: sans-serif; color: #1e293b;">
            <strong style="display:block; font-size: 14px; margin-bottom: 4px; color: #1d4ed8;">Le Fournisseur</strong>
            <span style="font-size: 12px; font-weight: bold;">{{ $commandes->fournisseur?->nom_ferme ?? 'AgriManager' }}</span><br>
            <span style="font-size: 12px; color: #475569;">Tél: {{ $commandes->fournisseur?->telephone ?? '' }}</span>
        </div>
    `;

    // Récupération des positions
    const clientLat = {{ $commandes->latitude ?? 'null' }};
    const clientLng = {{ $commandes->longitude ?? 'null' }};
    const fournisseurLat = {{ $commandes->fournisseur?->latitude ?? 'null' }};
    const fournisseurLng = {{ $commandes->fournisseur?->longitude ?? 'null' }};

    const markersGroup = L.featureGroup();

    if (clientLat && clientLng) {
        L.marker([clientLat, clientLng], {icon: iconClient}).bindPopup(clientPopupHtml).addTo(markersGroup);
    }
    if (fournisseurLat && fournisseurLng) {
        L.marker([fournisseurLat, fournisseurLng], {icon: iconFournisseur}).bindPopup(fournisseurPopupHtml).addTo(markersGroup);
    }

    // Centrer la carte
    if(markersGroup.getLayers().length > 0){
        markersGroup.addTo(map);
        map.fitBounds(markersGroup.getBounds(), {padding: [50, 50]});
    }

    /* --- 2. MARQUEUR DU LIVREUR (TEMPS RÉEL) --- */
    let markerLivreur = null;
    let initLivreurLat = {{ $livraison->latitude ?? 'null' }};
    let initLivreurLng = {{ $livraison->longitude ?? 'null' }};
    
    if (initLivreurLat && initLivreurLng) {
        markerLivreur = L.marker([initLivreurLat, initLivreurLng], {icon: iconLivreur}).bindPopup("<b>Livreur en route !</b>").addTo(map);
    }

    /* --- 3. POLLING AJAX --- */
    const pollingUrl = '{{ route('commande.suivi.polling', $commandes->reference) }}';
    
    function polling() {
        fetch(pollingUrl, { headers: { 'Accept': 'application/json' }})
        .then(res => res.json())
        .then(data => {
            // A. MISE À JOUR GPS DU LIVREUR
            if (data.livreur && data.livreur.latitude && data.livreur.longitude) {
                const newLatLng = new L.LatLng(data.livreur.latitude, data.livreur.longitude);
                
                if (markerLivreur) {
                    markerLivreur.setLatLng(newLatLng);
                } else {
                    markerLivreur = L.marker(newLatLng, {icon: iconLivreur}).bindPopup("<b>Livreur en route !</b>").addTo(map);
                }
            }

            // B. MISE À JOUR UI DU LIVREUR
            if (data.livreur && data.livreur.nom) {
                document.getElementById('livreur-nom').textContent = data.livreur.nom;
                document.getElementById('livreur-telephone').textContent = data.livreur.telephone ?? '';
                document.getElementById('livreur-card').classList.remove('hidden');
                document.getElementById('no-livreur-msg').classList.add('hidden');
                
                const btnCall = document.getElementById('call-livreur-btn');
                if(data.livreur.telephone) {
                    btnCall.href = 'tel:' + data.livreur.telephone;
                    btnCall.classList.remove('pointer-events-none', 'opacity-40');
                }
            }
        })
        .catch(err => console.error('Erreur polling:', err));
    }

    // Le polling s'exécute toutes les 6 secondes
    setInterval(polling, 6000);
});
</script>

<style>
/* Astuce Leaflet en Mode Sombre : on inverse les couleurs de la carte de base pour qu'elle soit foncée ! */
html.dark #map .leaflet-layer,
html.dark #map .leaflet-control-zoom-in,
html.dark #map .leaflet-control-zoom-out,
html.dark #map .leaflet-control-attribution {
  filter: invert(100%) hue-rotate(180deg) brightness(95%) contrast(90%);
}

/* CSS pour la barre de défilement du résumé */
.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
html.dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
html.dark .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #475569; }
</style>

@endsection