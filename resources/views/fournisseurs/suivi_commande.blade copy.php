@extends('layouts.fournisseur.main')
@section('content')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

@php
    $etapes = ['EN_ATTENTE', 'AFFECTEE', 'RECUPEREE', 'EN_ROUTE', 'LIVREE'];
    $statutActuel = $livraison->statut ?? 'EN_ATTENTE';
    $indexActuel = array_search($statutActuel, $etapes);
    $indexActuel = $indexActuel === false ? 0 : $indexActuel;

    $labels = [
        'EN_ATTENTE' => ['titre' => 'Commande reçue',    'desc' => 'En attente d\'affectation à un livreur.'],
        'AFFECTEE'   => ['titre' => 'Livreur affecté',   'desc' => 'Prêt pour l\'enlèvement.'],
        'EN_ROUTE'   => ['titre' => 'Livreur en route',  'desc' => 'La commande est en cours de livraison.'],
        'RECUPEREE'  => ['titre' => 'Commande récupérée', 'desc' => 'La commande a été récupérée par le livreur.'],
        'LIVREE'     => ['titre' => 'Livrée',            'desc' => 'Commande livrée avec succès.'],
    ];

    $articles = $commande->detailCommandeClients->where('type', 'PRODUIT');
@endphp

<main class="lg:ml-64 p-4 lg:p-margin-desktop grid grid-cols-12 gap-4 lg:gap-gutter animate-in fade-in duration-500 pb-28">

    {{-- ── Carte + Chat ─────────────────────────────────────── --}}
    <div class="col-span-12 lg:col-span-8 order-1 lg:order-2 flex flex-col gap-4">

        <section class="relative bg-surface-container-high rounded-xl overflow-hidden h-[350px] lg:h-[450px] shadow-sm border border-outline-variant/30">

            {{-- Carte Leaflet --}}
            <div id="map" class="absolute inset-0 z-0"></div>

            {{-- Message attente position --}}
            <div id="map-waiting-msg"
                 class="absolute inset-0 z-10 flex items-center justify-center bg-surface-container-high/90 backdrop-blur-sm
                        {{ $livraison?->position ? 'hidden' : '' }}">
                <div class="text-center p-6">
                    <span class="material-symbols-outlined text-4xl text-secondary mb-3 block animate-pulse">location_searching</span>
                    <p class="font-bold text-sm text-on-surface">En attente de la position du livreur...</p>
                </div>
            </div>

            {{-- Badge statut --}}
            <div class="absolute top-3 left-3 lg:top-4 lg:left-4 z-10 flex gap-2">
                <div class="bg-surface-container-lowest/90 backdrop-blur px-3 py-1.5 lg:px-4 lg:py-2 rounded-full flex items-center gap-2 shadow-lg">
                    <span id="statut-dot" class="w-2 h-2 lg:w-3 lg:h-3 rounded-full {{ $statutActuel === 'EN_ROUTE' ? 'bg-status-success animate-pulse' : 'bg-outline-variant' }}"></span>
                    <span id="statut-label-map" class="text-[10px] lg:text-sm font-bold">
                        {{ $labels[$statutActuel]['titre'] }}
                    </span>
                </div>
            </div>

            {{-- Contrôles carte --}}
            <div class="absolute bottom-4 right-4 lg:bottom-6 lg:right-6 z-10 flex flex-col gap-2">
                <button id="btn-recenter" class="bg-surface-container-lowest p-2 lg:p-3 rounded-xl shadow-lg hover:bg-surface transition-all active:scale-95">
                    <span class="material-symbols-outlined text-sm lg:text-base">my_location</span>
                </button>
            </div>

            {{-- Carte livreur flottante --}}
            <div id="livreur-card"
                 class="absolute bottom-4 left-4 lg:bottom-6 lg:left-6 z-10 bg-surface-container-lowest p-3 lg:p-4 rounded-2xl
                        shadow-2xl border border-outline-variant/30 flex items-center gap-3 lg:gap-4 max-w-[calc(100%-4rem)] sm:max-w-sm
                        {{ !$livraison?->livreur_id ? 'hidden' : '' }}">
                <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary shrink-0">
                    <span class="material-symbols-outlined">person</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-body-md-bold text-sm lg:text-base truncate" id="livreur-nom">
                        {{ $livraison?->livreur?->nom ?? '—' }}
                        {{ $livraison?->livreur?->telephone ?? '—' }}
                    </p>
                    <p class="text-[10px] lg:text-sm text-on-surface-variant truncate" id="position-fraicheur"></p>
                </div>
                <a id="call-livreur-btn"
                   href="tel:{{ $livraison?->livreur?->telephone }}"
                   class="bg-primary text-on-primary w-8 h-8 lg:w-10 lg:h-10 rounded-full flex items-center justify-center hover:opacity-90 transition-opacity flex-shrink-0
                          {{ !$livraison?->livreur?->telephone ? 'pointer-events-none opacity-40' : '' }}">
                    <span class="material-symbols-outlined text-sm lg:text-base">call</span>
                </a>
            </div>
        </section>

        {{-- Chat (interface conservée, non connectée à un backend pour l'instant) --}}
        <section class="bg-surface-container-lowest rounded-xl flex flex-col h-[300px] lg:h-[350px] shadow-sm border border-outline-variant/30 overflow-hidden">
            <div class="bg-surface px-4 lg:px-card-padding py-2 lg:py-3 border-b border-outline-variant flex items-center justify-between">
                <div class="flex gap-2 lg:gap-4 overflow-x-auto hide-scrollbar">
                    <button class="whitespace-nowrap px-3 py-1.5 rounded-full bg-primary-container text-on-primary-container text-[10px] lg:text-sm font-bold">
                        Livreur {{ $livraison?->livreur?->nom ? '(' . $livraison->livreur->nom . ')' : '' }}
                    </button>
                    <button class="whitespace-nowrap px-3 py-1.5 rounded-full text-on-surface-variant hover:bg-surface-container-high text-[10px] lg:text-sm font-bold transition-colors">
                        Client ({{ $commande->client?->nom ?? '—' }})
                    </button>
                </div>
            </div>
            <div class="flex-1 overflow-y-auto p-4 lg:p-card-padding space-y-4 hide-scrollbar flex items-center justify-center">
                <p class="text-xs text-on-surface-variant text-center">
                    La messagerie sera bientôt disponible.<br>Utilisez l'appel direct en attendant.
                </p>
            </div>
        </section>
    </div>

    {{-- ── Résumé + Timeline ────────────────────────────────── --}}
    <div class="col-span-12 lg:col-span-4 order-2 lg:order-1 flex flex-col gap-4 lg:gap-gutter">

        {{-- Résumé commande --}}
        <section class="bg-surface-container-lowest rounded-xl p-4 lg:p-card-padding shadow-sm border border-outline-variant/30">
            <div class="flex justify-between items-start mb-4 lg:mb-6">
                <div class="min-w-0">
                    <p class="text-[10px] lg:text-label-caps text-outline uppercase">Client</p>
                    <h3 class="font-headline-md text-base lg:text-headline-md truncate">{{ $commande->client?->nom ?? '—' }}</h3>
                    <p class="text-xs text-on-surface-variant mt-0.5"> 
                        <span class="material-symbols-outlined text-[25px] lg:text-sm text-on-primary" style="font-variation-settings: 'FILL' 1;">phone</span>
                         {{ $commande->client?->telephone }}</p>
                    <p class="text-xs text-on-surface-variant mt-0.5">{{ $commande->client?->adresse }}</p>
                    <p class="text-xs text-on-surface-variant mt-0.5">#{{ $commande->reference }}</p>
                </div>
                <span class="material-symbols-outlined text-primary bg-primary-fixed/20 p-2 rounded-lg flex-shrink-0">receipt_long</span>
            </div>
            <div class="space-y-3 lg:space-y-4">
                @foreach($articles as $article)
                <div class="flex justify-between items-center py-2 border-b border-outline-variant/20">
                    <span class="text-sm text-on-surface-variant">{{ $article->produitFournisseur?->produit?->nom ?? '—' }}</span>
                    <span class="text-sm font-body-md-bold">×{{ $article->quantite }}</span>
                </div>
                @endforeach
                <div class="flex justify-between items-center pt-2 lg:pt-4">
                    <span class="font-body-md-bold lg:text-lg">Total</span>
                    <span class="text-lg lg:text-headline-md text-primary font-bold">
                        {{ number_format($commande->montant_ttc, 0, ',', ' ') }} FCFA
                    </span>
                </div>
            </div>
        </section>

        {{-- Timeline --}}
        <section class="bg-surface-container-lowest rounded-xl p-4 lg:p-card-padding shadow-sm border border-outline-variant/30">
            <h4 class="font-headline-md text-base lg:text-headline-md mb-4 lg:mb-6">Suivi d'activité</h4>
            <div class="relative space-y-6 lg:space-y-8" id="timeline-container">
                <div class="absolute left-[11px] top-2 bottom-2 w-0.5 bg-outline-variant"></div>

                @foreach($etapes as $i => $etape)
                <div class="relative flex gap-3 lg:gap-4 timeline-step {{ $i > $indexActuel ? 'opacity-40' : '' }}"
                     data-step="{{ $i }}">
                    <div class="timeline-circle z-10 w-6 h-6 rounded-full flex items-center justify-center
                                {{ $i < $indexActuel ? 'bg-primary text-on-primary' : ($i === $indexActuel ? 'bg-secondary-container border-4 border-surface-container-lowest' : 'bg-outline-variant') }}">
                        @if($i < $indexActuel)
                            <span class="material-symbols-outlined text-[10px] lg:text-sm" style="font-variation-settings: 'FILL' 1;">check</span>
                        @elseif($i === $indexActuel && $etape !== 'LIVREE')
                            <div class="w-2 h-2 rounded-full bg-on-secondary-container {{ $etape === 'EN_ROUTE' ? 'animate-pulse' : '' }}"></div>
                        @elseif($i === $indexActuel && $etape === 'LIVREE')
                            <span class="material-symbols-outlined text-[10px] lg:text-sm text-on-primary" style="font-variation-settings: 'FILL' 1;">check</span>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <p class="timeline-titre text-sm {{ $i <= $indexActuel ? 'font-body-md-bold text-on-surface' : '' }}">
                            {{ $labels[$etape]['titre'] }}
                        </p>
                        <p class="timeline-date text-[10px] lg:text-sm text-on-surface-variant">
                            @if($etape === 'AFFECTEE' && $livraison?->date_affectation)
                             {{ \Carbon\Carbon::parse( $livraison->date_affectation)->format('d/m H:i') }}
                            @elseif($etape === 'EN_ROUTE' && $livraison?->date_depart)
                                {{ \Carbon\Carbon::parse($livraison->date_depart)->format('d/m H:i') }}
                            @elseif($etape === 'RECUPEREE' && $livraison?->date_recuperation)
                                {{ \Carbon\Carbon::parse($livraison->date_recuperation)->format('d/m H:i') }}
                            @elseif($etape === 'LIVREE' && $livraison?->date_arrivee)
                              {{ \Carbon\Carbon::parse($livraison->date_arrivee)->format('d/m H:i') }}
                            @else
                                {{ $labels[$etape]['desc'] }}
                            @endif
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
    </div>
</main>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const pollingUrl = '{{ route("Suivi-Position-Commande", $commande->id) }}';
    const etapeIndex = { 'EN_ATTENTE': 0, 'AFFECTEE': 1, 'RECUPEREE': 2, 'EN_ROUTE': 3, 'LIVREE': 4 };
    const labels = {
        'EN_ATTENTE': 'Commande reçue',
        'AFFECTEE':   'Livreur affecté',
        'RECUPEREE':   'Commande récupérée',
        'EN_ROUTE':   'Livreur en route',
        'LIVREE':     'Livrée',
    };

    let dernierStatut = '{{ $statutActuel }}';
    let map = null;
    let livreurMarker = null;
    let pollingInterval = null;

    const livreurIcon = L.divIcon({
        className: '',
        html: `<div style="
            width:38px;height:38px;border-radius:50%;
            background:#047857;color:#fff;
            display:flex;align-items:center;justify-content:center;
            border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,0.3);
            font-family:'Material Symbols Outlined';font-size:20px;
        ">local_shipping</div>`,
        iconSize: [38, 38],
        iconAnchor: [19, 19],
    });

    // Icône point de collecte (fournisseur)
    const fournisseurIcon = L.divIcon({
        className: '',
        html: `<div style="
            width:34px;height:34px;border-radius:50%;
            background:#d97706;color:#fff;
            display:flex;align-items:center;justify-content:center;
            border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,0.3);
            font-family:'Material Symbols Outlined';font-size:18px;
        ">storefront</div>`,
        iconSize: [34, 34],
        iconAnchor: [17, 17],
    });

    // Icône destination (client)
    const clientIcon = L.divIcon({
        className: '',
        html: `<div style="
            width:34px;height:34px;border-radius:50%;
            background:#dc2626;color:#fff;
            display:flex;align-items:center;justify-content:center;
            border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,0.3);
            font-family:'Material Symbols Outlined';font-size:18px;
        ">home</div>`,
        iconSize: [34, 34],
        iconAnchor: [17, 17],
    });

    // Points fixes (fournisseur + client), transmis depuis Blade
    const fournisseurPos = @json(
        $fournisseur->latitude && $fournisseur->longitude
            ? ['lat' => (float) $fournisseur->latitude, 'lng' => (float) $fournisseur->longitude]
            : null
    );
    const clientPos = @json(
        $commande->latitude && $commande->longitude
            ? ['lat' => (float) $commande->latitude, 'lng' => (float) $commande->longitude]
            : null
    );

    let bounds = [];

    function initMap(centerLat, centerLng) {
        if (map) return;
        map = L.map('map').setView([centerLat, centerLng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap',
            maxZoom: 19,
        }).addTo(map);

        // Marqueur fournisseur (point de collecte, fixe)
        if (fournisseurPos) {
            L.marker([fournisseurPos.lat, fournisseurPos.lng], { icon: fournisseurIcon })
                .addTo(map)
                .bindPopup('🏪 Point de collecte (vous)');
            bounds.push([fournisseurPos.lat, fournisseurPos.lng]);
        }

        // Marqueur client (destination, fixe)
        if (clientPos) {
            L.marker([clientPos.lat, clientPos.lng], { icon: clientIcon })
                .addTo(map)
                .bindPopup('🏠 Destination — ' + @json($commande->client?->nom ?? 'Client'));
            bounds.push([clientPos.lat, clientPos.lng]);
        }

        document.getElementById('map-waiting-msg').classList.add('hidden');
    }

    function mettreAJourPosition(position) {
        if (!position) return;
        const { lat, lng, capturee_at } = position;

        if (!map) {
            initMap(lat, lng);
        }

        if (!livreurMarker) {
            livreurMarker = L.marker([lat, lng], { icon: livreurIcon }).addTo(map).bindPopup('🚚 Livreur');
        } else {
            livreurMarker.setLatLng([lat, lng]);
        }

        // Ajuste la vue pour englober fournisseur + client + livreur (une seule fois au premier positionnement)
        if (!livreurMarker._boundsAjustees) {
            const allBounds = [...bounds, [lat, lng]];
            if (allBounds.length > 1) {
                map.fitBounds(allBounds, { padding: [50, 50] });
            } else {
                map.setView([lat, lng], 14);
            }
            livreurMarker._boundsAjustees = true;
        } else {
            map.panTo([lat, lng]);
        }

        const fraicheur = document.getElementById('position-fraicheur');
        if (fraicheur) fraicheur.textContent = 'Mis à jour ' + capturee_at;
    }

    function mettreAJourTimeline(statut, dates) {
        const idx = etapeIndex[statut] ?? 0;

        document.querySelectorAll('.timeline-step').forEach(function (step) {
            const stepIdx = parseInt(step.dataset.step);
            const circle = step.querySelector('.timeline-circle');
            const titre = step.querySelector('.timeline-titre');

            step.classList.toggle('opacity-40', stepIdx > idx);

            if (stepIdx < idx) {
                circle.className = 'timeline-circle z-10 w-6 h-6 rounded-full flex items-center justify-center bg-primary text-on-primary';
                circle.innerHTML = '<span class="material-symbols-outlined text-[10px] lg:text-sm" style="font-variation-settings: \'FILL\' 1;">check</span>';
                titre.classList.add('font-body-md-bold', 'text-on-surface');
            } else if (stepIdx === idx) {
                circle.className = 'timeline-circle z-10 w-6 h-6 rounded-full flex items-center justify-center bg-secondary-container border-4 border-surface-container-lowest';
                circle.innerHTML = '<div class="w-2 h-2 rounded-full bg-on-secondary-container animate-pulse"></div>';
                titre.classList.add('font-body-md-bold', 'text-on-surface');
            }
        });

        // Met à jour le badge sur la carte
        document.getElementById('statut-label-map').textContent = labels[statut] ?? statut;
        const dot = document.getElementById('statut-dot');
        dot.className = 'w-2 h-2 lg:w-3 lg:h-3 rounded-full ' + (statut === 'EN_ROUTE' ? 'bg-status-success animate-pulse' : 'bg-outline-variant');
    }

    function mettreAJourLivreur(livreur) {
        if (!livreur) return;
        document.getElementById('livreur-nom').textContent = livreur.nom ?? '—';
        const callBtn = document.getElementById('call-livreur-btn');
        if (livreur.telephone) {
            callBtn.href = 'tel:' + livreur.telephone;
            callBtn.classList.remove('pointer-events-none', 'opacity-40');
        }
        document.getElementById('livreur-card').classList.remove('hidden');
    }

    function polling() {
        fetch(pollingUrl, { headers: { 'Accept': 'application/json' } })
            .then(res => res.json())
            .then(data => {
                if (!data.statut) return;

                if (data.statut !== dernierStatut) {
                    dernierStatut = data.statut;
                    mettreAJourTimeline(data.statut, data.dates ?? {});

                    if (data.statut === 'LIVREE') {
                        clearInterval(pollingInterval);
                    }
                }

                if (data.livreur) mettreAJourLivreur(data.livreur);
                if (data.position) mettreAJourPosition(data.position);
            })
            .catch(err => console.error('Erreur polling :', err));
    }

    document.getElementById('btn-recenter').addEventListener('click', function () {
        if (map && livreurMarker) {
            map.setView(livreurMarker.getLatLng(), 15);
        }
    });

    pollingInterval = setInterval(polling, 6000);

    // Initialise la carte dès le chargement si on a au moins un point fixe
    // (fournisseur et/ou client), même si le livreur n'a pas encore de position
    @if(($fournisseur->latitude && $fournisseur->longitude) || ($commande->latitude && $commande->longitude))
        @php
            $centerLat = $fournisseur->latitude ?? $commande->latitude;
            $centerLng = $fournisseur->longitude ?? $commande->longitude;
        @endphp
        initMap({{ $centerLat }}, {{ $centerLng }});
        if (bounds.length > 1) {
            map.fitBounds(bounds, { padding: [50, 50] });
        }
    @endif

    // Si la position du livreur est déjà connue au chargement, on la place aussi
    @if($livraison?->position)
    mettreAJourPosition({
        lat: {{ $livraison->position->latitude }},
        lng: {{ $livraison->position->longitude }},
        capturee_at: '{{ $livraison->position->capturee_at->diffForHumans() }}'
    });
    @endif
});
</script>

@endsection
