@extends('layouts.master')
@section('content')

<main class="mt-8 px-margin-mobile md:px-margin-desktop max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 ">

<!-- Cart Items Section -->
<div class="lg:col-span-7 flex flex-col  gap-gutter" id="cartItemsContainer">
<h1 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-primary mb-2">Votre Panier</h1>

@foreach ($items as $item)
<div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden border border-outline-variant cart-item" data-produit-id="{{ $item['produit']->id }}" data-prix="{{ $item['produit']->prix }}">
<div class="bg-surface-container px-card-padding py-3 flex items-center justify-between border-b border-outline-variant">
<div class="flex items-center gap-2 cursor-pointer" >
<span class="material-symbols-outlined text-primary">agriculture</span>
<span class="font-body-md-bold text-body-md-bold hover:underline">  {{ $item['produit']->fournisseur?->nom }}</span>
</div>
<span class="text-label-sm font-label-sm text-on-surface-variant">Livraison directe possible</span>
</div>
<div class="p-card-padding flex flex-col gap-stack-gap">
<div class="flex gap-4">
<div class="w-20 h-20 bg-surface-container-high rounded-lg overflow-hidden flex-shrink-0 cursor-pointer" onclick="window.location.href='{{ route('Detail-Produit', $item['produit']->id) }}'">
<img class="w-full h-full object-cover" src="/storage/{{ $item['produit']->produit?->image }}" alt="{{ $item['produit']->produit?->nom }}">
</div>
<div class="flex-grow flex flex-col justify-between">
<div class="flex justify-between items-start">
<div>
<h3 class="font-body-md-bold text-body-md-bold cursor-pointer hover:text-primary" onclick="window.location.href='{{ route('Detail-Produit', $item['produit']->id) }}'">{{ $item['produit']->produit?->nom }}</h3>
<p class="text-label-sm font-label-sm text-on-surface-variant">Poids approx.{{ number_format($item['produit']->taille?->taille) }}kg</p>
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
       min="1">
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
<span class="text-on-surface-variant font-body-md text-body-md">Sous-total  {{ $item['produit']->fournisseur?->nom }}</span>
<span class="font-body-md-bold text-body-md-bold text-primary item-sous-total" data-produit-id="{{ $item['produit']->id }}"> {{ number_format($item['sous_total']) }} FCFA</span>
</div>
</div>
</div>
@endforeach

</div>

<!-- Summary & Checkout Section -->
<div class="lg:col-span-5">

<div class="space-y-2 flex items-center gap-3">
{{-- <input class="h-6 w-6 rounded-lg border-2 border-outline-variant focus:border-primary focus:ring-0 transition-all" type="checkbox" name="souhaite_livraison" id="souhaite_livraison" value="1">
<label class="font-body-md-bold text-on-surface-variant" for="souhaite_livraison">Je souhaite être livré</label> --}}
</div>

<div class="space-y-2 mt-4" style="display:none;" id="bloc_livraison">
<label class="font-body-md-bold text-on-surface-variant ml-1" for="zone_livraison"> Zone de Livraison </label>
<select class="w-full h-12 px-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-0 transition-all font-body-md bg-white" name="zone_livraison" id="zone_livraison">
 <option value="" data-frais="0">Sélectionner une zone</option>
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

<div class="bg-surface-container-low rounded-2xl p-card-padding border border-outline-variant sticky top-24 mt-6">
<h2 class="font-headline-md text-headline-md text-primary mb-6">Récapitulatif</h2>
<div class="flex flex-col gap-4 mb-6">
<div class="flex justify-between text-body-md font-body-md">
<span class="text-on-surface-variant">Sous-total (<span id="items_count">{{ count($items) }}</span> articles)</span>
<span id="sous_total" data-value="{{ $total }}">{{ number_format($total) }} FCFA</span>
</div>
<div class="flex justify-between text-body-md font-body-md">
<span class="text-on-surface-variant">Frais de livraison</span>
<span class="text-status-info" id="frais_livraison_display" data-value="0">Non sélectionnés</span>
</div>
</div>
<div class="border-t-2 border-dashed border-outline-variant pt-6 mb-8 flex justify-between items-center">
<span class="font-headline-md text-headline-md">Total</span>
<span class="font-headline-md text-headline-md text-primary" id="commandetotal">{{ number_format($total) }} FCFA</span>
</div>

<input type="hidden" name="zone_livraison_id" id="zone_livraison_id" value="">

