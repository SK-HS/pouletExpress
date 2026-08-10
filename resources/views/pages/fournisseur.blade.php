@extends('layouts.master')
@section('content')

<main class="pb-20">
<!-- Hero Section -->
<section class="relative w-full h-[350px] overflow-hidden">
<div class="absolute inset-0 bg-cover bg-center" style="background-image: url('/storage/{{ $fournisseurs->image }}')">
<div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
</div>
<div class="relative max-w-7xl mx-auto h-full px-margin-desktop flex flex-col justify-end pb-8">
<div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
<div class="flex items-center gap-6">
<div class="w-24 h-24 md:w-32 md:h-32 rounded-2xl bg-white p-2 shadow-lg border-2 border-primary-fixed overflow-hidden">
<img class="w-full h-full object-cover rounded-xl" src="/storage/{{ $fournisseurs->image_ferme }}" alt="{{ $fournisseurs->nom_ferme }}">
</div>
<div class="text-white">
<div class="flex items-center gap-3 mb-2">
<h1 class="font-headline-lg text-headline-lg">{{ $fournisseurs->nom }} {{ $fournisseurs->nom_ferme }}</h1>
<span class="bg-status-success text-white text-xs px-3 py-1 rounded-full flex items-center gap-1 font-bold uppercase tracking-wider">
<span class="material-symbols-outlined text-sm">verified</span> Certifié
</span>
</div>
<p class="font-body-md text-white/90 flex items-center gap-2">
<span class="material-symbols-outlined text-[18px]">location_on</span>{{ $fournisseurs->quartier->commune->ville->nom_ville }} {{ $fournisseurs->quartier->commune->nom_commune }} {{ $fournisseurs->quartier->nom_quartier }}
</p>
</div>
</div>
<div class="flex gap-4">
<a href="{{route('Contact')}}" class="bg-primary text-on-primary px-6 py-3 rounded-xl font-body-md-bold hover:bg-primary-container transition-colors flex items-center gap-2">
<span class="material-symbols-outlined">mail</span> Contacter Nous
</a>
</div>
</div>
</div>
</section>

<div class="max-w-7xl mx-auto px-margin-desktop mt-8 grid grid-cols-1 lg:grid-cols-12 gap-gutter">
<div class="lg:col-span-8 space-y-8">
<article class="bg-surface-container-lowest rounded-3xl p-8 border border-outline-variant/30 shadow-sm">
<h2 class="font-headline-md text-headline-md text-primary mb-4 flex items-center gap-3">
<span class="material-symbols-outlined text-primary text-3xl">agriculture</span> À propos de l'éleveur
</h2>
<p class="text-on-surface-variant font-body-md leading-relaxed mb-6">
{{ $fournisseurs->description }}
</p>
<div class="grid grid-cols-3 gap-4">
<div class="bg-surface-container-low p-4 rounded-2xl text-center">
<span class="material-symbols-outlined text-primary text-3xl mb-1">eco</span>
<p class="font-body-md-bold text-on-surface">100% Bio</p>
</div>
<div class="bg-surface-container-low p-4 rounded-2xl text-center">
<span class="material-symbols-outlined text-primary text-3xl mb-1">star</span>
<p class="font-body-md-bold text-on-surface">Note: 4.9/5</p>
</div>
<div class="bg-surface-container-low p-4 rounded-2xl text-center">
<span class="material-symbols-outlined text-primary text-3xl mb-1">local_shipping</span>
<p class="font-body-md-bold text-on-surface">Livraison 24h</p>
</div>
</div>
</article>

<!-- Available Products from Farmer -->
<section>
<h2 class="font-headline-md text-headline-md text-on-surface mb-6">Produits disponibles chez cet éleveur</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<!-- Product 1 -->
 @foreach ($produits as $produit )

