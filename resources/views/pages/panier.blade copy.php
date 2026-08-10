@extends('layouts.master')
@section('content')

<div class="px-margin-desktop py-8 max-w-4xl mx-auto">
    <h1 class="font-headline-lg text-headline-lg text-on-surface mb-8">Mon Panier</h1>

    @if (empty($items))
        <div class="text-center py-20">
            <span class="material-symbols-outlined text-6xl text-outline-variant mb-4 block">shopping_cart</span>
            <p class="text-on-surface-variant text-lg mb-6">Votre panier est vide.</p>
            <a href="{{ route('Services') }}" class="inline-block bg-primary text-on-primary px-6 py-3 rounded-xl font-body-md-bold">
                Découvrir les produits
            </a>
        </div>
    @else
        <div class="space-y-4 mb-8" id="cartItemsContainer1">
            @foreach ($items as $item)
            <div class="flex items-center gap-4 bg-surface-container-lowest rounded-2xl p-4 border border-outline-variant/30 cart-item" data-produit-id="{{ $item['produit']->id }}">

                <img src="/storage/{{ $item['produit']->produit?->image }}"
                     alt="{{ $item['produit']->produit?->nom }}"
                     class="w-20 h-20 object-cover rounded-xl">

                <div class="flex-1 min-w-0">
                    <h3 class="font-body-md-bold text-on-surface truncate">{{ $item['produit']->produit?->nom }}</h3>
                    <p class="text-sm text-on-surface-variant">{{ $item['produit']->fournisseur?->nom }}</p>
                    <span class="text-primary font-headline-md">{{ number_format($item['produit']->prix) }} FCFA</span>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" class="qty-btn w-8 h-8 rounded-full bg-surface-container-high flex items-center justify-center" data-action="decrease" data-produit-id="{{ $item['produit']->id }}">
                        <span class="material-symbols-outlined text-sm">remove</span>
                    </button>
                    <span class="w-8 text-center font-bold qty-display">{{ $item['quantite'] }}</span>
                    <button type="button" class="qty-btn w-8 h-8 rounded-full bg-surface-container-high flex items-center justify-center" data-action="increase" data-produit-id="{{ $item['produit']->id }}">
                        <span class="material-symbols-outlined text-sm">add</span>
                    </button>
                </div>

                <div class="w-28 text-right font-body-md-bold sous-total">
                    {{ number_format($item['sous_total']) }} FCFA
                </div>

                <button type="button" class="remove-btn p-2 text-error hover:bg-error/10 rounded-full" data-produit-id="{{ $item['produit']->id }}">
                    <span class="material-symbols-outlined">delete</span>
                </button>
            </div>
            @endforeach
        </div>

        <div class="flex items-center justify-between bg-surface-container-low rounded-2xl p-6">
            <span class="font-headline-md text-on-surface">Total</span>
            <span class="font-headline-lg text-primary" id="cartTotal">{{ number_format($total) }} FCFA</span>
        </div>

        <div class="flex justify-between mt-6">
            <form action="{{ route('panier.clear') }}" method="POST">
                @csrf
                <button type="submit" class="px-6 py-3 border border-outline-variant rounded-xl text-on-surface-variant hover:bg-surface-container-high">
                    Vider le panier
                </button>
            </form>

            <button type="button" class="bg-primary text-on-primary px-8 py-3 rounded-xl font-body-md-bold shadow-md">
                Passer la commande
            </button>
        </div>
    @endif
</div>

<main class="mt-8 px-margin-mobile md:px-margin-desktop max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 ">
    {{-- <main class="mt-8 px-margin-mobile md:px-margin-desktop max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8"> --}}