<a href="{{route('Finaliser-Commande')}}" class="w-full bg-primary text-on-primary font-body-md-bold text-lg py-4 rounded-xl flex items-center justify-center gap-3 shadow-lg hover:bg-primary-container transition-all">
<span>Passer la commande</span>
<span class="material-symbols-outlined">arrow_forward</span>
</a>
</div>
</div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let fraisLivraison = 0;

    // ============ MISE A JOUR DE LA QUANTITE (sans recharger la page) ============

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
                if (!data.success) return;

                if (data.item === null) {
                    // Le produit a été retiré (quantité à 0) -> on enlève la carte du DOM
                    const card = document.querySelector(`.cart-item[data-produit-id="${produitId}"]`);
                    if (card) card.remove();
                } else {
                    // Met à jour uniquement le sous-total de cet item et la quantité affichée
                    const sousTotalEl = document.querySelector(`.item-sous-total[data-produit-id="${produitId}"]`);
                    if (sousTotalEl) {
                        sousTotalEl.textContent = formatMontant(data.item.sous_total) + ' FCFA';
                    }
                    const qtyInput = document.querySelector(`.qty-input[data-produit-id="${produitId}"]`);
                    if (qtyInput) qtyInput.value = data.item.quantite;
                }

                // Met à jour le sous-total global et le nombre d'articles
                document.getElementById('sous_total').dataset.value = data.total;
                document.getElementById('sous_total').textContent = formatMontant(data.total) + ' FCFA';
                document.getElementById('items_count').textContent = data.count;

                recalculerTotal();
            })
            .catch((err) => console.error('Erreur mise à jour panier :', err));
    }

    function formatMontant(valeur) {
        return new Intl.NumberFormat('fr-FR').format(valeur);
    }

    // Boutons +/-
    document.querySelectorAll('.qty-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const produitId = btn.getAttribute('data-produit-id');
            const action = btn.getAttribute('data-action');
            const input = document.querySelector(`.qty-input[data-produit-id="${produitId}"]`);
            let qty = parseInt(input.value) || 1;

            qty = action === 'increase' ? qty + 1 : qty - 1;

            if (qty <= 0) {
                if (confirm('Retirer ce produit du panier ?')) {
                    updateItem(produitId, 0);
                }
                return;
            }

            input.value = qty;
            updateItem(produitId, qty);
        });
    });

    // Saisie manuelle de la quantité au clavier (avec debounce pour ne pas spammer le serveur)
    let debounceQty = {};
    document.querySelectorAll('.qty-input').forEach(function (input) {
        input.addEventListener('input', function () {
            const produitId = input.getAttribute('data-produit-id');
            let qty = parseInt(input.value);

            clearTimeout(debounceQty[produitId]);
            debounceQty[produitId] = setTimeout(function () {
                if (isNaN(qty) || qty < 1) {
                    qty = 1;
                    input.value = 1;
                }
                updateItem(produitId, qty);
            }, 600);
        });
    });

    // Retirer un produit
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
                    if (data.success) {
                        const card = document.querySelector(`.cart-item[data-produit-id="${produitId}"]`);
                        if (card) card.remove();

                        document.getElementById('sous_total').dataset.value = data.total;
                        document.getElementById('sous_total').textContent = formatMontant(data.total) + ' FCFA';
                        document.getElementById('items_count').textContent = data.count;

                        recalculerTotal();
                    }
                });
        });
    });

    // ============ GESTION DE LA LIVRAISON ============

    const checkboxLivraison = document.getElementById('souhaite_livraison');
    const blocLivraison = document.getElementById('bloc_livraison');
    const selectZone = document.getElementById('zone_livraison');
    const fraisDisplay = document.getElementById('frais_livraison_display');
    const zoneHiddenInput = document.getElementById('zone_livraison_id');

    checkboxLivraison.addEventListener('change', function () {
        if (this.checked) {
            blocLivraison.style.display = 'block';
        } else {
            blocLivraison.style.display = 'none';
            selectZone.value = '';
            fraisLivraison = 0;
            fraisDisplay.textContent = 'Non sélectionnés';
            fraisDisplay.dataset.value = 0;
            zoneHiddenInput.value = '';
            recalculerTotal();
        }
    });

    selectZone.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const frais = parseFloat(selectedOption.getAttribute('data-frais')) || 0;

        fraisLivraison = frais;
        zoneHiddenInput.value = this.value;

        if (this.value === '') {
            fraisDisplay.textContent = 'Non sélectionnés';
        } else {
            fraisDisplay.textContent = formatMontant(frais) + ' FCFA';
        }
        fraisDisplay.dataset.value = frais;

        recalculerTotal();
    });

    // ============ RECALCUL DU TOTAL GENERAL (sous-total produits + frais livraison) ============

    function recalculerTotal() {
        const sousTotal = parseFloat(document.getElementById('sous_total').dataset.value) || 0;
        const frais = parseFloat(document.getElementById('frais_livraison_display').dataset.value) || 0;
        const total = sousTotal + frais;

        document.getElementById('commandetotal').textContent = formatMontant(total) + ' FCFA';
    }
});
</script>

@endsection
