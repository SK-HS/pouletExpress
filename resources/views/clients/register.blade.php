@extends('layouts.master')
@section('content')

<main class="min-h-[80vh] flex items-center justify-center px-margin-mobile py-12">
    <div class="w-full bg-surface-container-lowest rounded-2xl shadow-lg border border-outline-variant p-8 ">

        <div class="text-center mb-8">
            <h1 class="font-headline-lg text-headline-lg text-primary mb-2">Créer un compte</h1>
            <p class="text-on-surface-variant">Créez votre compte pour finaliser votre commande</p>
        </div>

        @if ($errors->any())
        <div class="bg-error/10 border border-error text-error rounded-xl p-4 mb-6 text-sm">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        @if(session('success'))
            <div class="flex items-center gap-2 mb-4 p-4 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200">
                <span class="material-symbols-outlined">
                    check_circle
                </span>

                <span class="text-sm font-semibold">
                    {{ session('success') }}
                </span>
            </div>
        @endif

        <form method="POST" action="{{ route('register.attempt.client') }}" class="space-y-5">
            @csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
            <div class="space-y-1 ">
                <label class="font-label-caps uppercase text-on-surface-variant text-sm" for="nom">Nom complet</label>
                <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required autofocus
                       class="w-full border-2 border-outline-variant rounded-lg p-3 font-body-md focus:border-primary focus:ring-0 outline-none transition-all">
            </div>

            <div class="space-y-1">
                <label class="font-label-caps uppercase text-on-surface-variant text-sm" for="email">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                       class="w-full border-2 border-outline-variant rounded-lg p-3 font-body-md focus:border-primary focus:ring-0 outline-none transition-all">
            </div>
           

            <div class="space-y-1">
                <label class="font-label-caps uppercase text-on-surface-variant text-sm" for="telephone">Téléphone</label>
                <input type="tel" name="telephone" id="telephone" value="{{ old('telephone') }}" placeholder="+225 07 00 00 00 00" required
                       class="w-full border-2 border-outline-variant rounded-lg p-3 font-body-md focus:border-primary focus:ring-0 outline-none transition-all">
            </div>
            <div class="space-y-1">
                <label class="font-label-caps uppercase text-on-surface-variant text-sm" for="contact">Contact</label>
                <input type="tel" name="contact" id="contact" value="{{ old('contact') }}" placeholder="+225 07 00 00 00 00" 
                       class="w-full border-2 border-outline-variant rounded-lg p-3 font-body-md focus:border-primary focus:ring-0 outline-none transition-all">
            </div>
            <div class="space-y-1">
                <label class="font-label-caps uppercase text-on-surface-variant text-sm" for="adresse">Adresse</label>
                <input type="text" name="adresse" id="adresse" value="{{ old('adresse') }}" placeholder="cocody riviera radio Alpha" 
                       class="w-full border-2 border-outline-variant rounded-lg p-3 font-body-md focus:border-primary focus:ring-0 outline-none transition-all">
            </div>
          
            <div class="space-y-1">
                <label class="font-label-caps uppercase text-on-surface-variant text-sm" for="type">Type</label>
                <select class="w-full h-12 px-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-0 transition-all font-body-md bg-white" id="type" name="type" required>
                    <option value="">Sélectionnez un sujet</option>
                    <option value="Particulier">Particulier</option>
                    <option value="Restaurant">Restaurant</option>
                    <option value="Hôtel">Hôtel</option>
                    <option value="Entreprise">Entreprise</option>
                    <option value="Supermarché">Supermarché</option>
                    <option value="Boutique">Boutique</option>
                    <option value="Grossiste">Grossiste</option>
                    <option value="Revendeur">Revendeur</option>
                    <option value="Association">Association</option>
                    <option value="École">École</option>
                    <option value="Autre">Autre</option>
                    </select>
            </div>
            <div class="space-y-1">
                <label class="font-label-caps uppercase text-on-surface-variant text-sm" for="type">Quartier</label>
              <select class="w-full h-12 px-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-0 transition-all font-body-md bg-white" name="quartier_id" id="quartier_id">
                    <option value="" data-frais="0">Sélectionner Votre Quartier</option>
                            @foreach($quartiers as $quartier)
                                <option value="{{ $quartier->id }}">
                                    {{ $quartier->nom_quartier }}
                                </option>
                            @endforeach
                    </select>
            </div>
            <div class="space-y-1">
                <label class="font-label-caps uppercase text-on-surface-variant text-sm" for="image">Image</label>
                <input type="file" name="image" id="image"  
                       class="w-full border-2 border-outline-variant rounded-lg p-3 font-body-md focus:border-primary focus:ring-0 outline-none transition-all">
            </div>

    <div class="space-y-1">
    <label class="font-label-caps uppercase text-on-surface-variant text-sm" for="password">Mot de passe</label>
    
    <div class="relative w-full">
        
        <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400 pointer-events-none">
            lock
        </span>
        
        <input type="password" id="passwordInput" name="password" required
               class="w-full pl-10 pr-10 py-3 border-2 border-outline-variant rounded-lg font-body-md focus:border-primary focus:ring-0 outline-none transition-all">
        
        <button type="button" id="togglePasswordBtn" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none transition-colors">
            <span class="material-symbols-outlined text-lg">visibility</span>
        </button>
        
    </div>
</div>


            <div class="space-y-1">
                <label class="font-label-caps uppercase text-on-surface-variant text-sm" for="password_confirmation">Confirmer le mot de passe</label>
                <div class="relative w-full">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400 pointer-events-none">
                        lock
                    </span>
                    <input type="password" id="passwordInput2" name="password_confirmation" required
                           class="w-full pl-10 pr-10 py-3 border-2 border-outline-variant rounded-lg font-body-md focus:border-primary focus:ring-0 outline-none transition-all">
                    <button type="button" id="togglePasswordBtn2" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none transition-colors">
                        <span class="material-symbols-outlined text-lg">visibility</span>
                    </button>
                </div>
            </div>
            </div>
            

            <button type="submit" class="w-full bg-primary text-on-primary font-body-md-bold py-3 rounded-xl shadow-md hover:bg-primary-container transition-all">
                Créer mon compte
            </button>
        </form>

        <p class="text-center text-sm text-on-surface-variant mt-6">
            Déjà un compte ?
            <a href="{{ route('Login-Client') }}" class="text-primary font-body-md-bold hover:underline">Se connecter</a>
        </p>
    </div>
</main>
 <script>
    document.getElementById('togglePasswordBtn').addEventListener('click', function() {
        const passwordInput = document.getElementById('passwordInput');
        const icon = document.getElementById('togglePasswordIcon');
        
        // Bascule le type de l'input entre 'password' et 'text'
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.textContent = 'visibility_off'; // Change l'icône (œil barré)
        } else {
            passwordInput.type = 'password';
            icon.textContent = 'visibility'; // Change l'icône (œil normal)
        }
    });
    document.getElementById('togglePasswordBtn2').addEventListener('click', function() {
        const passwordInput = document.getElementById('passwordInput2');
        const icon = document.getElementById('togglePasswordIcon2');
        
        // Bascule le type de l'input entre 'password' et 'text'
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.textContent = 'visibility_off'; // Change l'icône (œil barré)
        } else {
            passwordInput.type = 'password';
            icon.textContent = 'visibility'; // Change l'icône (œil normal)
        }
    });
</script>
@endsection
