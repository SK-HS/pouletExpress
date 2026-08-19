@extends('layouts.master')
@section('content')

<main class="pt-8 pb-32 px-margin-mobile md:px-margin-desktop max-w-7xl mx-auto">

@if ($errors->any())
<div class="bg-error/10 border border-error text-error rounded-xl p-4 mb-6 text-sm">
    @foreach ($errors->all() as $error)
        <p>{{ $error }}</p>
    @endforeach
</div>
@endif

{{-- enctype multipart retiré : aucun fichier n'est envoyé, inutile d'alourdir la requête --}}
<form method="POST" action="{{ route('Valider-Commande-Panier') }}" id="commandeForm">
    @csrf

    {{-- Jeton anti double-soumission : généré une fois par affichage de page,
         vérifié et invalidé côté serveur pour empêcher qu'un double-clic
         (ou un rechargement de page après soumission) ne crée deux commandes --}}
    <input type="hidden" name="form_token" value="{{ $formToken }}">

    <input type="hidden" name="latitude"  id="latitude"  value="">
    <input type="hidden" name="longitude" id="longitude" value="">

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

<section class="lg:col-span-7 space-y-8">
<div class="flex flex-col gap-2">
<h1 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-primary">Finaliser votre commande</h1>
<p class="font-body-md text-on-surface-variant">Veuillez renseigner vos informations de livraison.</p>
</div>

<div class="bg-surface-container-lowest p-card-padding rounded-xl shadow-sm border border-outline-variant">
<div class="flex items-center gap-3 mb-6">
<span class="material-symbols-outlined text-primary">location_on</span>
<h2 class="font-headline-md text-headline-md">Adresse de livraison</h2>
</div>

<div class="space-y-5">

    <div class="rounded-xl border-2 border-outline-variant overflow-hidden">
        <div class="bg-primary/5 px-4 py-3 border-b border-outline-variant/50">
            <p class="font-body-md-bold text-primary text-sm">Lieu de livraison</p>
            <p class="text-xs text-on-surface-variant mt-0.5">
                Captez votre position si vous êtes sur place, ou décrivez le lieu manuellement.
            </p>
        </div>

        <div class="p-4 space-y-4">
            <button type="button" id="btn-gps"
                    class="w-full flex items-center justify-center gap-3 px-4 py-3.5
                           bg-primary text-on-primary font-body-md-bold rounded-xl
                           hover:bg-primary/90 active:scale-95 transition-all shadow-sm">
                <span class="material-symbols-outlined text-xl" id="gps-icon">my_location</span>
                <span id="gps-label">Capter ma position GPS (je suis sur place)</span>
            </button>

            <div id="gps-result" class="hidden rounded-xl p-3 text-sm font-medium flex items-start gap-2">
                <span class="material-symbols-outlined text-base shrink-0 mt-0.5" id="gps-result-icon"></span>
                <span id="gps-result-text"></span>
            </div>

            <div class="flex items-center gap-3">
                <div class="flex-1 h-px bg-outline-variant"></div>
                <span class="text-xs text-on-surface-variant font-bold shrink-0">OU</span>
                <div class="flex-1 h-px bg-outline-variant"></div>
            </div>

            <div class="space-y-1.5">
                <label class="font-label-caps text-label-caps uppercase text-on-surface-variant text-xs">
                    Décrire le lieu de livraison
                </label>
                <input type="text"
                       name="lieu_livraison"
                       id="lieu_livraison"
                       maxlength="500"
                       placeholder="Ex: Cocody Riviera 3, face à la pharmacie, bâtiment bleu..."
                       value="{{ old('lieu_livraison') }}"
                       class="w-full border-2 border-outline-variant rounded-xl p-3 font-body-md
                              focus:border-primary focus:ring-0 outline-none transition-all text-sm">
                <p class="text-xs text-on-surface-variant">
                    Plus votre description est précise, plus la livraison sera rapide.
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="space-y-1">
        <label class="font-label-caps text-label-caps uppercase text-on-surface-variant">Ville</label>
        <select name="ville" required class="w-full border-2 border-outline-variant rounded-lg p-3 font-body-md focus:border-primary focus:ring-0 outline-none transition-all">
        <option value="Abidjan" selected>Abidjan</option>
        <option value="Yamoussoukro">Yamoussoukro</option>
        <option value="Bouaké">Bouaké</option>
        </select>
        </div>
        <div class="space-y-1">
        <label class="font-label-caps text-label-caps uppercase text-on-surface-variant">Commune / Quartier</label>
        <input name="commune_quartier" required maxlength="255"
               class="w-full border-2 border-outline-variant rounded-lg p-3 font-body-md focus:border-primary focus:ring-0 outline-none transition-all"
               placeholder="ex: Cocody Mermoz" type="text" value="{{ old('commune_quartier') }}">
        </div>
    </div>

    <div class="space-y-1">
    <label class="font-label-caps text-label-caps uppercase text-on-surface-variant">Zone de livraison (frais)</label>
    <select name="quartier_id" id="quartier_id" class="w-full border-2 border-outline-variant rounded-lg p-3 font-body-md focus:border-primary focus:ring-0 outline-none transition-all">
    <option value="" data-frais="0">Aucune zone spécifique (frais calculés séparément)</option>
    @foreach($quartiers as $quartier)
    <option value="{{ $quartier->id }}" data-frais="{{ $quartier->service->prix ?? 0 }}">
        {{ $quartier->nom_quartier }}
        @if($quartier->service)
            (+{{ number_format($quartier->service->prix, 0, ',', ' ') }} FCFA)
        @endif
    </option>
    @endforeach
    </select>
    </div>

    <div class="space-y-1">
    <label class="font-label-caps text-label-caps uppercase text-on-surface-variant">Téléphone du destinataire</label>
    <input name="telephone_livraison" required maxlength="20" pattern="^[0-9+\s\-]{8,20}$"
           title="Numéro de téléphone valide (8 à 20 caractères : chiffres, +, espaces, tirets)"
           class="w-full border-2 border-outline-variant rounded-lg p-3 font-body-md focus:border-primary focus:ring-0 outline-none transition-all"
           placeholder="+225 07 00 00 00 00" type="tel"
           value="{{ old('telephone_livraison', $client->telephone ?? '') }}">
    </div>

