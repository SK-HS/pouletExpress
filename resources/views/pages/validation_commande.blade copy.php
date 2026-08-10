@extends('layouts.master')
@section('content')
<style>
        body { font-family: 'Hanken Grotesk', sans-serif; background-color: #f8faf8; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .success-checkmark-bounce { animation: bounceIn 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); opacity: 1; }
            70% { transform: scale(0.9); }
            100% { transform: scale(1); }
        }
        .confetti-canvas { position: fixed; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 10; }
    </style>
    
    <div class="min-h-screen flex justify-center items-center">
 <!-- Success Content Container -->
<main class="w-full max-w-2xl px-margin-mobile md:px-0 flex flex-col items-center text-center space-y-6 pb-margin-desktop my-auto">
    {{-- <main class="pt-8 pb-32 px-margin-mobile md:px-margin-desktop max-w-7xl mx-auto"> --}}
<div class="relative mt-8">
<div class="w-24 h-24 md:w-32 md:h-32 bg-status-success/10 rounded-full flex items-center justify-center success-checkmark-bounce">
<span class="material-symbols-outlined text-status-success text-6xl md:text-7xl" style="font-variation-settings: 'wght' 700;">
check_circle
</span>
</div>
</div>

<div class="space-y-2">
<h1 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-primary">
Commande confirmée !
</h1>
<p class="font-body-lg text-body-lg text-on-surface-variant">
Merci pour votre confiance. Votre commande est enregistrée et en cours de préparation.
</p>
</div>

<!-- Order Info Bento Grid -->
<div class="w-full grid grid-cols-1 md:grid-cols-2 gap-stack-gap mt-4">
<div class="bg-surface-container-lowest p-card-padding rounded-xl shadow-sm border border-outline-variant flex flex-col items-start justify-center">
<span class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest">Numéro de Commande</span>
<span class="font-label-sm text-label-sm text-primary-container bg-primary-fixed px-3 py-1 rounded-full mt-2">
#{{ $commande->reference }}
</span>
</div>
<div class="bg-surface-container-lowest p-card-padding rounded-xl shadow-sm border border-outline-variant flex flex-col items-start justify-center">
<span class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest">Livraison Estimée</span>
<div class="flex items-center gap-2 mt-2">
<span class="material-symbols-outlined text-secondary">schedule</span>
<span class="font-body-md-bold text-body-md-bold text-on-surface">{{ $commande->creneau === 'matin' ? 'Matin (08h - 12h)' : 'Après-midi (14h - 18h)' }}</span>
</div>
</div>
<div class="bg-surface-container-lowest p-card-padding rounded-xl shadow-sm border border-outline-variant flex flex-col items-start justify-center">
<span class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest">Zone Livraison</span>
<div class="flex items-center gap-2 mt-2">
<span class="material-symbols-outlined text-secondary">home</span>
<span class="font-body-md-bold text-body-md-bold text-on-surface"> {{ $commande->quartier?->commune?->ville?->nom_ville }}, {{ $commande->quartier?->commune?->nom_commune }},{{ $commande->quartier?->nom_quartier }}</span>
</div>
</div>
<div class="bg-surface-container-lowest p-card-padding rounded-xl shadow-sm border border-outline-variant flex flex-col items-start justify-center">
<span class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest">Téléphone Livraison</span>
<div class="flex items-center gap-2 mt-2">
<span class="material-symbols-outlined text-secondary">phone</span>
<span class="font-body-md-bold text-body-md-bold text-on-surface">{{ $commande->telephone_livraison }}</span>
</div>
</div>
</div>

<!-- Order Summary Card -->
<div class="w-full bg-white rounded-xl shadow-sm border border-outline-variant overflow-hidden text-left">
<div class="p-card-padding border-b border-outline-variant bg-surface-container-low">
<h3 class="font-headline-md text-headline-md text-on-surface">Récapitulatif de la commande</h3>
</div>

