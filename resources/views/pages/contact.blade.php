@extends('layouts.master')
@section('content')

<main class="max-w-7xl mx-auto px-margin-mobile md:px-margin-desktop py-1 md:py-5 relative">
<div class="absolute inset-0 agro-grid-pattern opacity-20 pointer-events-none"></div>
 @if ($message = Session::get('success'))
              <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
              <strong class="font-bold">SUCCESS!</strong>
              <span class="block sm:inline">{{ $message }}.</span>
              <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
              </span>
            </div>
                    @endif
            @if ($message = Session::get('danger'))
                       <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
              <strong class="font-bold">Erreur!</strong>
              <span class="block sm:inline">{{ $message }}.</span>
              <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
              </span>
            </div>

                        
                    @endif
<!-- Hero Title -->
<div class="mb-16 text-center md:text-left relative z-10">
<span class="inline-block bg-primary-fixed text-on-primary-fixed px-4 py-1 rounded-full font-label-caps text-label-caps mb-4 uppercase tracking-widest">Nous sommes à votre écoute</span>
<h1 class="font-headline-lg text-headline-lg md:text-[48px] text-primary mb-4 leading-tight">Parlons de vos besoins avicoles</h1>
<p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl">Besoin d'aide pour une commande ou envie de devenir éleveur partenaire ? Notre équipe d'Abidjan vous répond sous 24 heures.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter relative z-10">
<!-- Form Section -->
<div class="lg:col-span-7 bg-white p-card-padding rounded-2xl shadow-sm border border-outline-variant/20">
<h2 class="font-headline-md text-headline-md text-primary mb-8">Envoyez un message</h2>

<form class="space-y-6"  enctype="multipart/form-data" id="contactForm" method="POST" action="{{ route('Soumetre-Message') }}">
  {!! csrf_field() !!}
<div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
<div class="space-y-2">
<label class="font-body-md-bold text-on-surface-variant ml-1" for="name">Nom complet</label>
<input class="w-full h-12 px-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-0 transition-all font-body-md placeholder:text-outline" id="name" name="nom" placeholder="Ex: Koffi Bakayoko" required type="text">
</div>
<div class="space-y-2">
<label class="font-body-md-bold text-on-surface-variant ml-1" for="contact">Contact</label>
<input class="w-full h-12 px-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-0 transition-all font-body-md placeholder:text-outline" id="contact" name="contact" placeholder="22501020304" required type="text">
</div>
<div class="space-y-2">
<label class="font-body-md-bold text-on-surface-variant ml-1" for="email">Email</label>
<input class="w-full h-12 px-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-0 transition-all font-body-md placeholder:text-outline" id="email" name="email" placeholder="koffi@email.com"  type="email">
</div>
<div class="space-y-2">
<label class="font-body-md-bold text-on-surface-variant ml-1" for="subject">Sujet de votre demande</label>
<select class="w-full h-12 px-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-0 transition-all font-body-md bg-white" id="subject" name="subject" required>
<option value="">Sélectionnez un sujet</option>
<option value="order">Suivi de commande</option>
<option value="farmer">Devenir éleveur partenaire</option>
<option value="support">Support technique</option>
<option value="other">Autre demande</option>
</select>
</div>
</div>
<div class="space-y-2">
<label class="font-body-md-bold text-on-surface-variant ml-1" for="message">Votre message</label>
<textarea class="w-full p-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-0 transition-all font-body-md placeholder:text-outline" id="message" name="message" placeholder="Comment pouvons-nous vous aider ?" required rows="3"></textarea>
</div>
<button class="w-full md:w-auto px-10 h-14 bg-primary text-on-primary font-body-md-bold rounded-xl shadow-lg hover:bg-primary-container transition-all flex items-center justify-center gap-3 active:scale-95" type="submit">
Envoyer le message
<span class="material-symbols-outlined">send</span>
</button>
</form>
</div>

<!-- Contact Info Sidebar -->
<div class="lg:col-span-5 space-y-gutter">
<div class="bg-primary-container text-on-primary-container p-card-padding rounded-2xl shadow-xl overflow-hidden relative group">
<div class="absolute -right-10 -bottom-10 w-48 h-48 bg-primary-fixed/10 rounded-full blur-3xl"></div>
<h2 class="font-headline-md text-headline-md text-primary-fixed mb-8">Coordonnées</h2>
<div class="space-y-8 relative z-10">
<div class="flex gap-4 items-start">
<div class="w-12 h-12 bg-primary/20 rounded-xl flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-primary-fixed">location_on</span>
</div>
<div>
<p class="font-body-md-bold text-primary-fixed-dim">Siège Social</p>
<p class="font-body-md opacity-90">Immeuble Le Manguier, Zone 4C,<br>Marcory, Abidjan, Côte d'Ivoire</p>
</div>
</div>
<div class="flex gap-4 items-start">
<div class="w-12 h-12 bg-primary/20 rounded-xl flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-primary-fixed">call</span>
</div>
<div>
<p class="font-body-md-bold text-primary-fixed-dim">Téléphone</p>
<p class="font-body-md opacity-90">+225 07 00 00 00 00</p>
<p class="font-body-md opacity-70 text-sm">Lun-Sam: 08:00 - 18:00</p>
</div>
</div>
<div class="flex gap-4 items-start">
<div class="w-12 h-12 bg-primary/20 rounded-xl flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-primary-fixed">mail</span>
</div>
<div>
<p class="font-body-md-bold text-primary-fixed-dim">Email Support</p>
<p class="font-body-md opacity-90">contact@pouletexpress.ci</p>
</div>
</div>
</div>
</div>

