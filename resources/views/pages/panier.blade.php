@extends('layouts.master')
@section('content')

<main class="mt-8 px-margin-mobile md:px-margin-desktop max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 ">

<div class="lg:col-span-7 flex flex-col gap-gutter" id="cartItemsContainer">
<h1 class="font-headline-lg-mobile md:font-headline-lg text-primary mb-2">Votre Panier</h1>

@forelse ($items as $item)
@php
    $nomProduit     = $item['produit']->produit?->nom ?? 'Produit sans nom';
    $imageProduit   = $item['produit']->produit?->image;
    $urlImage       = $imageProduit ? '/storage/' . $imageProduit : asset('images/produit-placeholder.png');
    $nomFournisseur = $item['produit']->fournisseur?->nom ?? 'Fournisseur inconnu';

    $commandeMin = max(1, (int) ($item['produit']->commande_min ?? 1));
    $stockMax    = max($commandeMin, (int) ($item['produit']->quantite ?? $commandeMin));
@endphp

<div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden border border-outline-variant cart-item" data-produit-id="{{ $item['produit']->id }}">
    
    <div class="bg-surface-container px-card-padding py-3 flex items-center justify-between border-b border-outline-variant">
        <div class="flex items-center gap-2 cursor-pointer">
            <span class="material-symbols-outlined text-primary">agriculture</span>
            <span class="font-body-md-bold hover:underline">{{ $nomFournisseur }}</span>
        </div>
        <span class="text-label-sm text-on-surface-variant">Livraison directe possible</span>
    </div>
    
    <div class="p-card-padding flex flex-col gap-4">
        <div class="flex gap-4">
            
            <div class="w-20 h-20 bg-surface-container-high rounded-lg overflow-hidden flex-shrink-0 cursor-pointer" onclick="window.location.href='{{ route('Detail-Produit', $item['produit']->id) }}'">
                <img class="w-full h-full object-cover" src="{{ $urlImage }}" alt="{{ $nomProduit }}" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('images/produit-placeholder.png') }}';">
            </div>
            
            <div class="flex-grow flex flex-col justify-between">
                
                <!-- Ligne Haut : Titre, Prix et Promos -->
                <div class="flex justify-between items-start gap-4">
                    <div>
                        <h3 class="font-body-md-bold cursor-pointer hover:text-primary leading-tight" onclick="window.location.href='{{ route('Detail-Produit', $item['produit']->id) }}'">{{ $nomProduit }}</h3>
                        <p class="text-label-sm text-on-surface-variant mt-1">Poids approx. {{ $item['produit']->taille?->taille }}kg</p>
                        
                        <!-- CONTENEUR DES MESSAGES PROMOS (Géré par JS) -->
                        <div class="js-promo-messages" data-produit-id="{{ $item['produit']->id }}">
                            @if(isset($item['quantite_manquante_promo']) && $item['quantite_manquante_promo'] > 0)
                                <div class="text-orange-money text-[10px] md:text-xs font-bold mt-2 bg-orange-money/10 px-2 py-1 rounded inline-block">
                                    Plus que {{ $item['quantite_manquante_promo'] }} articles pour débloquer -{{ round($item['taux_promo_volume']) }}% !
                                </div>
                            @elseif($item['en_promo'])
                                <div class="text-status-success text-[10px] md:text-xs font-bold mt-2 bg-status-success/10 px-2 py-1 rounded inline-block">
                                    Promo appliquée ! Vous économisez {{ number_format($item['economie_unitaire'] * $item['quantite'], 0, ',', ' ') }} FCFA.
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- PRIX UNITAIRE (Géré par JS) -->
                    <div class="text-right flex flex-col items-end shrink-0 js-price-wrapper" data-produit-id="{{ $item['produit']->id }}">
                        @if($item['en_promo'])
                            <span class="text-xs text-on-surface-variant line-through mb-0.5">{{ number_format($item['prix_unitaire_initial'], 0, ',', ' ') }} FCFA</span>
                            <span class="font-headline-md text-base md:text-lg text-status-error font-black leading-none">{{ number_format($item['prix_unitaire_final'], 0, ',', ' ') }} FCFA</span>
                        @else
                            <span class="font-headline-md text-base md:text-lg text-primary font-black leading-none">{{ number_format($item['prix_unitaire_initial'], 0, ',', ' ') }} FCFA</span>
                        @endif
                    </div>
                </div>

                <!-- Ligne Bas : Boutons de Quantité -->
                <div class="flex justify-between items-center mt-3">
                    <div class="flex items-center bg-surface-container-high rounded-full px-1">
                        <button type="button" class="p-1 w-8 h-8 text-primary active:scale-90 transition-transform qty-btn" data-action="decrease" data-produit-id="{{ $item['produit']->id }}">-</button>
                        <input type="number"
                               class="w-20 text-center font-body-md-bold bg-transparent border-0 focus:ring-0 qty-input"
                               data-produit-id="{{ $item['produit']->id }}"
                               value="{{ $item['quantite'] }}"
                               min="{{ $commandeMin }}" max="{{ $stockMax }}">
                        <button type="button" class="p-1 w-8 h-8 text-primary active:scale-90 transition-transform qty-btn" data-action="increase" data-produit-id="{{ $item['produit']->id }}">+</button>
                    </div>
                    
                    <button type="button" class="text-status-error flex items-center gap-1 remove-btn" data-produit-id="{{ $item['produit']->id }}">
                        <span class="material-symbols-outlined text-lg hover:bg-error/10 rounded-full">delete</span>
                        <span class="text-label-sm font-label-sm hidden sm:inline">Retirer</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-surface-container px-4 py-3 border-t border-outline-variant flex justify-between items-center">
        <span class="text-on-surface-variant font-body-md">Sous-total {{ $nomFournisseur }}</span>
        <span class="font-headline-md text-primary item-sous-total" data-produit-id="{{ $item['produit']->id }}"> 
            {{ number_format($item['sous_total'], 0, ',', ' ') }} FCFA
        </span>
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
    <div class="bg-surface-container-low rounded-2xl p-card-padding border border-outline-variant sticky top-24 mt-6 lg:mt-0">
        <h2 class="font-headline-md text-primary mb-6">Récapitulatif</h2>
        <div class="flex flex-col gap-4 mb-6">
            <div class="flex justify-between text-body-md">
                <span class="text-on-surface-variant">Total des articles (<span id="items_count">{{ count($items) }}</span>)</span>
                <span id="sous_total" data-value="{{ $total }}">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
            </div>
        </div>
        
        <div class="border-t-2 border-dashed border-outline-variant pt-6 mb-8 flex justify-between items-center">
            <span class="font-headline-md">Total TTC</span>
            <span class="font-headline-md text-primary" id="commandetotal">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
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
        return new Intl.NumberFormat('fr-FR').format(valeur).replace(/\s/g, ' '); // Formate correctement les FCFA
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
                // Si l'article a été supprimé par le serveur
                const card = document.querySelector(`.cart-item[data-produit-id="${produitId}"]`);
                if (card) card.remove();
            } else {
                // 1. Mise à jour Quantité et Sous-total ligne
                const qtyInput = document.querySelector(`.qty-input[data-produit-id="${produitId}"]`);
                if (qtyInput) qtyInput.value = data.item.quantite;

                const sousTotalEl = document.querySelector(`.item-sous-total[data-produit-id="${produitId}"]`);
                if (sousTotalEl) sousTotalEl.textContent = formatMontant(data.item.sous_total) + ' FCFA';

                // 2. RECONSTRUCTION DU PRIX (Barré / Rouge / Normal)
                const priceWrapper = document.querySelector(`.js-price-wrapper[data-produit-id="${produitId}"]`);
                if (priceWrapper) {
                    if (data.item.en_promo) {
                        priceWrapper.innerHTML = `
                            <span class="text-xs text-on-surface-variant line-through mb-0.5">${formatMontant(data.item.prix_unitaire_initial)} FCFA</span>
                            <span class="font-headline-md text-base md:text-lg text-status-error font-black leading-none">${formatMontant(data.item.prix_unitaire_final)} FCFA</span>
                        `;
                    } else {
                        priceWrapper.innerHTML = `
                            <span class="font-headline-md text-base md:text-lg text-primary font-black leading-none">${formatMontant(data.item.prix_unitaire_initial)} FCFA</span>
                        `;
                    }
                }

                // 3. RECONSTRUCTION DES MESSAGES PROMOS (Upsell)
                const msgWrapper = document.querySelector(`.js-promo-messages[data-produit-id="${produitId}"]`);
                if (msgWrapper) {
                    if (data.item.quantite_manquante_promo > 0) {
                        msgWrapper.innerHTML = `
                            <div class="text-orange-money text-[10px] md:text-xs font-bold mt-2 bg-orange-money/10 px-2 py-1 rounded inline-block">
                                 Plus que ${data.item.quantite_manquante_promo} articles pour débloquer -${Math.round(data.item.taux_promo_volume)}% !
                            </div>
                        `;
                    } else if (data.item.en_promo) {
                        const eco = data.item.economie_unitaire * data.item.quantite;
                        msgWrapper.innerHTML = `
                            <div class="text-status-success text-[10px] md:text-xs font-bold mt-2 bg-status-success/10 px-2 py-1 rounded inline-block">
                                 Promo appliquée ! Vous économisez ${formatMontant(eco)} FCFA.
                            </div>
                        `;
                    } else {
                        msgWrapper.innerHTML = '';
                    }
                }
            }

            // 4. Mise à jour Totaux Globaux
            document.getElementById('sous_total').dataset.value = data.total;
            document.getElementById('sous_total').textContent = formatMontant(data.total) + ' FCFA';
            document.getElementById('items_count').textContent = data.count;
            recalculerTotal();
        })
        .catch((err) => console.error('Erreur :', err))
        .finally(() => {
            document.querySelectorAll(`.qty-btn[data-produit-id="${produitId}"]`).forEach(b => b.disabled = false);
        });
    }

    // Gestion des clics sur "+" et "-"
    document.querySelectorAll('.qty-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const produitId = btn.getAttribute('data-produit-id');
            const action = btn.getAttribute('data-action');
            const input = document.querySelector(`.qty-input[data-produit-id="${produitId}"]`);
            const min = parseInt(input.getAttribute('min')) || 1;
            const max = parseInt(input.getAttribute('max')) || 99;
            let qty = parseInt(input.value) || min;

            qty = action === 'increase' ? qty + 1 : qty - 1;

            if (qty < min) {
                if (confirm(`La quantité minimum est ${min}. Retirer ce produit du panier ?`)) {
                    updateItem(produitId, 0);
                }
                return;
            }
            if (qty > max) {
                qty = max;
                alert(`Quantité maximale en stock : ${max}`);
            }

            input.value = qty;
            updateItem(produitId, qty);
        });
    });

    // CORRECTION DU BUG DE LA SAISIE MANUELLE
    let debounceQty = {};
    document.querySelectorAll('.qty-input').forEach(function (input) {
        
        input.addEventListener('input', function () {
            const produitId = input.getAttribute('data-produit-id');
            const min = parseInt(input.getAttribute('min')) || 1;
            const max = parseInt(input.getAttribute('max')) || 99;
            
            if (input.value.trim() === '') return; 

            let qty = parseInt(input.value);

            clearTimeout(debounceQty[produitId]);
            debounceQty[produitId] = setTimeout(function () {
                if (isNaN(qty) || qty < min) qty = min;
                if (qty > max) qty = max;
                
                input.value = qty;
                updateItem(produitId, qty);
            }, 800); 
        });

        input.addEventListener('blur', function () {
            if (input.value.trim() === '') {
                const min = parseInt(input.getAttribute('min')) || 1;
                input.value = min;
                updateItem(input.getAttribute('data-produit-id'), min);
            }
        });
    });

    // Suppression d'un article avec l'icône corbeille
    document.querySelectorAll('.remove-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const produitId = btn.getAttribute('data-produit-id');
            if(confirm('Voulez-vous retirer cet article de votre panier ?')) {
                updateItem(produitId, 0);
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
            }
        });
    });

    function recalculerTotal() {
        const sousTotal = parseFloat(document.getElementById('sous_total').dataset.value) || 0;
        const totalEl = document.getElementById('commandetotal');
        if (totalEl) totalEl.textContent = formatMontant(sousTotal) + ' FCFA';
    }

});
</script>
@endsection
