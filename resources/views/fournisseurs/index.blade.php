@extends('layouts.fournisseur.main')
@section('content')

<main class="lg:ml-64 p-margin-mobile pb-28 md:p-margin-desktop space-y-8 animate-in fade-in duration-500">

    {{-- ── En-tête ──────────────────────────────────────────── --}}
    <section class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
        <div>
            <h3 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">
                Bonjour, {{ $fournisseur->nom_ferme ?? $fournisseur->nom }}
            </h3>
            <p class="font-body-md text-on-surface-variant mt-1 md:mt-2">Voici un aperçu de vos activités.</p>
        </div>

        <div class="flex gap-2 w-full sm:w-auto">
            {{-- Sélecteur de période --}}
            <div class="relative flex-1 sm:flex-none">
                <select id="periodeSelect"
                        class="w-full appearance-none px-4 md:px-6 py-2.5 pr-10 bg-surface-container-highest rounded-full
                               font-body-md-bold text-sm md:text-base cursor-pointer hover:bg-surface-variant transition-all">
                    <option value="7"  {{ $periode == 7 ? 'selected' : '' }}>7 jours</option>
                    <option value="30" {{ $periode == 30 ? 'selected' : '' }}>30 jours</option>
                    <option value="90" {{ $periode == 90 ? 'selected' : '' }}>90 jours</option>
                </select>
                <span class="material-symbols-outlined text-sm absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">calendar_today</span>
            </div>

            <a href="{{ route('Export-Commande-Fournisseur', ['periode' => $periode]) }}"
            {{-- <a href="#" --}}
               class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 md:px-6 py-2.5 bg-primary
                      text-on-primary rounded-full font-body-md-bold shadow-lg shadow-primary/20 hover:opacity-90
                      active:scale-95 transition-all text-sm md:text-base">
                <span class="material-symbols-outlined text-sm md:text-base">download</span>
                <span class="whitespace-nowrap">Exporter</span>
            </a>
        </div>
    </section>

    {{-- ── Métriques ────────────────────────────────────────── --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4 md:gap-gutter">

        {{-- Ventes compte --}}
        <div class="bg-surface-container-lowest p-card-padding rounded-2xl border border-outline-variant hover:border-primary transition-colors group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-primary-container/20 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">payments</span>
                </div>
                @if($evolutionVentes != 0)
                <span class="{{ $evolutionVentes > 0 ? 'text-status-success' : 'text-error' }} font-body-md-bold flex items-center text-xs md:text-sm">
                    {{ $evolutionVentes > 0 ? '+' : '' }}{{ $evolutionVentes }}%
                    <span class="material-symbols-outlined text-xs md:text-sm">
                        {{ $evolutionVentes > 0 ? 'trending_up' : 'trending_down' }}
                    </span>
                </span>
                @endif
            </div>
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-1 text-[10px] md:text-xs">Mon Compte</p>
            <h4 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">
                {{ number_format( $fournisseur->compte, 0, ',', ' ') }}
            </h4>
             <a href="{{route('Demande-Retrait-Fournisseur')}}" class="w-full py-2.5 bg-orange-600 hover:bg-orange-500 hover:bg-primary/30 backdrop-blur-sm rounded-xl text-white font-bold text-sm transition flex justify-center items-center gap-2">
                <span class="material-symbols-outlined text-sm">payments</span>
                Demander retrait
                </a>
        </div>

        {{-- Ventes totales --}}
        <div class="bg-surface-container-lowest p-card-padding rounded-2xl border border-outline-variant hover:border-primary transition-colors group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-primary-container/20 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">payments</span>
                </div>
                @if($evolutionVentes != 0)
                <span class="{{ $evolutionVentes > 0 ? 'text-status-success' : 'text-error' }} font-body-md-bold flex items-center text-xs md:text-sm">
                    {{ $evolutionVentes > 0 ? '+' : '' }}{{ $evolutionVentes }}%
                    <span class="material-symbols-outlined text-xs md:text-sm">
                        {{ $evolutionVentes > 0 ? 'trending_up' : 'trending_down' }}
                    </span>
                </span>
                @endif
            </div>
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-1 text-[10px] md:text-xs">Ventes ({{ $periode }}j) — FCFA</p>
            <h4 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">
                {{ number_format($ventesTotales, 0, ',', ' ') }}
            </h4>
        </div>

        {{-- Commandes en attente --}}
        <div class="bg-surface-container-lowest p-card-padding rounded-2xl border border-outline-variant hover:border-secondary transition-colors group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-secondary-container/20 flex items-center justify-center text-secondary">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">pending_actions</span>
                </div>
                @if($commandesEnAttente > 0)
                <span class="bg-secondary-container text-on-secondary-container px-2 py-0.5 rounded text-[10px] md:text-xs font-bold uppercase">Urgent</span>
                @endif
            </div>
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-1 text-[10px] md:text-xs">Commandes en attente</p>
            <h4 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">
                {{ $commandesEnAttente }}
            </h4>
        </div>

        {{-- Produits actifs --}}
        <div class="bg-surface-container-lowest p-card-padding rounded-2xl border border-outline-variant hover:border-tertiary transition-colors group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-tertiary-container/20 flex items-center justify-center text-tertiary">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">inventory_2</span>
                </div>
            </div>
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-1 text-[10px] md:text-xs">Produits Actifs</p>
            <h4 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">
                {{ $produitsActifs }}
            </h4>
        </div>

        {{-- Note --}}
        <div class="bg-surface-container-lowest p-card-padding rounded-2xl border border-outline-variant hover:border-primary transition-colors group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-primary-container/10 flex items-center justify-center text-secondary">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
                </div>
                @if($nbAvis > 0)
                <p class="text-[10px] md:text-xs text-on-surface-variant">{{ $nbAvis }} avis</p>
                @endif
            </div>
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-1 text-[10px] md:text-xs">Note de la Ferme</p>
            <h4 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">
                @if($noteMoyenne)
                    {{ number_format($noteMoyenne, 1) }} <span class="text-sm md:text-lg font-normal text-on-surface-variant">/ 5.0</span>
                @else
                    <span class="text-lg font-normal text-on-surface-variant">Pas encore noté</span>
                @endif
            </h4>
        </div>
    </section>

    {{-- ── Commandes récentes + Top produits ─────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">

        {{-- Commandes récentes --}}
        <section class="lg:col-span-2 bg-surface-container-lowest rounded-2xl border border-outline-variant overflow-hidden flex flex-col">
            <div class="px-card-padding py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-low/30">
                <h5 class="font-headline-md text-headline-md text-on-surface">Commandes Récentes</h5>
                <a class="text-primary font-body-md-bold hover:underline text-sm" href="{{ route('Fournisseur-Commande') }}">Voir tout</a>
            </div>
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left border-collapse min-w-[600px]">
                    <thead>
                        <tr class="bg-surface-container-low/50">
                            <th class="px-card-padding py-3 font-label-caps text-label-caps text-on-surface-variant whitespace-nowrap">Référence</th>
                            <th class="px-card-padding py-3 font-label-caps text-label-caps text-on-surface-variant">Client</th>
                            <th class="px-card-padding py-3 font-label-caps text-label-caps text-on-surface-variant">Montant</th>
                            <th class="px-card-padding py-3 font-label-caps text-label-caps text-on-surface-variant">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse($commandesRecentes as $cmd)
                        @php
                            $statutLiv = $cmd->commandeLivreur?->statut ?? 'EN_ATTENTE';
                            $badgeConfig = match($statutLiv) {
                                'EN_ATTENTE' => ['bg' => 'bg-secondary-container text-on-secondary-container', 'label' => 'Attente'],
                                'AFFECTEE'   => ['bg' => 'bg-blue-100 text-blue-700', 'label' => 'Affectée'],
                                'RECUPEREE'   => ['bg' => 'bg-amber-100 text-amber-700', 'label' => 'Récupérée'],
                                'EN_ROUTE'   => ['bg' => 'bg-amber-100 text-amber-700', 'label' => 'En livraison'],
                                'LIVREE'     => ['bg' => 'bg-emerald-100 text-emerald-700', 'label' => 'Livrée'],
                                default      => ['bg' => 'bg-slate-100 text-slate-600', 'label' => $statutLiv],
                            };
                        @endphp
                        <tr class="hover:bg-surface-container transition-colors group">
                            <td class="px-card-padding py-4 font-label-sm text-label-sm text-primary">#{{ $cmd->reference }}</td>
                            <td class="px-card-padding py-4 font-body-md-bold">{{ $cmd->client?->nom ?? '—' }}</td>
                            <td class="px-card-padding py-4 text-sm whitespace-nowrap font-bold">
                                {{ number_format($cmd->montant_ttc, 0, ',', ' ') }} FCFA
                            </td>
                            <td class="px-card-padding py-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold {{ $badgeConfig['bg'] }} uppercase tracking-tight">
                                    {{ $badgeConfig['label'] }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-card-padding py-10 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined text-3xl mb-2 block">inventory_2</span>
                                Aucune commande sur cette période.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Top produits vendus --}}
        <aside class="space-y-6">
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant p-card-padding">
                <h5 class="font-headline-md text-headline-md text-on-surface mb-6">Produits les plus vendus</h5>

                @forelse($topProduits as $index => $produit)
                <div class="flex items-center gap-3 {{ !$loop->last ? 'mb-4' : '' }}">
                    <span class="w-6 h-6 shrink-0 rounded-full bg-primary/10 text-primary font-bold text-xs flex items-center justify-center">
                        {{ $index + 1 }}
                    </span>

                    <div class="w-10 h-10 rounded-lg bg-surface-container-high overflow-hidden shrink-0">
                        @if($produit->image)
                        <img src="/storage/{{ $produit->image }}" alt="{{ $produit->nom }}" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-sm">inventory_2</span>
                        </div>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="font-body-md-bold text-sm truncate">{{ $produit->nom }}</p>
                        <div class="w-full bg-surface-container-high h-1.5 rounded-full overflow-hidden mt-1">
                            <div class="bg-primary h-full rounded-full"
                                 style="width: {{ round(($produit->total_quantite / $maxQuantiteTop) * 100) }}%;"></div>
                        </div>
                    </div>

                    <span class="text-xs font-bold text-on-surface-variant shrink-0">
                        {{ $produit->total_quantite }} vendus
                    </span>
                </div>
                @empty
                <p class="text-sm text-on-surface-variant text-center py-6">Aucune vente sur cette période.</p>
                @endforelse
            </div>

            {{-- Notifications --}}
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant p-card-padding">
                <h5 class="font-headline-md text-headline-md text-on-surface mb-6 flex items-center justify-between">
                    Notifications
                    @if($commandesEnAttente > 0)
                    <span class="w-6 h-6 bg-error text-on-error text-[10px] flex items-center justify-center rounded-full">
                        {{ $commandesEnAttente }}
                    </span>
                    @endif
                </h5>
                <div class="space-y-4">
                    @if($commandesEnAttente > 0)
                    <div class="flex gap-4 p-3 rounded-xl bg-secondary-container/5 border-l-4 border-secondary">
                        <div class="w-10 h-10 rounded-full bg-secondary/10 flex-shrink-0 flex items-center justify-center text-secondary">
                            <span class="material-symbols-outlined">pending_actions</span>
                        </div>
                        <div>
                            <p class="font-body-md-bold text-sm leading-snug">
                                {{ $commandesEnAttente }} commande(s) en attente de traitement
                            </p>
                            <a href="{{ route('Fournisseur-Commande') }}" class="text-xs text-primary font-bold hover:underline mt-1 inline-block">
                                Voir les commandes
                            </a>
                        </div>
                    </div>
                    @else
                    <p class="text-sm text-on-surface-variant text-center py-4">Aucune nouvelle notification.</p>
                    @endif
                </div>
            </div>
        </aside>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('periodeSelect').addEventListener('change', function () {
        const url = new URL(window.location.href);
        url.searchParams.set('periode', this.value);
        window.location.href = url.toString();
    });
});
</script>

@endsection
