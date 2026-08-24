@extends('layouts.master')
@section('content')

<main class="mt-8 px-margin-mobile md:px-margin-desktop max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 ">

<div class="lg:col-span-7 flex flex-col gap-gutter" id="cartItemsContainer">
<h1 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-primary mb-2">Votre Panier</h1>

@forelse ($items as $item)
@php
    $nomProduit     = $item['produit']->produit?->nom ?? 'Produit sans nom';
    $imageProduit   = $item['produit']->produit?->image;
    $urlImage       = $imageProduit ? '/storage/' . $imageProduit : asset('images/produit-placeholder.png');
    $nomFournisseur = $item['produit']->fournisseur?->nom ?? 'Fournisseur inconnu';

    // min = quantité minimum de commande, max = stock réel disponible (pas l'inverse)
    $commandeMin = max(1, (int) ($item['produit']->commande_min ?? 1));
    $stockMax    = max($commandeMin, (int) ($item['produit']->quantite ?? $commandeMin));
@endphp
<div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden border border-outline-variant cart-item" data-produit-id="{{ $item['produit']->id }}">
<div class="bg-surface-container px-card-padding py-3 flex items-center justify-between border-b border-outline-variant">
<div class="flex items-center gap-2 cursor-pointer">
<span class="material-symbols-outlined text-primary">agriculture</span>
<span class="font-body-md-bold text-body-md-bold hover:underline">{{ $nomFournisseur }}</span>
</div>
<span class="text-label-sm font-label-sm text-on-surface-variant">Livraison directe possible</span>
</div>
<div class="p-card-padding flex flex-col gap-stack-gap">
<div class="flex gap-4">
<div class="w-20 h-20 bg-surface-container-high rounded-lg overflow-hidden flex-shrink-0 cursor-pointer" onclick="window.location.href='{{ route('Detail-Produit', $item['produit']->id) }}'">
<img class="w-full h-full object-cover" src="{{ $urlImage }}" alt="{{ $nomProduit }}" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('images/produit-placeholder.png') }}';">
</div>
<div class="flex-grow flex flex-col justify-between">
<div class="flex justify-between items-start">
<div>
<h3 class="font-body-md-bold text-body-md-bold cursor-pointer hover:text-primary" onclick="window.location.href='{{ route('Detail-Produit', $item['produit']->id) }}'">{{ $nomProduit }}</h3>
<p class="text-label-sm font-label-sm text-on-surface-variant">Poids approx.{{ number_format($item['produit']->taille?->taille ?? 0) }}kg</p>
</div>
<span class="font-body-md-bold text-body-md-bold">{{ number_format($item['produit']->prix) }} FCFA</span>
</div>
<div class="flex justify-between items-center mt-2">
<div class="flex items-center bg-surface-container-high rounded-full px-1">
<button type="button" class="p-1 w-8 h-8 text-primary active:scale-90 transition-transform qty-btn" data-action="decrease" data-produit-id="{{ $item['produit']->id }}">-</button>
<input type="number"
       class="w-20 text-center font-body-md-bold text-body-md-bold bg-transparent border-0 focus:ring-0 qty-input"
       data-produit-id="{{ $item['produit']->id }}"
       value="{{ $item['quantite'] }}"
       min="{{ $commandeMin }}" max="{{ $stockMax }}">
<button type="button" class="p-1 w-8 h-8 text-primary active:scale-90 transition-transform qty-btn" data-action="increase" data-produit-id="{{ $item['produit']->id }}">+</button>
</div>
<button type="button" class="text-status-error flex items-center gap-1 remove-btn" data-produit-id="{{ $item['produit']->id }}">
<span class="material-symbols-outlined text-lg text-error hover:bg-error/10 rounded-full">delete</span>
<span class="text-label-sm font-label-sm hover:bg-error/10 rounded-full">Retirer</span>
</button>
</div>
</div>
</div>
<div class="mt-4 pt-4 border-t border-outline-variant flex justify-between items-center">
<span class="text-on-surface-variant font-body-md text-body-md">Sous-total {{ $nomFournisseur }}</span>
<span class="font-body-md-bold text-body-md-bold text-primary item-sous-total" data-produit-id="{{ $item['produit']->id }}"> {{ number_format($item['sous_total']) }} FCFA</span>
</div>
</div>
</div>
@empty
<div class="text-center py-16 bg-surface-container-lowest rounded-2xl border border-outline-variant/30">
    <span class="material-symbols-outlined text-4xl text-outline-variant mb-3">shopping_cart</span>
    <p class="text-on-surface-variant font-bold">Votre panier est vide.</p>
</div>
@endforelse

</div>

<div class="lg:col-span-5">

{{--
    Bloc "Zone de Livraison" retiré de cette page — la sélection de zone
    se fait désormais à l'étape "Finaliser la commande".
    Le JS correspondant a été retiré en même temps (voir plus bas) pour
    éviter toute référence à des éléments/variables inexistants.
--}}

<div class="bg-surface-container-low rounded-2xl p-card-padding border border-outline-variant sticky top-24 mt-6">
<h2 class="font-headline-md text-headline-md text-primary mb-6">Récapitulatif</h2>
<div class="flex flex-col gap-4 mb-6">
<div class="flex justify-between text-body-md font-body-md">
<span class="text-on-surface-variant">Sous-total (<span id="items_count">{{ count($items) }}</span> articles)</span>
<span id="sous_total" data-value="{{ $total }}">{{ number_format($total) }} FCFA</span>
</div>
</div>
<div class="border-t-2 border-dashed border-outline-variant pt-6 mb-8 flex justify-between items-center">
<span class="font-headline-md text-headline-md">Total</span>
<span class="font-headline-md text-headline-md text-primary" id="commandetotal">{{ number_format($total) }} FCFA</span>
</div>