</div>
</div>

<div class="bg-surface-container-lowest p-card-padding rounded-xl shadow-sm border border-outline-variant">
<div class="flex items-center gap-3 mb-6">
<span class="material-symbols-outlined text-primary">schedule</span>
<h2 class="font-headline-md text-headline-md">Créneau de livraison</h2>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
<label class="creneau-label relative flex flex-col p-4 border-2 border-primary bg-primary/5 rounded-xl cursor-pointer">
<input checked name="creneau" value="matin" class="absolute top-4 right-4 text-primary focus:ring-primary h-5 w-5" type="radio">
<span class="font-body-md-bold text-body-md-bold text-primary">Matin (08h - 12h)</span>
<span class="font-label-sm text-label-sm text-on-surface-variant">Demain</span>
</label>
<label class="creneau-label relative flex flex-col p-4 border-2 border-outline-variant rounded-xl cursor-pointer">
<input name="creneau" value="apres_midi" class="absolute top-4 right-4 text-primary focus:ring-primary h-5 w-5" type="radio">
<span class="font-body-md-bold text-body-md-bold text-on-surface">Après-midi (14h - 18h)</span>
<span class="font-label-sm text-label-sm text-on-surface-variant">Demain</span>
</label>
</div>
</div>
</section>

<aside class="lg:col-span-5 sticky top-24">
<div class="bg-surface-container-lowest p-card-padding rounded-xl shadow-lg border border-primary-container/20 overflow-hidden">
<div class="bg-primary-container/10 -mx-card-padding -mt-card-padding p-4 mb-6">
<h3 class="font-headline-md text-headline-md text-primary flex items-center gap-2">
<span class="material-symbols-outlined">receipt_long</span>
Résumé de commande
</h3>
</div>

<div class="space-y-6 mb-8">
    @php
        $itemsParFournisseur = collect($items)->groupBy(function($item) {
            return $item['produit']->fournisseur_id;
        });
        $indexColis = 1;
    @endphp

    @foreach ($itemsParFournisseur as $fournisseur_id => $articlesGroupes)
        <div class="bg-surface-container-low p-3 rounded-lg border border-outline-variant/50">
            <h4 class="font-label-caps text-[10px] text-primary uppercase tracking-widest mb-3 flex items-center gap-2 border-b border-primary/20 pb-2">
                <span class="material-symbols-outlined text-[14px]">inventory_2</span>
                Colis {{ $indexColis++ }} : Expédié par {{ $articlesGroupes->first()['produit']->fournisseur?->nom_ferme ?? 'Fournisseur' }}
            </h4>

            <div class="space-y-3">
                @foreach ($articlesGroupes as $item)
                    <div class="flex justify-between items-start">
                        <div class="flex-1 pr-2">
                            <p class="font-body-md-bold text-sm leading-tight">{{ $item['produit']->produit?->nom ?? 'Produit' }}</p>
                            <p class="font-label-sm text-label-sm text-on-surface-variant mt-0.5">Quantité: {{ $item['quantite'] }}</p>
                        </div>
                        <span class="font-body-md-bold text-sm whitespace-nowrap">{{ number_format($item['sous_total'], 0, ',', ' ') }} FCFA</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>