<div class="bg-surface-container rounded-2xl h-64 overflow-hidden border border-outline-variant/30 relative">
<div class="w-full h-full bg-cover bg-center" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCggleGuBFn5fw2ZuBwrJSf6nJ5Fk2-1dx2UulBcjsEFe4WqeJ-XX6qrZNoy8amgu-Nn9XisuCGrmKELipV-rPyzDDdWCP_z0BAo0kywhluCwimdxjYjyInSLpF9Op1-ORAg76sCc2qwLgcDK39jRUVrGjL5uKlJfRiQeAB6vbZBY_Q7-LHpj0YItwu-hLBCm5UP4jbPnwaUzq9N2hYd_unan9Km6b-3u3Hs7WD3CUV0fdF47xuO9xo7YSbQ8DjHPR5jO7NHs4GrBA')"></div>
<div class="absolute bottom-4 left-4 bg-white/90 backdrop-blur px-4 py-2 rounded-lg shadow-sm">
<p class="text-label-sm font-label-sm text-primary flex items-center gap-2">
<span class="material-symbols-outlined text-xs">navigation</span>
Abidjan, Marcory Zone 4C
</p>
</div>
</div>
</div>
</div>

<!-- FAQ Section -->
<section class="mt-32">
<div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
<div>
<h2 class="font-headline-lg text-headline-lg text-primary mb-2">Questions Fréquentes</h2>
<p class="text-body-lg text-on-surface-variant">Les réponses rapides pour gagner du temps.</p>
</div>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
<div class="p-card-padding bg-surface-container-low rounded-2xl hover:bg-surface-container transition-colors cursor-pointer group border border-transparent hover:border-outline-variant/30">
<div class="flex justify-between items-start mb-2">
<h3 class="font-body-md-bold text-on-surface">Comment commander sur PouletExpress ?</h3>
<span class="material-symbols-outlined text-primary opacity-0 group-hover:opacity-100 transition-opacity">add_circle</span>
</div>
<p class="text-body-md text-on-surface-variant leading-relaxed">Parcourez le marché, choisissez vos produits, sélectionnez l'éleveur de votre choix et réglez via Mobile Money.</p>
</div>
<div class="p-card-padding bg-surface-container-low rounded-2xl hover:bg-surface-container transition-colors cursor-pointer group border border-transparent hover:border-outline-variant/30">
<div class="flex justify-between items-start mb-2">
<h3 class="font-body-md-bold text-on-surface">Quels sont les délais de livraison ?</h3>
<span class="material-symbols-outlined text-primary opacity-0 group-hover:opacity-100 transition-opacity">add_circle</span>
</div>
<p class="text-body-md text-on-surface-variant leading-relaxed">Pour garantir la fraîcheur, nous livrons sous 24h à 48h selon votre localisation à Abidjan.</p>
</div>
<div class="p-card-padding bg-surface-container-low rounded-2xl hover:bg-surface-container transition-colors cursor-pointer group border border-transparent hover:border-outline-variant/30">
<div class="flex justify-between items-start mb-2">
<h3 class="font-body-md-bold text-on-surface">Est-ce que le paiement est sécurisé ?</h3>
<span class="material-symbols-outlined text-primary opacity-0 group-hover:opacity-100 transition-opacity">add_circle</span>
</div>
<p class="text-body-md text-on-surface-variant leading-relaxed">Absolument. Nous supportons Orange Money, MTN et Moov Money pour des transactions sécurisées.</p>
</div>
<div class="p-card-padding bg-surface-container-low rounded-2xl hover:bg-surface-container transition-colors cursor-pointer group border border-transparent hover:border-outline-variant/30">
<div class="flex justify-between items-start mb-2">
<h3 class="font-body-md-bold text-on-surface">Puis-je modifier ma commande après validation ?</h3>
<span class="material-symbols-outlined text-primary opacity-0 group-hover:opacity-100 transition-opacity">add_circle</span>
</div>
<p class="text-body-md text-on-surface-variant leading-relaxed">Une modification est possible tant que l'éleveur n'a pas encore validé l'expédition (généralement dans les 2 heures).</p>
</div>
</div>
</section>

<!-- CTA Section -->
<section class="mt-32 bg-secondary-container rounded-[32px] p-12 text-center relative overflow-hidden">
<div class="absolute inset-0 agro-grid-pattern opacity-10"></div>
<div class="relative z-10">
<h2 class="font-headline-lg text-headline-lg text-on-secondary-container mb-4">Prêt à soutenir nos éleveurs locaux ?</h2>
<p class="text-body-lg text-on-secondary-container opacity-80 mb-8 max-w-xl mx-auto">Rejoignez des milliers d'ivoiriens qui font confiance au circuit court pour leur alimentation.</p>
<div class="flex flex-wrap justify-center gap-4">
<a href="produits.html" class="px-8 h-12 bg-primary text-on-primary rounded-xl font-body-md-bold shadow-md hover:shadow-lg transition-all active:scale-95 flex items-center">Explorer le marché</a>
<a href="apropos.html" class="px-8 h-12 border-2 border-on-secondary-container text-on-secondary-container rounded-xl font-body-md-bold hover:bg-on-secondary-container/5 transition-all active:scale-95 flex items-center">Voir nos engagements</a>
</div>
</div>
</section>
</main>


@endsection