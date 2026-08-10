@extends('layouts.livreur.main')
@section('content')
@php
    $livreur = Auth::guard('livreur')->user();
    // Statistiques du jour
    $livraisonsAujourdhui = $livraisonsJour ?? 0;
    $distanceJour         = $distanceJour ?? 0;
    $gainJour             = $gainJour ?? 0;
    $note                 = $note ?? '4.95';
    // Commandes
    $nbEnCours = isset($commandesEnCours) ? count($commandesEnCours) : 0;
    $coursePrioritaire = isset($commandesEnCours) ? $commandesEnCours->first() : null;
    $nbDispos  = count($commandesDisponibles ?? []) ?? 0;
@endphp
<!-- ==================== MAIN CONTENT AREA ==================== -->
{{-- AJOUT DE w-full md:w-[calc...] et overflow-x-hidden pour sécuriser le mobile --}}
<main class="md:ml-64 w-full md:w-[calc(100%-16rem)] overflow-x-hidden pb-20 md:pb-8 p-4 md:p-8 mx-auto space-y-6 animate-in fade-in duration-500">
    
    <!-- Welcome Hero Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-900 via-emerald-800 to-emerald-950 text-white p-6 sm:p-8 md:p-12 shadow-xl flex flex-col items-center text-center">
        <div class="relative z-10 max-w-2xl space-y-4 flex flex-col items-center w-full">
            
            {{-- Flex-wrap ajouté ici si le texte est très long --}}
            <div class="inline-flex flex-wrap items-center justify-center text-center gap-2 px-4 py-2 rounded-2xl sm:rounded-full bg-emerald-700/60 text-emerald-200 font-semibold text-xs backdrop-blur-sm border border-emerald-500/30">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping shrink-0"></span>
                <span id="disponibilite-label">
                    {{ $nbDispos > 0 ? $nbDispos . ' nouvelle(s) commande(s) disponible(s)' : 'Itinéraire Optimisé - Abidjan & Environs' }}
                </span>
            </div>
            
            <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight">Bonjour {{ $livreur?->nom ?? 'Livreur' }} ! Ready ?</h2>
            
            <p class="text-emerald-100/90 text-sm md:text-base leading-relaxed max-w-lg">
                @if($nbEnCours > 0)
                    Vous avez <strong class="text-amber-400 text-lg">{{ $nbEnCours }} course(s) en cours</strong>. Gardez le cap !
                @else
                    Vous n'avez pas de course en cours pour le moment. Vérifiez les commandes disponibles.
                @endif
            </p>
            
            <!-- Boutons d'action centrés -->
            <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4 w-full">
                <!-- Le fameux Bouton GPS Dynamique -->
                <button id="btn-start-gps" class="w-full sm:w-auto px-6 py-3.5 rounded-xl bg-orange-600 hover:bg-orange-500 text-white font-bold text-sm shadow-lg shadow-orange-900/30 transition-all active:scale-95 flex items-center justify-center gap-2 border border-orange-500/50">
                    <span class="material-symbols-outlined animate-pulse shrink-0" id="gps-icon">satellite_alt</span>
                    <span id="gps-text" class="whitespace-nowrap">Démarrer le Guidage GPS</span>
                </button>
                
                <a href="{{ route('Commande-Livreur') }}" class="w-full sm:w-auto px-6 py-3.5 rounded-xl bg-emerald-800/80 hover:bg-emerald-700 text-emerald-100 font-bold text-sm border border-emerald-600/40 shadow-lg transition-all active:scale-95 flex items-center justify-center gap-2">
                    <span class="whitespace-nowrap">Voir mes commandes</span>
                    <span class="material-symbols-outlined text-lg shrink-0">arrow_forward</span>
                </a>
            </div>
        </div>
        <span class="material-symbols-outlined text-[150px] sm:text-[200px] text-white/5 absolute -right-6 -bottom-10 pointer-events-none select-none transform -rotate-12">local_shipping</span>
        <span class="material-symbols-outlined text-[100px] sm:text-[150px] text-white/5 absolute -left-10 -top-10 pointer-events-none select-none transform rotate-12">map</span>
    </div>
    <!-- Key Performance Metrics (Bento Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col justify-between hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Gains du jour</span>
                <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined">payments</span>
                </div>
            </div>
            <div class="mt-4">
                <h3 class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ number_format($gainJour, 0, ',', ' ') }} <span class="text-sm font-semibold text-slate-500">FCFA</span></h3>
                <p class="text-xs font-semibold text-emerald-600 mt-1 flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">trending_up</span> En hausse
                </p>
            </div>
        </div>
        <div class="p-5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col justify-between hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Livraisons (Jour)</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined">task_alt</span>
                </div>
            </div>
            <div class="mt-4">
                <h3 class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ $livraisonsAujourdhui }} <span class="text-sm font-semibold text-slate-500">colis</span></h3>
                <p class="text-xs font-medium text-slate-500 mt-1">Excellent travail !</p>
            </div>
        </div>
        <div class="p-5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col justify-between hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Distance</span>
                <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined">speed</span>
                </div>
            </div>
            <div class="mt-4">
                <h3 class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ number_format($distanceJour, 1) }} <span class="text-sm font-semibold text-slate-500">km</span></h3>
                <p class="text-xs font-medium text-slate-500 mt-1">Secteur Abidjan & Environs</p>
            </div>
        </div>
        <div class="p-5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col justify-between hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Satisfaction</span>
                <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined">star</span>
                </div>
            </div>
            <div class="mt-4">
                <h3 class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ $note }} <span class="text-sm font-semibold text-slate-500">/ 5</span></h3>
                <p class="text-xs font-semibold text-amber-500 mt-1">Note très positive</p>
            </div>
        </div>
    </div>
    {{-- ── NOUVELLES COMMANDES DISPONIBLES (POLLING) ─────── --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 sm:p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100 dark:border-slate-700">
            <h3 class="font-extrabold text-lg text-slate-900 dark:text-white">Nouvelles commandes</h3>
            <span class="flex items-center w-fit gap-1.5 text-xs text-emerald-600 font-bold bg-emerald-50 dark:bg-emerald-900/30 px-3 py-1.5 rounded-full">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Actualisation auto
            </span>
        </div>
        <div id="commandesDisponiblesContainer" class="space-y-4 mt-4 w-full">
            @forelse($commandesDisponibles ?? [] as $cmd)
            <div class="commande-dispo-card w-full bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-4 sm:p-5 space-y-4 transition hover:shadow-md" data-id="{{ $cmd['id'] }}">
                
                {{-- Ligne 1 : Les badges s'empilent sur mobile si besoin (flex-col) --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="font-mono text-xs font-bold bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 px-2.5 py-1 rounded-md">#{{ $cmd['reference'] }}</span>
                        <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-700 text-[11px] font-extrabold">NOUVELLE COURSE</span>
                    </div>
                    <span class="text-xs text-slate-400 shrink-0">{{ $cmd['created_at'] ?? 'À l\'instant' }}</span>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-xs">
                    <div>
                        <p class="text-slate-400 font-bold uppercase tracking-wider mb-0.5">Zone</p>
                        <p class="font-bold text-slate-900 dark:text-white"> {{ $cmd['fournisseur_quartier'] }} -> {{ $cmd['quartier'] }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400 font-bold uppercase tracking-wider mb-0.5">Distance</p>
                        <p class="font-bold text-blue-600">{{ $cmd['distance_km'] ?? '—' }} km</p>
                    </div>
                    <div> 
                        <p class="text-slate-400 font-bold uppercase tracking-wider mb-0.5">Articles</p>
                        <p class="font-bold text-slate-900 dark:text-white">{{ $cmd['nb_articles'] ?? '0' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400 font-bold uppercase tracking-wider mb-0.5">Créneau</p>
                        <p class="font-bold text-slate-900 dark:text-white">{{ (isset($cmd['creneau']) && $cmd['creneau'] === 'matin') ? '08h-12h' : '14h-18h' }}</p>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 pt-3 border-t border-slate-200 dark:border-slate-700">
                    <div>
                        <p class="text-xs text-slate-400 font-medium">À encaisser</p>
                        <p class="text-xl font-extrabold text-emerald-700 dark:text-emerald-400">{{ number_format($cmd['total'] ?? 0, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                        <button type="button" class="btn-refuser w-full sm:w-auto px-4 py-2.5 rounded-xl bg-red-50 dark:bg-red-900/20 hover:bg-red-100 text-red-600 font-bold text-sm flex items-center justify-center gap-1.5 transition" data-id="{{ $cmd['id'] }}">
                            <span class="material-symbols-outlined text-base shrink-0">close</span> Refuser
                        </button>
                        <form class="accept-form w-full sm:w-auto" method="POST" action="{{ route('Livreur-Accepter-Commande', $cmd['id']) }}">
                            @csrf
                            <button type="submit" class="w-full px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-sm flex items-center justify-center gap-1.5 shadow-sm transition">
                                <span class="material-symbols-outlined text-base shrink-0">check_circle</span> Accepter
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center w-full py-12 text-slate-400 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-200 dark:border-slate-700" id="noCommandeDispo">
                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="material-symbols-outlined text-3xl">inbox</span>
                </div>
                <p class="font-bold text-sm text-slate-600 dark:text-slate-300">Aucune commande disponible dans votre zone.</p>
                <p class="text-xs mt-1">Vous serez notifié dès qu'une nouvelle commande arrive.</p>
            </div>
            @endforelse
        </div>
    </div>
    <!-- Active Delivery Priority Card (S'affiche s'il y a une course en cours) -->
    @if($coursePrioritaire)
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 sm:p-6 border border-slate-200 dark:border-slate-700 shadow-sm mt-8 w-full">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100 dark:border-slate-700">
            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 text-xs font-extrabold uppercase tracking-wider">Course en cours</span>
                <span class="font-mono text-sm font-bold text-slate-500">#{{ $coursePrioritaire->reference }}</span>
            </div>
            <span class="text-xs font-semibold text-slate-400">À livrer aujourd'hui</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 py-6">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 font-bold flex items-center justify-center shrink-0">1</div>
                <div>
                    <p class="text-xs font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">Point de Collecte</p>
                    <h4 class="font-bold text-slate-900 dark:text-white">{{ $coursePrioritaire->fournisseur?->nom_ferme ?? 'Fournisseur' }}</h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $coursePrioritaire->fournisseur?->telephone }}</p>
                </div>
            </div>
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 font-bold flex items-center justify-center shrink-0">2</div>
                <div>
                    <p class="text-xs font-bold text-blue-700 dark:text-blue-400 uppercase tracking-wider">Créneau</p>
                    <h4 class="font-bold text-slate-900 dark:text-white">{{ $coursePrioritaire->creneau === 'matin' ? 'Matin (08h-12h)' : 'Après-midi (14h-18h)' }}</h4>
                </div>
            </div>
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-orange-100 dark:bg-orange-900/50 text-orange-700 dark:text-orange-300 font-bold flex items-center justify-center shrink-0">3</div>
                <div>
                    <p class="text-xs font-bold text-orange-700 dark:text-orange-400 uppercase tracking-wider">Destinataire Final</p>
                    <h4 class="font-bold text-slate-900 dark:text-white">{{ $coursePrioritaire->client?->nom }}</h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $coursePrioritaire->quartier?->nom_quartier }} - {{ $coursePrioritaire->telephone_livraison }}</p>
                </div>
            </div>
        </div>
        <div class="pt-4 border-t border-slate-100 dark:border-slate-700 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center justify-between w-full sm:w-auto gap-2">
                <span class="text-xs text-slate-400 font-medium">Montant :</span>
                <span class="text-lg font-extrabold text-emerald-700 dark:text-emerald-400">{{ number_format($coursePrioritaire->montant_ttc, 0, ',', ' ') }} FCFA</span>
            </div>
            @if($coursePrioritaire->latitude && $coursePrioritaire->longitude)
                <a href="https://www.google.com/maps?q={{ $coursePrioritaire->latitude }},{{ $coursePrioritaire->longitude }}" target="_blank" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-sm shadow transition flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined shrink-0">navigation</span>
                    <span>Carte de Trajet</span>
                </a>
            @endif
        </div>
    </div>
    @endif
   <div class="h-24 md:hidden w-full"></div>
</main>

{{-- ── Script polling commandes disponibles ──────────────────────────────── --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const container = document.getElementById('commandesDisponiblesContainer');

    if(container) {
        // Accepter via AJAX
    container.addEventListener('submit', function (e) {
    if (!e.target.classList.contains('accept-form')) return;
    e.preventDefault(); // Empêche le rechargement de la page
    
    const form = e.target;
    const card = form.closest('.commande-dispo-card');
    const btnAcc = form.querySelector('button');
    
    btnAcc.disabled = true;
    btnAcc.textContent = 'Traitement...';
    fetch(form.action, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        body: new FormData(form),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Affiche la notification VERTE (Succès)
            afficherToast('success', data.message);
            
            // Fait disparaître la carte en douceur
            card.style.transition = 'opacity 0.4s, transform 0.4s';
            card.style.opacity = '0';
            card.style.transform = 'translateY(-10px)';
            
            setTimeout(() => {
                card.remove();
                verifierListeVide();
                // On redirige vers les commandes au bout de 2.5 secondes pour avoir le temps de lire le message
                setTimeout(() => window.location.href = "{{ route('Livreur-Espace') }}", 2500);
            }, 400);
            
        } else {
            // Affiche la notification ROUGE (Erreur)
            afficherToast('error', data.message);
            btnAcc.disabled = false;
            btnAcc.innerHTML = '<span class="material-symbols-outlined text-base">check_circle</span> Accepter';
        }
    })
    .catch(() => {
        afficherToast('error', 'Erreur de connexion avec le serveur.');
        btnAcc.disabled = false;
        btnAcc.innerHTML = '<span class="material-symbols-outlined text-base">check_circle</span> Accepter';
    });
});

        // Refuser localement
        container.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-refuser');
            if (!btn) return;
            const card = btn.closest('.commande-dispo-card');
            const id = btn.dataset.id;
            card.style.transition = 'opacity 0.3s';
            card.style.opacity = '0';
            setTimeout(() => {
                card.remove();
                verifierListeVide();
            }, 300);
            refuseesLocalement.add(String(id));
        });

        const refuseesLocalement = new Set();
        
        function polling() {
            fetch("{{ route('livreur.polling') }}", { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => {
                if (!data.commandes) return;
                const idsActuels = new Set(Array.from(container.querySelectorAll('.commande-dispo-card')).map(c => c.dataset.id));
                const nouveauxIds = new Set(data.commandes.map(c => String(c.id)));
                
                // Retirer les commandes disparues
                idsActuels.forEach(id => {
                    if (!nouveauxIds.has(id)) {
                        const card = container.querySelector(`.commande-dispo-card[data-id="${id}"]`);
                        if (card) {
                            card.style.transition = 'opacity 0.4s';
                            card.style.opacity = '0';
                            setTimeout(() => { card.remove(); verifierListeVide(); }, 400);
                        }
                    }
                });
                
                // Ajouter les nouvelles
                data.commandes.forEach(cmd => {
                    if (!idsActuels.has(String(cmd.id)) && !refuseesLocalement.has(String(cmd.id))) {
                        location.reload();
                    }
                });
                
                const label = document.getElementById('disponibilite-label');
                if (label) {
                    label.textContent = data.commandes.length > 0
                        ? data.commandes.length + ' nouvelle(s) commande(s) disponible(s)'
                        : 'Itinéraire Optimisé - Abidjan & Environs';
                }
            })
            .catch(() => {});
        }
        
        function verifierListeVide() {
            const cardsRestantes = container.querySelectorAll('.commande-dispo-card');
            if (cardsRestantes.length === 0 && !document.getElementById('noCommandeDispo')) {
                container.innerHTML = `
                    <div class="text-center py-12 text-slate-400 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-200 dark:border-slate-700" id="noCommandeDispo">
                        <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-3">
                            <span class="material-symbols-outlined text-3xl">inbox</span>
                        </div>
                        <p class="font-bold text-sm text-slate-600 dark:text-slate-300">Aucune commande disponible dans votre zone.</p>
                        <p class="text-xs mt-1">Vous serez notifié dès qu'une nouvelle commande arrive.</p>
                    </div>`;
            }
        }
        
        setInterval(polling, 8000);
    }
});
</script>

{{-- ── Script d'actualisation GPS Livreur ──────────────────────────────── --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnStartGps = document.getElementById('btn-start-gps');
    if(!btnStartGps) return;
    
    let watchId = null;

    btnStartGps.addEventListener('click', function () {
        if (!navigator.geolocation) {
            alert('Votre téléphone ne supporte pas le GPS.');
            return;
        }

        // On modifie l'intérieur du bouton (texte + icône animée)
        const gpsText = document.getElementById('gps-text');
        const gpsIcon = document.getElementById('gps-icon');
        
        gpsText.textContent = "Transmission GPS en cours...";
        gpsIcon.textContent = "radar";
        gpsIcon.classList.add("animate-spin");
        gpsIcon.classList.remove("animate-pulse");

        // On remplace proprement la couleur de orange vers emerald (vert) pour montrer que c'est actif
        btnStartGps.classList.remove('bg-orange-600', 'hover:bg-orange-500', 'border-orange-500/50', 'shadow-orange-900/30');
        btnStartGps.classList.add('bg-emerald-600', 'hover:bg-emerald-500', 'border-emerald-500/50', 'shadow-emerald-900/30');

        // watchPosition suit le téléphone en temps réel
        watchId = navigator.geolocation.watchPosition(
            function (position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                fetch('{{ route('Actualiser-Position-gps') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        latitude: lat,
                        longitude: lng
                    })
                })
                .then(response => console.log('Position GPS synchronisée avec succès !'))
                .catch(error => console.error('Erreur réseau', error));
            },
            function (error) {
                console.error("Erreur GPS : ", error);
                alert("Veuillez autoriser votre localisation pour continuer.");
                
                // Remise à zéro du bouton en cas d'erreur
                gpsText.textContent = "Démarrer le Guidage GPS";
                gpsIcon.textContent = "satellite_alt";
                gpsIcon.classList.remove("animate-spin");
                gpsIcon.classList.add("animate-pulse");
                
                btnStartGps.classList.remove('bg-emerald-600', 'hover:bg-emerald-500', 'border-emerald-500/50', 'shadow-emerald-900/30');
                btnStartGps.classList.add('bg-orange-600', 'hover:bg-orange-500', 'border-orange-500/50', 'shadow-orange-900/30');
            },
            {
                enableHighAccuracy: true,
                maximumAge: 0,
                timeout: 10000
            }
        );
    });
});
</script>

@endsection