<div class="space-y-3 font-body-md text-on-surface-variant bg-surface-container-lowest border-t-2 border-dashed border-outline-variant pt-4">
<div class="flex justify-between">
<span>Sous-total des articles</span>
<span id="resume_sous_total" data-value="{{ $sousTotal }}">{{ number_format($sousTotal, 0, ',', ' ') }} FCFA</span>
</div>
<div class="flex justify-between">
<span>Frais de Livraison ({{ $itemsParFournisseur->count() }} colis)</span>
<span id="resume_livraison" data-value="0">0 FCFA</span>
</div>
<div class="flex justify-between font-headline-md text-headline-md text-on-surface pt-4 border-t border-outline-variant">
<span>Total à payer</span>
<span class="text-primary font-black" id="resume_total">{{ number_format($sousTotal, 0, ',', ' ') }} FCFA</span>
</div>
<p class="text-xs text-on-surface-variant italic mt-2 text-center">
    Les articles provenant de fournisseurs différents nécessitent des expéditions séparées. Les frais de livraison s'appliquent par colis.
</p>
</div>
</div>
</aside>
</div>

<section class="mt-12 space-y-8">
<div class="flex flex-col gap-2">
<h2 class="font-headline-md text-headline-md text-primary">Méthode de paiement</h2>
<p class="font-body-md text-on-surface-variant">Sélectionnez votre mode de paiement sécurisé Mobile Money.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <label class="relative group cursor-pointer block">
        <div class="h-24 bg-surface-container-lowest border-2 border-outline-variant rounded-xl flex items-center px-4 sm:px-6 gap-3 sm:gap-4 transition-all has-[:checked]:border-orange-money has-[:checked]:bg-orange-money/5 hover:bg-slate-50">
            <div class="w-16 sm:w-20 h-12 bg-emerald-700 rounded-lg flex items-center justify-center text-white font-bold text-xs sm:text-sm">ESPECE</div>
            <div><p class="font-bold text-sm sm:text-base">Espèce</p><p class="text-xs text-slate-500">Paiement Espèce</p></div>
            <input class="ml-auto w-5 h-5 cursor-pointer accent-orange-600" name="mode_paiement" value="ESPECE" type="radio" checked>
        </div>
    </label>
    <label class="relative group cursor-pointer block">
        <div class="h-24 bg-surface-container-lowest border-2 border-outline-variant rounded-xl flex items-center px-4 sm:px-6 gap-3 sm:gap-4 transition-all has-[:checked]:border-orange-money has-[:checked]:bg-orange-money/5 hover:bg-slate-50">
            <div class="w-16 sm:w-20 h-12 bg-blue-500 rounded-lg flex items-center justify-center text-white font-bold text-xs sm:text-sm">WAVE</div>
            <div><p class="font-bold text-sm sm:text-base">Wave</p><p class="text-xs text-slate-500">Paiement Mobile</p></div>
            <input class="ml-auto w-5 h-5 cursor-pointer accent-orange-600" name="mode_paiement" value="WAVE" type="radio">
        </div>
    </label>
    <label class="relative group cursor-pointer block">
        <div class="h-24 bg-surface-container-lowest border-2 border-outline-variant rounded-xl flex items-center px-4 sm:px-6 gap-3 sm:gap-4 transition-all has-[:checked]:border-orange-money has-[:checked]:bg-orange-money/5 hover:bg-slate-50">
            <div class="w-16 h-12 bg-orange-500 rounded-lg flex items-center justify-center text-white font-bold text-xs sm:text-sm">OM</div>
            <div><p class="font-bold text-sm sm:text-base">Orange Money</p><p class="text-xs text-slate-500">Paiement Mobile</p></div>
            <input class="ml-auto w-5 h-5 cursor-pointer accent-orange-600" name="mode_paiement" value="ORANGE-MONEY" type="radio">
        </div>
    </label>
    <label class="relative group cursor-pointer block">
        <div class="h-24 bg-surface-container-lowest border-2 border-outline-variant rounded-xl flex items-center px-4 sm:px-6 gap-3 sm:gap-4 transition-all has-[:checked]:border-mtn-money has-[:checked]:bg-mtn-money/5 hover:bg-slate-50">
            <div class="w-16 h-12 bg-yellow-400 rounded-lg flex items-center justify-center text-slate-900 font-bold text-xs sm:text-sm">MTN</div>
            <div><p class="font-bold text-sm sm:text-base">MTN Mobile</p><p class="text-xs text-slate-500">Paiement Mobile</p></div>
            <input class="ml-auto w-5 h-5 cursor-pointer accent-yellow-500" name="mode_paiement" value="MTN-MONEY" type="radio">
        </div>
    </label>
    <label class="relative group cursor-pointer block">
        <div class="h-24 bg-surface-container-lowest border-2 border-outline-variant rounded-xl flex items-center px-4 sm:px-6 gap-3 sm:gap-4 transition-all has-[:checked]:border-moov-money has-[:checked]:bg-moov-money/5 hover:bg-slate-50">
            <div class="w-16 h-12 bg-blue-700 rounded-lg flex items-center justify-center text-white font-bold text-xs sm:text-sm">Moov</div>
            <div><p class="font-bold text-sm sm:text-base">Moov Money</p><p class="text-xs text-slate-500">Paiement Mobile</p></div>
            <input class="ml-auto w-5 h-5 cursor-pointer accent-blue-700" name="mode_paiement" value="MOOV-MONEY" type="radio">
        </div>
    </label>