<!-- Cart Items Section -->
<div class="lg:col-span-7 flex flex-col  gap-gutter" id="cartItemsContainer">
<h1 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-primary mb-2">Votre Panier</h1>
<!-- Farmer Group 1: Ferme Saliou -->
@foreach ($items as $item)
<div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden border border-outline-variant cart-item" data-produit-id="{{ $item['produit']->id }}">
<div class="bg-surface-container px-card-padding py-3 flex items-center justify-between border-b border-outline-variant">
<div class="flex items-center gap-2 cursor-pointer" >
<span class="material-symbols-outlined text-primary">agriculture</span>
<span class="font-body-md-bold text-body-md-bold hover:underline">  {{ $item['produit']->fournisseur?->nom }}</span>
</div>
<span class="text-label-sm font-label-sm text-on-surface-variant">Livraison directe possible</span>
</div>
<div class="p-card-padding flex flex-col gap-stack-gap">
<!-- Item 1 -->
<div class="flex gap-4">
<div class="w-20 h-20 bg-surface-container-high rounded-lg overflow-hidden flex-shrink-0 cursor-pointer" onclick="window.location.href='detail-produit.html?id=poulet'">
<img class="w-full h-full object-cover" src="/storage/{{ $item['produit']->produit?->image }}" alt="{{ $item['produit']->produit?->nom }}">
</div>
<div class="flex-grow flex flex-col justify-between">
<div class="flex justify-between items-start">
<div>
<h3 class="font-body-md-bold text-body-md-bold cursor-pointer hover:text-primary" onclick="window.location.href='detail-produit.html?id=poulet'">{{ $item['produit']->produit?->nom }}</h3>
<p class="text-label-sm font-label-sm text-on-surface-variant">Poids approx.{{ number_format($item['produit']->taille?->taille) }}kg</p>
</div>
<span class="font-body-md-bold text-body-md-bold">{{ number_format($item['produit']->prix) }} FCFA</span>
</div>
<div class="flex justify-between items-center mt-2">
<div class="flex items-center bg-surface-container-high rounded-full px-1">
<button class="p-1 text-primary active:scale-90 transition-transform qty-btn" data-action="decrease" data-produit-id="{{ $item['produit']->id }}" >-</button>
<span class="px-4 font-body-md-bold text-body-md-bold  qty-display">{{ $item['quantite'] }}</span>
<button class="p-1 text-primary active:scale-90 transition-transform qty-btn" data-action="increase" data-produit-id="{{ $item['produit']->id }}">+</button>
</div>
<button class="text-status-error flex items-center gap-1">
<span class="remove-btn material-symbols-outlined text-lg text-error  hover:bg-error/10 rounded-full" data-produit-id="{{ $item['produit']->id }}">delete</span>
<span class="text-label-sm font-label-sm remove-btn hover:bg-error/10 rounded-full" data-produit-id="{{ $item['produit']->id }}">Retirer</span>
</button>
</div>
</div>
</div>
<div class="mt-4 pt-4 border-t border-outline-variant flex justify-between items-center">
<span class="text-on-surface-variant font-body-md text-body-md">Sous-total  {{ $item['produit']->fournisseur?->nom }}</span>
<span class="font-body-md-bold text-body-md-bold text-primary"> {{ number_format($item['sous_total']) }} FCFA</span>
</div>
</div>
</div>
@endforeach

</div>

<!-- Summary & Checkout Section -->
<div class="lg:col-span-5">

<div class="space-y-2">
<label class="font-body-md-bold text-on-surface-variant ml-1" for="email">Je souhaite être livré</label>
<input class=" h-12 px-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-0 transition-all"  type="checkbox" name="souhaite_livraison" id="souhaite_livraison" value="1">
</div>
<div class="space-y-2" style="display:none;" id="bloc_livraison">

<label class="font-body-md-bold text-on-surface-variant ml-1" for="subject"> Zone de Livraison </label>
<select class="w-full h-12 px-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-0 transition-all font-body-md bg-white" name="zone_livraison" id="zone_livraison">
 <option value="">Sélectionner une zone</option>
          @foreach($quartiers as $quartier)
              <option value="{{ $quartier->id }}" 
                      data-frais="{{ $quartier->service->prix ?? 0 }}">
                  {{ $quartier->nom_quartier }}
                  @if($quartier->service)
                      (+{{ number_format($quartier->service->prix, 0, ',', ' ') }} FCFA)
                  @endif
              </option>
          @endforeach
</select>
</div>

<div class="bg-surface-container-low rounded-2xl p-card-padding border border-outline-variant sticky top-24">
<h2 class="font-headline-md text-headline-md text-primary mb-6">Récapitulatif</h2>
<div class="flex flex-col gap-4 mb-6">
<div class="flex justify-between text-body-md font-body-md">
<span class="text-on-surface-variant">Sous-total (3 articles)</span>
<span class="" id="sous_total">{{ number_format($total) }} FCFA</span>
</div>
<div class="flex justify-between text-body-md font-body-md">
<span class="text-on-surface-variant">Frais de livraison</span>
<span class="text-status-info">Calculé au paiement</span>
</div>
</div>
<div class="border-t-2 border-dashed border-outline-variant pt-6 mb-8 flex justify-between items-center">
<span class="font-headline-md text-headline-md">Total</span>
<span class="font-headline-md text-headline-md text-primary" id="commandetotal">{{ number_format($total) }} FCFA</span>
</div>

<a href="finaliser-commande.html" class="w-full bg-primary text-on-primary font-body-md-bold text-lg py-4 rounded-xl flex items-center justify-center gap-3 shadow-lg hover:bg-primary-container transition-all">
<span>Passer la commande</span>
<span class="material-symbols-outlined">arrow_forward</span>
</a>
</div>
</div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function updateItem(produitId, quantite) {
        fetch('/panier/modifier', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ produit_id: produitId, quantite: quantite }),
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.success) {
                    location.reload(); // simple et fiable pour recalculer tous les sous-totaux/total
                }
            });
    }

    document.querySelectorAll('.qty-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const produitId = btn.getAttribute('data-produit-id');
            const action = btn.getAttribute('data-action');
            const item = btn.closest('.cart-item');
            const qtyDisplay = item.querySelector('.qty-display');
            let qty = parseInt(qtyDisplay.textContent);

            qty = action === 'increase' ? qty + 1 : qty - 1;

            if (qty <= 0) {
                if (confirm('Retirer ce produit du panier ?')) {
                    updateItem(produitId, 0);
                }
                return;
            }

            updateItem(produitId, qty);
        });
    });

    document.querySelectorAll('.remove-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const produitId = btn.getAttribute('data-produit-id');

            fetch('/panier/retirer', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ produit_id: produitId }),
            })
                .then((res) => res.json())
                .then((data) => {
                    if (data.success) location.reload();
                });
        });
    });



});
</script>

@endsection
