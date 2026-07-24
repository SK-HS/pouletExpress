@extends('layouts.master')
@section('content')

 
 <main>
<!-- Hero Section -->
<section class="relative overflow-hidden pt-12 pb-24 md:pt-24 md:pb-32">
<div class="max-w-7xl mx-auto px-margin-mobile md:px-margin-desktop grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
<div class="z-10">
<span class="font-label-caps text-label-caps text-secondary mb-4 block tracking-[0.2em] uppercase">Notre Histoire</span>
<h1 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg mb-6 leading-tight">Connecter le terroir à votre table, <span class="text-gradient">sans intermédiaire.</span></h1>
<p class="font-body-lg text-body-lg text-on-surface-variant mb-8 max-w-lg">
PouletExpress est née d'une vision simple : rendre la volaille locale de haute qualité accessible à tous tout en garantissant une rémunération juste pour nos éleveurs.
</p>
<div class="flex flex-wrap gap-4">
<a href="produits.html" class="bg-primary text-on-primary px-8 py-4 rounded-xl font-body-md-bold hover:opacity-90 active:scale-95 transition-all shadow-lg">Découvrir nos fermes</a>
<a href="contact.html" class="bg-surface text-primary border-2 border-primary px-8 py-4 rounded-xl font-body-md-bold hover:bg-primary-fixed-dim/10 active:scale-95 transition-all">Nous rejoindre</a>
</div>
</div>
<div class="relative">
<div class="absolute -top-10 -right-10 w-64 h-64 bg-primary-fixed opacity-20 rounded-full blur-3xl"></div>
<div class="relative z-10 rounded-[2rem] overflow-hidden shadow-2xl transform rotate-2">
<img class="w-full aspect-[4/5] object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAUmvhz5xd_Q4ATR-gDj2Lpxa2tdkxlq_DpT_oSp2PZVZ8qJMeCGHW4FVceU8-1nfVkn5Jyho-GQ7OcOBfV8TMmp3pVpSGspoUiJs9ISgrfEmZP7_fm23uFvi6OExn9sUGQTLmACrY-3AXmcdsprMd6UIbPoV-nycOOAyYItWoEa54Lg6n2mQjjU3JCV4fTspkW76I6HSWAJgfnNSyf1xKtK-ZKTLbxhmvsviO9H0_IGs6sLP-s7y9RMGMSenQq9e3h0O2yu2kq0C8" alt="Éleveurs partenaires">
</div>
<div class="absolute -bottom-6 -left-6 z-20 bg-surface-container-lowest p-card-padding rounded-2xl shadow-xl flex items-center gap-4 border border-outline-variant">
<div class="bg-status-success text-on-primary w-12 h-12 rounded-full flex items-center justify-center">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">verified</span>
</div>
<div>
<p class="font-body-md-bold text-on-surface">+500 Éleveurs</p>
<p class="font-label-sm text-on-surface-variant text-[10px]">Partenaires certifiés</p>
</div>
</div>
</div>
</div>
</section>

<!-- Mission Section -->
<section class="bg-surface-container-low py-24">
<div class="max-w-7xl mx-auto px-margin-mobile md:px-margin-desktop text-center mb-16">
<span class="font-label-caps text-label-caps text-secondary tracking-widest">VALEURS</span>
<h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg mt-2">Notre Mission</h2>
</div>
<div class="max-w-7xl mx-auto px-margin-mobile md:px-margin-desktop grid grid-cols-1 md:grid-cols-3 gap-gutter">
<div class="bg-surface-container-lowest p-8 rounded-[2rem] shadow-sm hover:shadow-md transition-shadow group flex flex-col items-start border border-outline-variant/30">
<div class="bg-primary-fixed-dim/20 text-primary w-14 h-14 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
<span class="material-symbols-outlined text-3xl">temp_preferences_eco</span>
</div>
<h3 class="font-headline-md text-headline-md mb-3">Fraîcheur Absolue</h3>
<p class="font-body-md text-on-surface-variant">Du producteur au consommateur en moins de 24 heures. Nous optimisons chaque étape pour préserver les qualités nutritionnelles.</p>
</div>
<div class="bg-surface-container-lowest p-8 rounded-[2rem] shadow-sm hover:shadow-md transition-shadow group flex flex-col items-start border border-outline-variant/30">
<div class="bg-secondary-fixed/30 text-secondary w-14 h-14 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
<span class="material-symbols-outlined text-3xl">distance</span>
</div>
<h3 class="font-headline-md text-headline-md mb-3">Circuit Court</h3>
<p class="font-body-md text-on-surface-variant">En éliminant les intermédiaires inutiles, nous réduisons l'empreinte carbone et soutenons l'économie locale directe.</p>
</div>
<div class="bg-surface-container-lowest p-8 rounded-[2rem] shadow-sm hover:shadow-md transition-shadow group flex flex-col items-start border border-outline-variant/30">
<div class="bg-primary/10 text-primary w-14 h-14 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
<span class="material-symbols-outlined text-3xl">handshake</span>
</div>
<h3 class="font-headline-md text-headline-md mb-3">Équité Totale</h3>
<p class="font-body-md text-on-surface-variant">Les éleveurs fixent leurs propres prix. Nous garantissons une transparence totale sur les marges et les frais.</p>
</div>
</div>
</section>