</div>
</section>

<div class="mt-12 flex flex-col md:flex-row items-center justify-between gap-6 p-6 bg-surface-container-high rounded-2xl border border-outline-variant/30">
<div class="flex items-center gap-4">
<span class="material-symbols-outlined text-primary text-3xl">verified_user</span>
<div>
<p class="font-body-md-bold text-body-md-bold">Paiement 100% sécurisé</p>
<p class="font-label-sm text-label-sm text-on-surface-variant">Validation par Mobile Money</p>
</div>
</div>
<button type="submit" id="btn-submit" class="w-full md:w-auto px-12 h-14 bg-primary text-white font-body-md-bold rounded-xl active:scale-95 transition-transform shadow-lg shadow-primary/20 flex items-center justify-center gap-3">
<span id="btn-submit-label">Valider et Payer</span>
<span class="material-symbols-outlined" id="btn-submit-icon">arrow_forward</span>
</button>
</div>

</form>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Calcul des frais de livraison (multiplié par le nombre de fournisseurs) ──
    const quartierSelect = document.getElementById('quartier_id');
    const sousTotalEl = document.getElementById('resume_sous_total');
    const sousTotal = sousTotalEl ? parseFloat(sousTotalEl.dataset.value) || 0 : 0;
    const nombreFournisseurs = {{ (int) $itemsParFournisseur->count() }};

    function formatMontant(v) {
        return new Intl.NumberFormat('fr-FR').format(v);
    }

    //  Garde défensive : évite un crash JS si l'élément venait à manquer
    if (quartierSelect) {
        quartierSelect.addEventListener('change', function () {
            const fraisBase = parseFloat(this.options[this.selectedIndex].getAttribute('data-frais')) || 0;
            const fraisTotalLivraison = fraisBase * nombreFournisseurs;

            const resumeLivraison = document.getElementById('resume_livraison');
            const resumeTotal = document.getElementById('resume_total');

            if (resumeLivraison) {
                resumeLivraison.textContent = formatMontant(fraisTotalLivraison) + ' FCFA';
                resumeLivraison.dataset.value = fraisTotalLivraison;
            }
            if (resumeTotal) {
                resumeTotal.textContent = formatMontant(sousTotal + fraisTotalLivraison) + ' FCFA';
            }
        });
    }

    // ── Style créneaux radio ───────────────────────────────────
    document.querySelectorAll('.creneau-label input[type=radio]').forEach(function (radio) {
        radio.addEventListener('change', function () {
            document.querySelectorAll('.creneau-label').forEach(function (label) {
                label.classList.remove('border-primary', 'bg-primary/5');
                label.classList.add('border-outline-variant');
            });
            this.closest('.creneau-label').classList.add('border-primary', 'bg-primary/5');
            this.closest('.creneau-label').classList.remove('border-outline-variant');
        });
    });

    // ── GPS ────────────────────────────────────────────────────
    const btnGps      = document.getElementById('btn-gps');
    const gpsIcon     = document.getElementById('gps-icon');
    const gpsLabel    = document.getElementById('gps-label');
    const gpsResult   = document.getElementById('gps-result');
    const gpsResultIcon = document.getElementById('gps-result-icon');
    const gpsResultText = document.getElementById('gps-result-text');
    const inputLat    = document.getElementById('latitude');
    const inputLng    = document.getElementById('longitude');

    function afficherResultat(type, message) {
        gpsResult.classList.remove('hidden', 'bg-emerald-50', 'text-emerald-700', 'bg-red-50', 'text-red-700', 'bg-amber-50', 'text-amber-700');
        const styles = {
            success: { bg: 'bg-emerald-50', text: 'text-emerald-700', icon: 'check_circle' },
            error:   { bg: 'bg-red-50',     text: 'text-red-700',     icon: 'error' },
            warning: { bg: 'bg-amber-50',   text: 'text-amber-700',   icon: 'warning' },
        };
        const s = styles[type] || styles.warning;
        gpsResult.classList.add(s.bg, s.text);
        gpsResultIcon.textContent = s.icon;
        gpsResultText.textContent = message;
    }

    if (btnGps) {
        btnGps.addEventListener('click', function () {
            if (!navigator.geolocation) {
                afficherResultat('error', 'La géolocalisation n\'est pas supportée par votre navigateur. Décrivez votre adresse manuellement ci-dessous.');
                return;
            }

            btnGps.disabled = true;
            gpsIcon.textContent = 'refresh';
            gpsIcon.style.animation = 'spin 1s linear infinite';
            gpsLabel.textContent = 'Localisation en cours...';

            navigator.geolocation.getCurrentPosition(
                function (position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    const precision = Math.round(position.coords.accuracy);

                    inputLat.value = lat;
                    inputLng.value = lng;

                    btnGps.disabled = false;
                    gpsIcon.textContent = 'check_circle';
                    gpsIcon.style.animation = '';
                    gpsLabel.textContent = 'Position captée — cliquer pour actualiser';
                    btnGps.classList.remove('bg-primary');
                    btnGps.classList.add('bg-emerald-600');

                    afficherResultat('success',
                        'Position GPS enregistrée (précision ±' + precision + ' m). '
                        + 'Vous pouvez aussi ajouter une description pour aider le livreur.'
                    );
                },
                function (err) {
                    btnGps.disabled = false;
                    gpsIcon.textContent = 'my_location';
                    gpsIcon.style.animation = '';
                    gpsLabel.textContent = 'Capter ma position GPS (je suis sur place)';

                    let msg = '';
                    switch (err.code) {
                        case err.PERMISSION_DENIED:
                            msg = 'Permission GPS refusée. Autorisez la localisation dans les paramètres de votre navigateur, puis réessayez.';
                            if (location.protocol === 'http:' && !location.hostname.includes('localhost')) {
                                msg += ' Note : le GPS nécessite une connexion HTTPS.';
                            }
                            break;
                        case err.POSITION_UNAVAILABLE:
                            msg = 'Position GPS indisponible. Vérifiez que le GPS est activé sur votre téléphone.';
                            break;
                        case err.TIMEOUT:
                            msg = 'Délai GPS dépassé. Allez en extérieur et réessayez.';
                            break;
                        default:
                            msg = 'Erreur GPS. Décrivez votre adresse manuellement ci-dessous.';
                    }
                    afficherResultat('error', msg);
                },
                { enableHighAccuracy: true, timeout: 12000, maximumAge: 0 }
            );
        });
    }

    // ── Anti double-soumission côté client ──────────────────
    // Désactive le bouton dès le premier clic, empêche un double-clic
    // d'envoyer deux fois le formulaire pendant que la 1ère requête est en cours
    const form = document.getElementById('commandeForm');
    const btnSubmit = document.getElementById('btn-submit');
    const btnLabel = document.getElementById('btn-submit-label');

    form.addEventListener('submit', function () {
        btnSubmit.disabled = true;
        btnSubmit.classList.add('opacity-70', 'cursor-not-allowed');
        btnLabel.textContent = 'Traitement en cours...';
        // Le formulaire continue sa soumission normale (pas de preventDefault) —
        // on désactive juste visuellement pour empêcher un second clic
    });
});
</script>

<style>
@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
</style>

@endsection