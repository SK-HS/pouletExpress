@extends('layouts.livreur.main')
@section('content')
<main class="max-w-3xl mx-auto px-margin-mobile md:px-margin-desktop py-8">

    <div class="flex items-center justify-between mb-8">
        <h1 class="font-headline-lg text-headline-lg text-primary">Commandes disponibles</h1>
        <span class="flex items-center gap-2 text-sm text-on-surface-variant">
            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
            Mise à jour automatique
        </span>
    </div>

    @if (session('success'))
        <div class="bg-primary/10 border border-primary text-primary rounded-xl p-4 mb-6 text-sm">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-error/10 border border-error text-error rounded-xl p-4 mb-6 text-sm">{{ session('error') }}</div>
    @endif

    <div id="commandesContainer" class="space-y-4">
        @forelse ($commandes as $cmd)
        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant p-5 commande-card" data-id="{{ $cmd['id'] }}">
            <div class="flex items-center justify-between mb-3">
                <span class="font-body-md-bold text-primary">{{ $cmd['reference'] }}</span>
                <span class="text-xs font-bold px-3 py-1 rounded-full bg-secondary-container text-on-secondary-container">
                    {{ $cmd['distance_km'] }} km
                </span>
            </div>

            <div class="grid grid-cols-2 gap-3 text-sm mb-4">
                <div>
                    <p class="text-on-surface-variant">Zone</p>
                    <p class="font-body-md-bold">{{ $cmd['quartier'] }}</p>
                </div>
                <div>
                    <p class="text-on-surface-variant">Articles</p>
                    <p class="font-body-md-bold">{{ $cmd['nb_articles'] }}</p>
                </div>
                <div>
                    <p class="text-on-surface-variant">Créneau</p>
                    <p class="font-body-md-bold">{{ $cmd['creneau'] === 'matin' ? 'Matin (08h-12h)' : 'Après-midi (14h-18h)' }}</p>
                </div>
                <div>
                    <p class="text-on-surface-variant">Montant</p>
                    <p class="font-body-md-bold text-primary">{{ number_format($cmd['total']) }} FCFA</p>
                </div>
            </div>

            <p class="text-xs text-on-surface-variant mb-4">Reçue {{ $cmd['created_at'] }}</p>

            <form method="POST" action="{{ route('Livreur-Accepter-Commande', $cmd['id']) }}" class="accept-form">
                @csrf
                <button type="submit" class="w-full bg-primary text-on-primary font-body-md-bold py-3 rounded-xl shadow-md hover:bg-primary-container transition-all">
                    Accepter cette commande
                </button>
            </form>
        </div>
        @empty
        <div class="text-center py-16 text-on-surface-variant" id="emptyState">
            <span class="material-symbols-outlined text-5xl mb-4 block">inventory_2</span>
            Aucune commande disponible dans votre zone pour le moment.
        </div>
        @endforelse
    </div>

</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const container = document.getElementById('commandesContainer');

    // Intercepte la soumission du formulaire "Accepter" en AJAX
    container.addEventListener('submit', function (e) {
        if (!e.target.classList.contains('accept-form')) return;
        e.preventDefault();

        const form = e.target;
        const card = form.closest('.commande-card');
        const submitBtn = form.querySelector('button');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Traitement...';

        fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.success) {
                    card.style.opacity = '0.5';
                    submitBtn.textContent = 'Commande acceptée !';
                    setTimeout(() => window.location.href = "{{ route('livreur.mes-livraisons') }}", 1000);
                } else {
                    alert(data.message);
                    card.remove(); // la commande n'est plus disponible, on la retire de la liste
                }
            })
            .catch(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Accepter cette commande';
            });
    });

    // ============ POLLING : rafraîchit la liste toutes les 8 secondes ============
    function rafraichirListe() {
        fetch("{{ route('livreur.polling') }}", {
            headers: { 'Accept': 'application/json' },
        })
            .then((res) => res.json())
            .then((data) => {
                const idsActuels = Array.from(container.querySelectorAll('.commande-card')).map(c => c.dataset.id);
                const nouveauxIds = data.commandes.map(c => String(c.id));

                // Retire les commandes qui ne sont plus disponibles (acceptées par un autre livreur)
                idsActuels.forEach(id => {
                    if (!nouveauxIds.includes(id)) {
                        const card = container.querySelector(`.commande-card[data-id="${id}"]`);
                        if (card) card.remove();
                    }
                });

                // Ajoute les nouvelles commandes qui viennent d'apparaître
                data.commandes.forEach(cmd => {
                    if (!idsActuels.includes(String(cmd.id))) {
                        // Rechargement simple de la page pour afficher la nouvelle carte
                        // (évite de dupliquer tout le template HTML en JS)
                        location.reload();
                    }
                });

                const emptyState = document.getElementById('emptyState');
                if (data.commandes.length > 0 && emptyState) {
                    emptyState.remove();
                }
            });
    }

    setInterval(rafraichirListe, 8000);
});
</script>

@endsection