@if(count($items) > 0)
<a href="{{ route('Finaliser-Commande') }}" class="w-full bg-primary text-on-primary font-body-md-bold text-lg py-4 rounded-xl flex items-center justify-center gap-3 shadow-lg hover:bg-primary-container transition-all">
<span>Passer la commande</span>
<span class="material-symbols-outlined">arrow_forward</span>
</a>
@else
<button type="button" disabled class="w-full bg-outline-variant text-on-surface-variant font-body-md-bold text-lg py-4 rounded-xl flex items-center justify-center gap-3 cursor-not-allowed">
<span>Panier vide</span>
</button>
@endif
</div>
</div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const requestVersion = {};

    function formatMontant(valeur) {
        return new Intl.NumberFormat('fr-FR').format(valeur);
    }

    function updateItem(produitId, quantite) {
        requestVersion[produitId] = (requestVersion[produitId] || 0) + 1;
        const monVersion = requestVersion[produitId];

        document.querySelectorAll(`.qty-btn[data-produit-id="${produitId}"]`).forEach(b => b.disabled = true);

        fetch('/panier/modifier', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ produit_id: produitId, quantite: quantite }),
        })
            .then((res) => {
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return res.json();
            })
            .then((data) => {
                if (monVersion !== requestVersion[produitId]) return;
                if (!data.success) return;

                if (data.item === null) {
                    const card = document.querySelector(`.cart-item[data-produit-id="${produitId}"]`);
                    if (card) card.remove();
                } else {
                    const sousTotalEl = document.querySelector(`.item-sous-total[data-produit-id="${produitId}"]`);
                    if (sousTotalEl) sousTotalEl.textContent = formatMontant(data.item.sous_total) + ' FCFA';

                    const qtyInput = document.querySelector(`.qty-input[data-produit-id="${produitId}"]`);
                    if (qtyInput) qtyInput.value = data.item.quantite;
                }

                document.getElementById('sous_total').dataset.value = data.total;
                document.getElementById('sous_total').textContent = formatMontant(data.total) + ' FCFA';
                document.getElementById('items_count').textContent = data.count;

                recalculerTotal();
            })
            .catch((err) => console.error('Erreur mise à jour panier :', err))
            .finally(() => {
                document.querySelectorAll(`.qty-btn[data-produit-id="${produitId}"]`).forEach(b => b.disabled = false);
            });
    }

    document.querySelectorAll('.qty-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const produitId = btn.getAttribute('data-produit-id');
            const action = btn.getAttribute('data-action');
            const input = document.querySelector(`.qty-input[data-produit-id="${produitId}"]`);
            const min = parseInt(input.getAttribute('min')) || 1;
            const max = parseInt(input.getAttribute('max')) || 99;
            let qty = parseInt(input.value) || min;

            qty = action === 'increase' ? qty + 1 : qty - 1;

            //Ne descend jamais sous la quantité minimum de commande —
            // pour retirer l'article, on passe par "Retirer", pas par "-"
            if (qty < min) {
                if (confirm(`La quantité minimum est ${min}. Retirer ce produit du panier ?`)) {
                    updateItem(produitId, 0);
                }
                return;
            }

            if (qty > max) {
                qty = max;
                alert(`Quantité maximale disponible : ${max}`);
            }

            input.value = qty;
            updateItem(produitId, qty);
        });
    });

    let debounceQty = {};
    document.querySelectorAll('.qty-input').forEach(function (input) {
        input.addEventListener('input', function () {
            const produitId = input.getAttribute('data-produit-id');
            const min = parseInt(input.getAttribute('min')) || 1;
            const max = parseInt(input.getAttribute('max')) || 99;
            let qty = parseInt(input.value);

            clearTimeout(debounceQty[produitId]);
            debounceQty[produitId] = setTimeout(function () {
                if (isNaN(qty) || qty < min) qty = min;
                if (qty > max) qty = max;
                input.value = qty;
                updateItem(produitId, qty);
            }, 600);
        });
    });

    document.querySelectorAll('.remove-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const produitId = btn.getAttribute('data-produit-id');
            btn.disabled = true;

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
                    if (!data.success) return;

                    const card = document.querySelector(`.cart-item[data-produit-id="${produitId}"]`);
                    if (card) card.remove();

                    const sousTotalEl = document.getElementById('sous_total');
                    if (sousTotalEl) {
                        sousTotalEl.dataset.value = data.total;
                        sousTotalEl.textContent = formatMontant(data.total) + ' FCFA';
                    }

                    const itemsCountEl = document.getElementById('items_count');
                    if (itemsCountEl) itemsCountEl.textContent = data.count;

                    recalculerTotal();

                    const cartBadge = document.getElementById('cartCountBadge');
                    if (cartBadge) {
                        cartBadge.textContent = data.count;
                        cartBadge.style.display = data.count === 0 ? 'none' : 'flex';
                    }

                    if (data.count === 0) {
                        setTimeout(() => location.reload(), 300);
                    }
                })
                .catch((error) => console.error('Erreur lors de la suppression:', error));
        });
    });

    // Bloc "Zone de Livraison" retiré : plus aucune référence à
    // selectZone / fraisDisplay / zoneHiddenInput, qui n'existent plus dans le DOM

    function recalculerTotal() {
        const sousTotal = parseFloat(document.getElementById('sous_total').dataset.value) || 0;
        const totalEl = document.getElementById('commandetotal');
        if (totalEl) totalEl.textContent = formatMontant(sousTotal) + ' FCFA';
    }
});
</script>

@endsection