<div class="bg-surface-container-lowest rounded-3xl overflow-hidden border border-outline-variant/30 p-4 flex gap-4 cursor-pointer hover:shadow-md transition-shadow" onclick="window.location.href='{{ route('Detail-Produit', $produit->id) }}'">
<img class="w-28 h-28 object-cover rounded-2xl" src="/storage/{{ $produit->produit?->image }}" alt="{{$produit->produit?->nom}}">
<div class="flex flex-col justify-between flex-1">
<div>
<h3 class="font-body-md-bold text-on-surface mb-1">{{ $produit->produit?->nom }}</h3>
<p class="text-xs text-on-surface-variant">{{ $produit->description }}</p>
<p class="text-xs text-on-surface-variant">{{ $produit->prix }} FCFA / unité</p>
</div>
<div class="flex items-center justify-between">
<span class="text-primary font-headline-md">{{ $produit->prix }}FCFA</span>
<a href="{{ route('Detail-Produit', $produit->id) }}" class="bg-primary text-on-primary px-3 py-1.5 rounded-xl text-xs font-bold">Voir</a>
</div>
</div>
</div>
@endforeach
<!-- Product 2 -->
<div class="bg-surface-container-lowest rounded-3xl overflow-hidden border border-outline-variant/30 p-4 flex gap-4 cursor-pointer hover:shadow-md transition-shadow" onclick="window.location.href='detail-produit.html?id=pintades'">
<img class="w-28 h-28 object-cover rounded-2xl" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAXqTmyVb-HMbWZxqtdcwPRrZVqzNhejST7_4sKmNTRvqbUvtEoiuclVl2drqRR12Lbe-85JcPn7yUdQ24vUAg7_6eMbzIiePGfobp26g3rG5d_Gbk2Fvw5TxdxSQbsA-iqBD5OoW-wgGSYnFVAO4is25--5FhNFMoqXgUFUGVDBiB9-SNxHpj3NHkxBaMlXfHSNlHL9SCYmM1CnkzTwtuE6_P0_Vx05JvnOdw9HQcpLdPAFH2oCTrvV5HbAHY7A5WOfDxTHzoBarw" alt="Pintades">
<div class="flex flex-col justify-between flex-1">
<div>
<h3 class="font-body-md-bold text-on-surface mb-1">Lot de 2 Pintades</h3>
<p class="text-xs text-on-surface-variant">Sujets adultes • Elevage bio</p>
</div>
<div class="flex items-center justify-between">
<span class="text-primary font-headline-md">12.000 FCFA</span>
<a href="detail-produit.html?id=pintades" class="bg-primary text-on-primary px-3 py-1.5 rounded-xl text-xs font-bold">Voir</a>
</div>
</div>
</div>
</div>
</section>
</div>

<!-- Sidebar Stats -->
<aside class="lg:col-span-4 space-y-6">
<div class="bg-surface-container-lowest rounded-3xl p-6 border border-outline-variant/30">
<h3 class="font-body-md-bold text-primary mb-4">Informations de la ferme</h3>
<ul class="space-y-4 text-sm">
<li class="flex items-center justify-between border-b border-outline-variant/20 pb-3">
<span class="text-on-surface-variant">Capacité de production:</span>
<span class="font-bold">{{ $fournisseurs->capacite_ferme }} sujets/mois</span>
</li>
<li class="flex items-center justify-between border-b border-outline-variant/20 pb-3">
<span class="text-on-surface-variant">Type de produts:</span>
@if($fournisseurs->type_produit)
@foreach ($fournisseurs->type_produit as $typeproduit )
<span class="font-bold">{{ $typeproduit }} @if(!$loop->last) ; @endif </span>
@endforeach
@endif
</li>
<li class="flex items-center justify-between border-b border-outline-variant/20 pb-3">
<span class="text-on-surface-variant">Taux de satisfaction:</span>
<span class="font-bold text-status-success">98%</span>
</li>
<li class="flex items-center justify-between border-b border-outline-variant/20 pb-3">
<span class="text-on-surface-variant">Paiements acceptés:</span>
<span class="font-bold">Espèces,Wave, Orange, MTN, Moov</span>
</li>
</ul>
<a href="contact.html" class="mt-6 w-full bg-primary text-on-primary py-3 rounded-xl font-bold flex items-center justify-center gap-2 block text-center">
Envoyer un message
</a>
</div>
</aside>
</div>
</main>
@endsection