<div class="p-card-padding space-y-4">
{{-- <div class="flex items-center gap-4">
<div class="w-16 h-16 rounded-lg bg-surface-container overflow-hidden flex-shrink-0">
<img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAlmiHazWG2TEp0ihPiEv_MNRhQG5b9hBbRfYxzIpRbuzWXGYUNxmV6J7DxbrAFq8H9EuJE0pcuX0BcjWqdT_ApiZjK6_ij0H53TZzlvRHCbJGIP3g7JX0XI4vWnHFPvHt2Sk_B1AT__BAMn6mhN5KFI5O3YTC5dATN4xTEGXx_EhjJVglMGNrWwq_GZ4IYHvdSNJl1YiXi9KK2oSeU_A--oT8EWyZp0fPEouCDM65YLpU2yfd16Zf0w_a7qswexLkmIufH0HKnkII" alt="Poulet">
</div>
<div class="flex-grow">
<p class="font-body-md-bold text-body-md-bold text-on-surface">Poulet Bicyclette - Lot de 5</p>
<p class="font-body-md text-body-md text-on-surface-variant">Quantité: 2 lots</p>
</div>
<div class="text-right">
<p class="font-body-md-bold text-body-md-bold text-on-surface">9 000 FCFA</p>
</div>
</div> --}}

@foreach ($commande->detailCommandeClients as $detail)
@if($detail->type === "PRODUIT")
<div class="flex items-center gap-4">
<div class="w-16 h-16 rounded-lg bg-surface-container overflow-hidden flex-shrink-0">
<img class="w-full h-full object-cover" src="/storage/{{$detail->produitFournisseur?->produit?->image }}" alt="{{ $detail->produit?->nom }}">
</div>
<div class="flex-grow">
<p class="font-body-md-bold text-body-md-bold text-on-surface">{{ $detail->produitFournisseur->produit?->nom }}</p>
<p class="font-body-md text-body-md text-on-surface-variant">Quantité: {{ $detail->quantite }}</p>
</div>
<div class="text-right">
<p class="font-body-md-bold text-body-md-bold text-on-surface">{{ number_format($detail->montant) }} FCFA</p>
</div>
</div>
@endif
@endforeach
<hr class="border-outline-variant">
<div class="space-y-2 pt-2">
<div class="flex justify-between font-body-md text-body-md text-on-surface-variant">
<span>Sous-total</span>
<span>{{ number_format($commande->montant_brut) }} FCFA</span>
</div>
@foreach ($commande->detailCommandeClients as $detail)
@if($detail->type == "SERVICE")
<div class="flex justify-between font-body-md text-body-md text-on-surface-variant">
<span>{{$detail->service->designation}}</span>
<span>{{ number_format($detail->montant) }} FCFA</span>
</div>
@endif
@endforeach
<div class="flex justify-between font-headline-md text-headline-md text-on-surface pt-2">
<span>Total Payé</span>
<span class="text-primary">{{ number_format($commande->montant_ttc) }} FCFA</span>
</div>
</div>
</div>
</div>

<!-- Action Buttons -->
<div class="w-full flex flex-col sm:flex-row gap-4 pt-4">
<a href="{{route('Suivi-last-Commande',$commande->id)}}" class="flex-1 h-12 bg-primary text-on-primary font-body-md-bold rounded-xl active:scale-95 transition-all flex items-center justify-center gap-2">
<span class="material-symbols-outlined">local_shipping</span>
Suivre ma commande
</a>
<a href="{{ route('Services') }}" class="flex-1 h-12 bg-surface-container-highest text-on-surface-variant font-body-md-bold rounded-xl active:scale-95 transition-all flex items-center justify-center gap-2">
<span class="material-symbols-outlined">home</span>
Retour au marché
</a>
</div>
</main>
</div>

<canvas class="confetti-canvas" id="confetti"></canvas>
<script>
        window.addEventListener('DOMContentLoaded', () => {
            const canvas = document.getElementById('confetti');
            if(!canvas) return;
            const ctx = canvas.getContext('2d');
            let pieces = [];
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            const colors = ['#00450d', '#2E7D32', '#fcab28', '#FFCC00'];

            for (let i = 0; i < 40; i++) {
                pieces.push({
                    x: Math.random() * canvas.width,
                    y: Math.random() * canvas.height - canvas.height,
                    rotation: Math.random() * 360,
                    color: colors[Math.floor(Math.random() * colors.length)],
                    size: Math.random() * 8 + 4,
                    speed: Math.random() * 3 + 2
                });
            }

            function animate() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                pieces.forEach(p => {
                    p.y += p.speed;
                    p.rotation += 1;
                    ctx.save();
                    ctx.translate(p.x, p.y);
                    ctx.rotate(p.rotation * Math.PI / 180);
                    ctx.fillStyle = p.color;
                    ctx.fillRect(-p.size/2, -p.size/2, p.size, p.size);
                    ctx.restore();
                });
                if(pieces.length > 0) requestAnimationFrame(animate);
            }
            animate();
            setTimeout(() => { pieces = []; ctx.clearRect(0,0,canvas.width,canvas.height); }, 3500);
        });
</script>

@endsection