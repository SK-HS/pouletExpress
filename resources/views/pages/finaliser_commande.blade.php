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

<form method="POST" action="{{ route('Valider-Commande') }}" id="commandeForm">
@csrf

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
<!-- Left Side: Delivery Details -->
<section class="lg:col-span-7 space-y-8">
<div class="flex flex-col gap-2">
<h1 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-primary">Finaliser votre commande</h1>
<p class="font-body-md text-on-surface-variant">Veuillez renseigner vos informations de livraison.</p>
</div>

<!-- Address Selection Card -->
<div class="bg-surface-container-lowest p-card-padding rounded-xl shadow-sm border border-outline-variant">
<div class="flex items-center gap-3 mb-6">
<span class="material-symbols-outlined text-primary">location_on</span>
<h2 class="font-headline-md text-headline-md">Adresse de livraison</h2>
</div>
<div class="space-y-4">
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
<input name="commune_quartier" required class="w-full border-2 border-outline-variant rounded-lg p-3 font-body-md focus:border-primary focus:ring-0 outline-none transition-all" placeholder="ex: Cocody Mermoz" type="text" value="{{ old('commune_quartier') }}">
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
<input name="telephone_livraison" required class="w-full border-2 border-outline-variant rounded-lg p-3 font-body-md focus:border-primary focus:ring-0 outline-none transition-all" placeholder="+225 07 00 00 00 00" type="tel" value="{{ old('telephone_livraison', $client->telephone ?? '') }}">
</div>
</div>
</div>

<!-- Delivery Slot Selection -->
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

<!-- Right Side: Order Summary -->
<aside class="lg:col-span-5 sticky top-24">
<div class="bg-surface-container-lowest p-card-padding rounded-xl shadow-lg border border-primary-container/20 overflow-hidden">
<div class="bg-primary-container/10 -mx-card-padding -mt-card-padding p-4 mb-6">
<h3 class="font-headline-md text-headline-md text-primary flex items-center gap-2">
<span class="material-symbols-outlined">receipt_long</span>
Résumé de commande
</h3>
</div>
<div class="space-y-4 mb-8">
@foreach ($items as $item)
<div class="flex justify-between items-center pb-4 border-b border-outline-variant">
<div>
<p class="font-body-md-bold text-body-md-bold">{{ $item['produit']->produit?->nom }}</p>
<p class="font-label-sm text-label-sm text-on-surface-variant">Quantité: {{ $item['quantite'] }}</p>
</div>
<span class="font-body-md-bold">{{ number_format($item['sous_total']) }} FCFA</span>
</div>
@endforeach
</div>
<div class="space-y-3 font-body-md text-on-surface-variant">
<div class="flex justify-between">
<span>Sous-total</span>
<span id="resume_sous_total" data-value="{{ $sousTotal }}">{{ number_format($sousTotal) }} FCFA</span>
</div>
<div class="flex justify-between">
<span>Livraison</span>
<span id="resume_livraison" data-value="0">0 FCFA</span>
</div>
<div class="flex justify-between font-headline-md text-headline-md text-on-surface pt-4 border-t-2 border-dashed border-outline-variant">
<span>Total à payer</span>
<span class="text-primary" id="resume_total">{{ number_format($sousTotal) }} FCFA</span>
</div>
</div>
</div>
</aside>
</div>

