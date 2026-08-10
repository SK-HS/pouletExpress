@extends('layouts.livreur.main')
@section('content')

{{-- ─── CSS ──────────── --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />

<style>
    /* Cache le panneau de texte verbose de Leaflet Routing Machine */
    .leaflet-routing-container { display: none !important; }
</style>

<main class="md:ml-64 flex flex-col h-[calc(100vh-64px)] overflow-hidden bg-slate-50 dark:bg-slate-900">

    {{-- ─── SECTION HAUT : CARTE ──────────── --}}
    <div class="relative w-full shrink-0 border-b border-slate-200 dark:border-slate-700 shadow-sm"
         style="height: 45vh; min-height: 300px;">

        <div id="map" style="width:100%; height:100%; z-index:0;"></div>

        {{-- Boutons superposés --}}
        <div class="absolute top-3 left-3 right-3 z-10 flex justify-between pointer-events-none">
            <button onclick="recenterMap()"
                    class="pointer-events-auto px-3.5 py-2.5 rounded-xl bg-white/90 dark:bg-slate-800/90
                           backdrop-blur text-slate-700 dark:text-slate-200 font-bold text-xs
                           flex items-center gap-2 shadow-md hover:bg-white transition">
                <span class="material-symbols-outlined text-base">my_location</span>
                <span class="hidden sm:inline">Recentrer</span>
            </button>

            <span class="pointer-events-auto px-3 py-1.5 rounded-full bg-emerald-600 text-white
                         shadow-md text-xs font-extrabold flex items-center gap-1">
                <span class="w-2 h-2 bg-white rounded-full animate-ping mr-1"></span>
                {{ $commandes->count() }} MISSIONS
            </span>
        </div>

        {{-- Message si GPS en attente --}}
        <div id="gps-loading"
             class="absolute inset-0 flex items-center justify-center bg-slate-100/80 z-10 backdrop-blur-sm">
            <div class="text-center">
                <span class="material-symbols-outlined text-4xl text-emerald-600 animate-pulse block mb-2">
                    location_searching
                </span>
                <p class="text-sm font-bold text-slate-700">Localisation en cours...</p>
            </div>
        </div>
    </div>

    {{-- ─── SECTION BAS : LISTE MISSIONS ─── --}}
    <div class="flex-1 overflow-y-auto p-4 space-y-4">

        <h2 class="text-xs font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-widest px-1">
            Missions en cours
        </h2>

        @forelse($commandes as $commande)
        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700
                    space-y-4 transition-transform active:scale-[0.98]">

            <div class="flex items-start justify-between">
                <h3 class="font-extrabold text-base text-slate-900 dark:text-white">
                    Course #{{ $commande->reference }}
                </h3>
                {{-- On encode les coordonnées en data-* pour éviter tout bug Blade/JS --}}
                <button
                    class="btn-focus-map text-emerald-700 dark:text-emerald-400 font-bold text-xs
                           flex items-center gap-1 bg-emerald-50 dark:bg-emerald-900/30
                           px-2.5 py-1.5 rounded-lg hover:bg-emerald-100 transition-colors"
                    data-lat="{{ $commande->latitude ?? '' }}"
                    data-lng="{{ $commande->longitude ?? '' }}"
                    data-ref="{{ $commande->reference }}">
                    <span class="material-symbols-outlined text-sm">map</span>
                    Sur la carte
                </button>
            </div>

            {{-- Infos commande --}}
            <div class="space-y-3 text-xs border-t border-b border-slate-100 dark:border-slate-700/60 py-3">

                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-2.5">
                        <span class="material-symbols-outlined text-emerald-700 text-base">storefront</span>
                        <div>
                            <strong class="text-slate-900 dark:text-white block font-bold">
                                {{ $commande->fournisseur?->nom_ferme ?? 'Fournisseur non renseigné' }}
                            </strong>
                            <span class="text-slate-500">Collecte à effectuer</span>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <strong class="text-blue-600 dark:text-blue-400 block font-bold">
                            {{ ucfirst($commande->creneau ?? '--') }}
                        </strong>
                        <span class="text-slate-500 text-[10px] uppercase tracking-wider">Créneau</span>
                    </div>
                </div>

                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-2.5">
                        <span class="material-symbols-outlined text-orange-600 text-base">person_pin</span>
                        <div>
                            <strong class="text-slate-900 dark:text-white block font-bold">
                                {{ $commande->client?->nom ?? 'Client inconnu' }}
                            </strong>
                            <span class="text-slate-500">
                                {{ $commande->quartier?->nom_quartier ?? '—' }}
                                — {{ $commande->telephone_livraison }}
                            </span>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <strong class="text-emerald-700 dark:text-emerald-400 block font-extrabold">
                            {{ number_format($commande->montant_ttc ?? 0, 0, ',', ' ') }} FCFA
                        </strong>
                        <span class="text-slate-500 text-[10px] uppercase tracking-wider">À encaisser</span>
                    </div>
                </div>

                {{-- Indicateur GPS --}}
                @if($commande->latitude && $commande->longitude)
                <div class="flex items-center gap-1.5 text-emerald-600 font-medium">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Position GPS disponible
                </div>
                @else
                <div class="flex items-center gap-1.5 text-amber-600 font-medium">
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                    Pas de position GPS — livraison par quartier uniquement
                </div>
                @endif
            </div>

            {{-- Boutons action --}}
            <div class="grid grid-cols-1 gap-3 pt-1">
             @if($commande->commande_recuperee == 0)
                    <!-- ÉTAPE 1 : Le livreur est chez le fournisseur -->
                    <form method="POST" action="{{ route('Livraison-Demarrer', $commande->id) }}">
                        @csrf
                        @method('PUT')
                        <button type="submit"
                                class="w-full py-3 rounded-xl bg-orange-600 hover:bg-orange-700 text-white
                                    font-bold text-xs flex items-center justify-center gap-1.5 shadow-sm transition">
                            <span class="material-symbols-outlined text-sm">shopping_bag</span>
                            Confirmer la récupération
                        </button>
                    </form>

                @elseif($commande->commande_recuperee == 1 && $commande->commande_en_route == 0)
                    <!-- ÉTAPE 2 : Le livreur a le colis et s'apprête à rouler -->
                    <form method="POST" action="{{ route('Livraison-En-Route', $commande->id) }}">
                        @csrf
                        @method('PUT')
                        <button type="submit"
                                class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white
                                    font-bold text-xs flex items-center justify-center gap-1.5 shadow-sm transition">
                            <span class="material-symbols-outlined text-sm">two_wheeler</span> <!-- Icône de moto -->
                            En route vers le client
                        </button>
                    </form>

                @elseif($commande->commande_recuperee == 1 && $commande->commande_en_route == 1 && $commande->commande_livree == 0)
                    <!-- ÉTAPE 3 : Le livreur est devant chez le client, il valide la fin -->
                    <!-- (J'ai remplacé votre simple bouton bleu par le formulaire manquant vers 'Livraison-Terminer') -->
                    <form method="POST" action="{{ route('Livraison-Terminer', $commande->id) }}">
                        @csrf
                        @method('PUT')
                        <button type="submit"
                                class="w-full py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white
                                    font-bold text-xs flex items-center justify-center gap-1.5 shadow-sm transition">
                            <span class="material-symbols-outlined text-sm">task_alt</span>
                            Terminer la livraison
                        </button>
                    </form>

                @elseif($commande->commande_livree == 1)
                    <!-- ÉTAPE 4 : Course terminée (Bouton inactif/Affichage) -->
                    <button type="button" disabled
                            class="w-full py-3 rounded-xl bg-slate-200 text-slate-500 cursor-not-allowed
                                font-bold text-xs flex items-center justify-center gap-1.5 shadow-sm transition">
                        <span class="material-symbols-outlined text-sm">check_circle</span>
                        Course achevée
                    </button>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-16 text-slate-400">
            <span class="material-symbols-outlined text-5xl mb-3 block">inventory_2</span>
            <p class="font-bold text-sm">Aucune mission en cours.</p>
        </div>
        @endforelse
   <div class="h-20 md:hidden w-full"></div>
    </div>
    {{-- Remplacement sans closure, compatible Blade --}}
@php
    $destinations = [];
    foreach ($commandes as $c) {
        $destinations[] = [
            'ref'      => $c->reference,
            'lat'      => $c->latitude  ? (float) $c->latitude  : null,
            'lng'      => $c->longitude ? (float) $c->longitude : null,
            'client'   => optional($c->client)->nom ?? 'Client',
            'quartier' => optional($c->quartier)->nom_quartier ?? '',
        ];
    }
@endphp

{{-- Plus loin dans le JS --}}

</main>

{{-- ─── JS : Leaflet d'abord, puis Routing Machine --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    // ── Initialisation carte (centre Abidjan par défaut) 
    const map = L.map('map', { zoomControl: false }).setView([5.30966, -4.01266], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap',
        maxZoom: 19,
    }).addTo(map);

    // Contrôle de zoom en bas à droite (évite le chevauchement avec nos boutons)
    L.control.zoom({ position: 'bottomright' }).addTo(map);

    // ── Variables globales
    let livreurMarker = null;
    let livreurPosition = null;
    let routingControl = null;

    // Icône livreur (vert)
    const livreurIcon = L.divIcon({
        className: '',
        html: `<div style="
            width:42px;height:42px;border-radius:50%;
            background:#047857;color:#fff;
            display:flex;align-items:center;justify-content:center;
            border:3px solid #fff;box-shadow:0 2px 10px rgba(0,0,0,0.35);
            font-family:'Material Symbols Outlined';font-size:22px;
        ">local_shipping</div>`,
        iconSize: [42, 42],
        iconAnchor: [21, 21],
    });

    // Icône destination (rouge)
    const destinationIcon = L.divIcon({
        className: '',
        html: `<div style="
            width:38px;height:38px;border-radius:50%;
            background:#dc2626;color:#fff;
            display:flex;align-items:center;justify-content:center;
            border:3px solid #fff;box-shadow:0 2px 10px rgba(0,0,0,0.35);
            font-family:'Material Symbols Outlined';font-size:20px;
        ">home</div>`,
        iconSize: [38, 38],
        iconAnchor: [19, 19],
    });

    // ── Géolocalisation du livreur au chargement 
    function initLivreurPosition() {
        if (!navigator.geolocation) {
            document.getElementById('gps-loading').innerHTML =
                '<p class="text-sm font-bold text-red-600 p-4">GPS non supporté par ce navigateur.</p>';
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function (pos) {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                livreurPosition = { lat, lng };

                // Cache le message de chargement
                document.getElementById('gps-loading').style.display = 'none';

                // Place le marqueur du livreur
                livreurMarker = L.marker([lat, lng], { icon: livreurIcon })
                    .addTo(map)
                    .bindPopup('📦 Votre position')
                    .openPopup();

                map.setView([lat, lng], 14);

                // Ajoute les marqueurs de toutes les destinations sur la carte
                afficherToutesDestinations();

                // Continue de suivre la position (mis à jour toutes les 15 sec)
                navigator.geolocation.watchPosition(function (pos) {
                    const newLat = pos.coords.latitude;
                    const newLng = pos.coords.longitude;
                    livreurPosition = { lat: newLat, lng: newLng };
                    if (livreurMarker) livreurMarker.setLatLng([newLat, newLng]);
                }, null, { enableHighAccuracy: true, maximumAge: 15000 });
            },
            function (err) {
                document.getElementById('gps-loading').style.display = 'none';
                let msg = 'Impossible de vous localiser.';
                if (err.code === 1) msg = 'Permission GPS refusée. Autorisez la localisation dans votre navigateur.';
                if (err.code === 3) msg = 'GPS trop lent. Réessayez en extérieur.';

                // Affiche une alerte non bloquante sur la carte
                const alertDiv = document.createElement('div');
                alertDiv.className = 'absolute bottom-3 left-3 right-3 z-10 bg-amber-50 border border-amber-300 text-amber-700 text-xs font-bold p-3 rounded-xl shadow';
                alertDiv.textContent = '⚠️ ' + msg;
                document.querySelector('.relative.w-full.shrink-0').appendChild(alertDiv);

                // Affiche quand même les destinations sans route
                afficherToutesDestinations();
            },
            { enableHighAccuracy: true, timeout: 12000 }
        );
    }

    // ── Marqueurs de toutes les destinations 

const destinations = @json($destinations);
    // const destinations = @json($destinations);

    function afficherToutesDestinations() {
        const bounds = [];

        destinations.forEach(function (dest) {
            if (!dest.lat || !dest.lng) return;

            L.marker([dest.lat, dest.lng], { icon: destinationIcon })
                .addTo(map)
                .bindPopup(`
                    <div class="text-xs">
                        <strong> ${dest.client}</strong><br>
                        ${dest.quartier}<br>
                        Réf: ${dest.ref}
                    </div>
                `);

            bounds.push([dest.lat, dest.lng]);
        });

        // Si plusieurs destinations, ajuste le zoom pour tout voir
        if (bounds.length > 1) {
            map.fitBounds(bounds, { padding: [40, 40] });
        } else if (bounds.length === 1 && !livreurPosition) {
            map.setView(bounds[0], 15);
        }
    }

    // ── Tracer la route vers une destination précise ─────────────
    window.focusOnCommande = function (destLat, destLng, ref) {
        // Cas : pas de coordonnées GPS pour cette commande
        if (!destLat || !destLng) {
            showToast('⚠️ Pas de position GPS pour cette commande. Utilisez le quartier comme repère.', 'warning');
            return;
        }

        if (!livreurPosition) {
            showToast('⚠️ Votre position GPS n\'est pas encore disponible. Patientez...', 'warning');
            return;
        }

        // Supprime l'ancienne route si elle existe
        if (routingControl !== null) {
            map.removeControl(routingControl);
            routingControl = null;
        }

        // Trace la route
        routingControl = L.Routing.control({
            waypoints: [
                L.latLng(livreurPosition.lat, livreurPosition.lng),
                L.latLng(destLat, destLng),
            ],
            language: 'fr',
            routeWhileDragging: false,
            showAlternatives: false,
            createMarker: function () { return null; }, // On garde nos propres marqueurs
            lineOptions: {
                styles: [{ color: '#047857', opacity: 0.95, weight: 6 }],
            },
        }).addTo(map);

        // Centre la vue entre livreur et destination
        map.fitBounds([
            [livreurPosition.lat, livreurPosition.lng],
            [destLat, destLng],
        ], { padding: [60, 60] });

        showToast('🗺️ Itinéraire tracé vers la commande ' + ref, 'success');
    };

    // ── Recentrer sur la position du livreur 
    window.recenterMap = function () {
        if (livreurPosition) {
            map.setView([livreurPosition.lat, livreurPosition.lng], 15);
        } else {
            showToast('⚠️ Position GPS non disponible.', 'warning');
        }
    };

    // ── Attachement des boutons "Sur la carte"
    document.querySelectorAll('.btn-focus-map').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const lat = parseFloat(this.dataset.lat) || null;
            const lng = parseFloat(this.dataset.lng) || null;
            const ref = this.dataset.ref;
            focusOnCommande(lat, lng, ref);
        });
    });

    // ── Toast (notification non bloquante)
    function showToast(message, type) {
        const existing = document.getElementById('map-toast');
        if (existing) existing.remove();

        const colors = {
            success: 'bg-emerald-600 text-white',
            warning: 'bg-amber-500 text-white',
            error: 'bg-red-600 text-white',
        };

        const toast = document.createElement('div');
        toast.id = 'map-toast';
        toast.className = 'fixed bottom-6 left-1/2 -translate-x-1/2 z-50 px-5 py-3 rounded-2xl shadow-xl text-sm font-bold transition-all ' + (colors[type] || colors.success);
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => toast.remove(), 3500);
    }


    initLivreurPosition();
});




</script>




@endsection