<!-- Vision Section -->
<section class="py-24 overflow-hidden">
<div class="max-w-7xl mx-auto px-margin-mobile md:px-margin-desktop grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
<div class="order-2 md:order-1 relative">
<div class="relative z-10 rounded-[2rem] overflow-hidden shadow-2xl border-4 border-surface">
<img class="w-full aspect-video object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBXNmw3YQYQDfBJXjeR5FNDsO53eadfWH1fynxZpQKQbVUOYNkUyStbHS0nXu3WDNvvec0I12SzKS583izi4ZWdnGTwhPATx2_cHOTbtC2NtlmQHoNb81jd1ncA1xW4pLHjJxI1Fcamlka3Pi0Ev7jGTU2IQegDsx-862zuRyEB_7Lupbuz6jnrffLdEh_0wXrRFeTKwQ9Lta4WL7oQ1u5bOihydYcNr78Ydvl-6AOqcSGhoFzPFVfqZMk-4gl1CGeCrzyKhfZjhTI" alt="Agriculture numérique">
</div>
</div>
<div class="order-1 md:order-2">
<span class="font-label-caps text-label-caps text-secondary mb-4 block tracking-[0.2em] uppercase">Vision 2030</span>
<h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg mb-6 leading-tight">L'avenir de l'agriculture numérique en Afrique</h2>
<p class="font-body-lg text-body-lg text-on-surface-variant mb-6">
Nous croyons que la technologie peut transformer les défis logistiques en opportunités de croissance. Notre vision est de bâtir le plus grand écosystème agricole digitalisé du continent.
</p>
<ul class="space-y-4">
<li class="flex items-start gap-3">
<span class="material-symbols-outlined text-status-success mt-1">check_circle</span>
<span class="font-body-md text-on-surface">Digitalisation complète du suivi sanitaire des élevages.</span>
</li>
<li class="flex items-start gap-3">
<span class="material-symbols-outlined text-status-success mt-1">check_circle</span>
<span class="font-body-md text-on-surface">Logistique prédictive par IA pour zéro gaspillage.</span>
</li>
<li class="flex items-start gap-3">
<span class="material-symbols-outlined text-status-success mt-1">check_circle</span>
<span class="font-body-md text-on-surface">Inclusion financière pour tous les petits producteurs.</span>
</li>
</ul>
</div>
</div>
</section>

<!-- Why Choose Us -->
<section class="bg-primary text-on-primary py-24 rounded-t-[3rem] -mt-10">
<div class="max-w-7xl mx-auto px-margin-mobile md:px-margin-desktop grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
<div>
<h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg mb-8">Pourquoi nous choisir ?</h2>
<div class="space-y-10">
<div class="flex gap-6">
<div class="flex-shrink-0 w-12 h-12 bg-white/10 rounded-full flex items-center justify-center border border-white/20">
<span class="material-symbols-outlined text-primary-fixed-dim">qr_code_2</span>
</div>
<div>
<h4 class="font-headline-md text-headline-md mb-2">Traçabilité QR</h4>
<p class="font-body-md text-on-primary/80">Scannez le code sur votre emballage pour voir le nom de l'éleveur, la date de naissance de la volaille et son régime alimentaire.</p>
</div>
</div>
<div class="flex gap-6">
<div class="flex-shrink-0 w-12 h-12 bg-white/10 rounded-full flex items-center justify-center border border-white/20">
<span class="material-symbols-outlined text-primary-fixed-dim">workspace_premium</span>
</div>
<div>
<h4 class="font-headline-md text-headline-md mb-2">Qualité Premium</h4>
<p class="font-body-md text-on-primary/80">Tous nos partenaires respectent une charte stricte : élevage en plein air, alimentation naturelle et sans hormones de croissance.</p>
</div>
</div>
<div class="flex gap-6">
<div class="flex-shrink-0 w-12 h-12 bg-white/10 rounded-full flex items-center justify-center border border-white/20">
<span class="material-symbols-outlined text-primary-fixed-dim">support_agent</span>
</div>
<div>
<h4 class="font-headline-md text-headline-md mb-2">Accompagnement</h4>
<p class="font-body-md text-on-primary/80">Nous ne sommes pas qu'une plateforme. Nous fournissons conseils vétérinaires et support technique à tous nos éleveurs.</p>
</div>
</div>
</div>
</div>
<div class="relative h-[500px] rounded-[2rem] overflow-hidden">
<img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB9RlztSDLhKGw0OFkAk8NzCZFiI8mkrhgUKpNes39bpga08WLZIONHzq1y_WBo_aAfTPg-m-F4ZGgNLlWQo1eZyToCxkEKGwEmWvj5ZhKt_AjFPdbEmr1QjdZarLyf0LMIsAxckRO443kwUoNL0_Ggzx7J6ZawtQlQTfrwKBIVyzNntVcA-7tuh6kXejfn-WOPYNOnIZh-0Jmm84jLR-JedIpLkwW0lVS1Ol0q3FI6TGqLeTQuaA4zFfYW2Cx_CGoURhLwqHCOt4A" alt="Qualité premium">
<div class="absolute inset-0 bg-gradient-to-t from-primary/80 to-transparent"></div>
<div class="absolute bottom-8 left-8 right-8 text-center p-6 backdrop-blur-md bg-white/10 rounded-2xl border border-white/20">
<p class="font-headline-md text-headline-md">"Garantir le meilleur, pour vous et pour eux."</p>
</div>
</div>
</div>
</section>
</main>
@endsection