<!-- Payment Selection Section -->
<section class="mt-12 space-y-8">
<div class="flex flex-col gap-2">
<h2 class="font-headline-md text-headline-md text-primary">Méthode de paiement</h2>
<p class="font-body-md text-on-surface-variant">Sélectionnez votre mode de paiement sécurisé Mobile Money.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <!-- ESPECE -->
    <label class="relative group cursor-pointer block">
        <div class="h-24 bg-surface-container-lowest border-2 border-outline-variant rounded-xl flex items-center px-4 sm:px-6 gap-3 sm:gap-4 transition-all has-[:checked]:border-orange-money has-[:checked]:bg-orange-money/5 hover:bg-slate-50">
            
            <div class="w-16 sm:w-20 h-12 bg-emerald-700 rounded-lg flex items-center justify-center text-white font-bold text-xs sm:text-sm">ESPECE</div>
            
            <div>
                <p class="font-bold text-sm sm:text-base">Espèce</p>
                <p class="text-xs text-slate-500">Paiement Espèce</p>
            </div>

            <!-- Bouton à droite grâce à ml-auto -->
            <input class="ml-auto w-5 h-5 cursor-pointer accent-orange-600" name="mode_paiement" value="ESPECE" type="radio" checked>
        </div>
    </label>

    <!-- WAVE -->
    <label class="relative group cursor-pointer block">
        <div class="h-24 bg-surface-container-lowest border-2 border-outline-variant rounded-xl flex items-center px-4 sm:px-6 gap-3 sm:gap-4 transition-all has-[:checked]:border-orange-money has-[:checked]:bg-orange-money/5 hover:bg-slate-50">
            
            <div class="w-16 sm:w-20 h-12 bg-blue-500 rounded-lg flex items-center justify-center text-white font-bold text-xs sm:text-sm">WAVE</div>
            
            <div>
                <p class="font-bold text-sm sm:text-base">Wave</p>
                <p class="text-xs text-slate-500">Paiement Mobile</p>
            </div>

            <input class="ml-auto w-5 h-5 cursor-pointer accent-orange-600" name="mode_paiement" value="WAVE" type="radio">
        </div>
    </label>

    <!-- ORANGE MONEY -->
    <label class="relative group cursor-pointer block">
        <div class="h-24 bg-surface-container-lowest border-2 border-outline-variant rounded-xl flex items-center px-4 sm:px-6 gap-3 sm:gap-4 transition-all has-[:checked]:border-orange-money has-[:checked]:bg-orange-money/5 hover:bg-slate-50">
            
            <div class="w-16 h-12 bg-orange-500 rounded-lg flex items-center justify-center text-white font-bold text-xs sm:text-sm">OM</div>
            
            <div>
                <p class="font-bold text-sm sm:text-base">Orange Money</p>
                <p class="text-xs text-slate-500">Paiement Mobile</p>
            </div>

            <input class="ml-auto w-5 h-5 cursor-pointer accent-orange-600" name="mode_paiement" value="ORANGE-MONEY" type="radio">
        </div>
    </label>

    <!-- MTN MONEY -->
    <label class="relative group cursor-pointer block">
        <div class="h-24 bg-surface-container-lowest border-2 border-outline-variant rounded-xl flex items-center px-4 sm:px-6 gap-3 sm:gap-4 transition-all has-[:checked]:border-mtn-money has-[:checked]:bg-mtn-money/5 hover:bg-slate-50">
            
            <div class="w-16 h-12 bg-yellow-400 rounded-lg flex items-center justify-center text-slate-900 font-bold text-xs sm:text-sm">MTN</div>
            
            <div>
                <p class="font-bold text-sm sm:text-base">MTN Mobile</p>
                <p class="text-xs text-slate-500">Paiement Mobile</p>
            </div>

            <input class="ml-auto w-5 h-5 cursor-pointer accent-yellow-500" name="mode_paiement" value="MTN-MONEY" type="radio">
        </div>
    </label>

    <!-- MOOV MONEY -->
    <label class="relative group cursor-pointer block">
        <div class="h-24 bg-surface-container-lowest border-2 border-outline-variant rounded-xl flex items-center px-4 sm:px-6 gap-3 sm:gap-4 transition-all has-[:checked]:border-moov-money has-[:checked]:bg-moov-money/5 hover:bg-slate-50">
            
            <div class="w-16 h-12 bg-blue-700 rounded-lg flex items-center justify-center text-white font-bold text-xs sm:text-sm">Moov</div>
            
            <div>
                <p class="font-bold text-sm sm:text-base">Moov Money</p>
                <p class="text-xs text-slate-500">Paiement Mobile</p>
            </div>

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
<button type="submit" class="w-full md:w-auto px-12 h-14 bg-primary text-white font-body-md-bold rounded-xl active:scale-95 transition-transform shadow-lg shadow-primary/20 flex items-center justify-center gap-3">
Valider et Payer
<span class="material-symbols-outlined">arrow_forward</span>
</button>
</div>

</form>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const quartierSelect = document.getElementById('quartier_id');
    const sousTotal = parseFloat(document.getElementById('resume_sous_total').dataset.value) || 0;

    function formatMontant(v) {
        return new Intl.NumberFormat('fr-FR').format(v);
    }

    quartierSelect.addEventListener('change', function () {
        const frais = parseFloat(this.options[this.selectedIndex].getAttribute('data-frais')) || 0;
        document.getElementById('resume_livraison').textContent = formatMontant(frais) + ' FCFA';
        document.getElementById('resume_livraison').dataset.value = frais;
        document.getElementById('resume_total').textContent = formatMontant(sousTotal + frais) + ' FCFA';
    });

    // Style visuel des créneaux radio sélectionnés
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
});
</script>

@endsection
