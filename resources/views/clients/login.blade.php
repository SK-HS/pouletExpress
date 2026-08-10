@extends('layouts.master')
@section('content')

<main class="min-h-[80vh] flex items-center justify-center px-margin-mobile py-12">
    <div class="w-full max-w-md bg-surface-container-lowest rounded-2xl shadow-lg border border-outline-variant p-8">

        <div class="text-center mb-8">
            <h1 class="font-headline-lg text-headline-lg text-primary mb-2">Connexion</h1>
            <p class="text-on-surface-variant">Connectez-vous pour finaliser votre commande</p>
        </div>

        @if ($errors->any())
        <div class="bg-error/10 border border-error text-error rounded-xl p-4 mb-6 text-sm">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('login.attempt.client') }}" class="space-y-5">
            @csrf

            <div class="space-y-1">
                <label class="font-label-caps uppercase text-on-surface-variant text-sm" for="email">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
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

            <div class="flex items-center gap-2">
                <input type="checkbox" name="remember" id="remember" class="w-4 h-4">
                <label for="remember" class="text-sm text-on-surface-variant">Se souvenir de moi</label>
            </div>

            <button type="submit" class="w-full bg-primary text-on-primary font-body-md-bold py-3 rounded-xl shadow-md hover:bg-primary-container transition-all">
                Se connecter
            </button>
        </form>

        <p class="text-center text-sm text-on-surface-variant mt-6">
            Pas encore de compte ?
            <a href="{{ route('Inscription-Client') }}" class="text-primary font-body-md-bold hover:underline">Créer un compte</a>
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
    </script>


@endsection
