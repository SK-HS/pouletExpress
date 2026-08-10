<!DOCTYPE html>
<html lang="fr" class="light">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title>{{ config('app.name') }} | Inscription Livreur </title>

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script>tailwind.config = { darkMode: 'class' }</script>
  <script>if(localStorage.getItem('theme')==='dark'){document.documentElement.classList.add('dark')}else{document.documentElement.classList.remove('dark')}</script>
  <link rel="stylesheet" href="{{asset('assets/livreur/css/css/styles.css')}}" />
  <!-- Google Fonts & Material Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

  <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
  </style>
</head>
<body class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 min-h-screen flex flex-col justify-center items-center p-4 md:p-8 antialiased selection:bg-emerald-500 selection:text-white py-12">

  <!-- Main Registration Card -->
  <main class="w-full max-w-3xl">
    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 md:p-10 border border-slate-200 dark:border-slate-700 shadow-2xl space-y-8 relative overflow-hidden">
      
      <!-- Decorative Background element -->
      <div class="absolute top-0 right-0 -mr-16 -mt-16 w-48 h-48 rounded-full bg-emerald-600/10 blur-3xl z-0"></div>
      
      <!-- Branding Area -->
      <div class="text-center space-y-3 relative z-10">
        <div class="w-16 h-16 rounded-2xl bg-emerald-700 text-white mx-auto flex items-center justify-center shadow-lg shadow-emerald-900/30">
          <span class="material-symbols-outlined text-3xl">two_wheeler</span>
        </div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Rejoignez PouletExpress</h1>
        <p class="text-xs md:text-sm font-semibold text-slate-500 uppercase tracking-widest">Création de compte Livreur Partenaire</p>
      </div>

      <!-- Gestion des erreurs -->
      @if ($errors->any())
      <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 text-red-600 dark:text-red-400 rounded-xl p-4 text-sm relative z-10">
          <ul class="list-disc pl-5 space-y-1">
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
      @endif

      <!-- Registration Form -->
      <form id="registerForm" class="space-y-6 relative z-10" method="POST" action="{{ route('Save-Livreur-Inscription') }}" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Informations Personnelles -->
            <div class="space-y-6">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-700 pb-2">Informations Personnelles</h3>
                
                <div class="space-y-1">
                    <label for="nom" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nom complet <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">person</span>
                        <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 transition" placeholder="Ex: Koffi Konan" />
                    </div>
                </div>

                <div class="space-y-1">
                    <label for="telephone" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Numéro de téléphone <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">phone</span>
                        <input type="tel" id="telephone" name="telephone" value="{{ old('telephone') }}" required class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 transition" placeholder="Ex: 0700000000" />
                    </div>
                </div>

                <div class="space-y-1">
                    <label for="email" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Adresse Email (Optionnel)</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">mail</span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 transition" placeholder="livreur@exemple.com" />
                    </div>
                </div>

                <div class="space-y-1">
                    <label for="type" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Type de livreur <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">badge</span>
                        <select id="type" name="type" required class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 transition appearance-none">
                            <option value="">Sélectionnez un type</option>
                            <option value="VELO" {{ old('type') == 'VELO' ? 'selected' : '' }}>VELO</option>
                            <option value="MOTO" {{ old('type') == 'MOTO' ? 'selected' : '' }}>MOTO</option>
                            <option value="TRICYCLE" {{ old('type') == 'TRICYCLE' ? 'selected' : '' }}>TRICYCLE</option>
                            <option value="VOITURE" {{ old('type') == 'VOITURE' ? 'selected' : '' }}>VOITURE</option>
                            <option value="CAMIONNETTE" {{ old('type') == 'CAMIONNETTE' ? 'selected' : '' }}>CAMIONNETTE</option>
                            <option value="CAMION" {{ old('type') == 'CAMION' ? 'selected' : '' }}>CAMION</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Informations Zone et Sécurité -->
            <div class="space-y-6">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-700 pb-2">Secteur et Sécurité</h3>
                
                <div class="space-y-1">
                    <label for="quartier_id" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Quartier Principal <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">location_on</span>
                        <select id="quartier_id" name="quartier_id" required class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 transition appearance-none">
                            <option value="">Choisissez votre zone</option>
                            <!-- Remplacez cette liste statique par une boucle dynamique sur vos quartiers -->
                            @foreach($quartiers as $quartier)
                                <option value="{{ $quartier->id }}">{{ $quartier->nom_quartier}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="space-y-1">
                    <label for="adresse" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Adresse de résidence</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">home</span>
                        <input type="text" id="adresse" name="adresse" value="{{ old('adresse') }}" class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 transition" placeholder="Quartier, rue..." />
                    </div>
                </div>

                <div class="space-y-1">
                    <label for="password" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Mot de passe <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">lock</span>
                        <input type="password" id="password" name="password" required class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 transition" placeholder="Minimum 8 caractères" />
                    </div>
                </div>

                <div class="space-y-1">
                    <label for="password_confirmation" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Confirmer mot de passe <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">lock</span>
                        <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 transition" placeholder="Retapez le mot de passe" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Photo / Document -->
        <div class="pt-4 border-t border-slate-200 dark:border-slate-700">
            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-3">Photo de profil / Pièce d'identité (Optionnel)</label>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 dark:border-slate-700 border-dashed rounded-xl hover:border-emerald-500 transition-colors bg-slate-50 dark:bg-slate-900/50">
                <div class="space-y-1 text-center">
                    <span class="material-symbols-outlined text-4xl text-slate-400">cloud_upload</span>
                    <div class="flex text-sm text-slate-600 dark:text-slate-400 justify-center">
                        <label for="image" class="relative cursor-pointer rounded-md font-medium text-emerald-600 hover:text-emerald-500 focus-within:outline-none">
                            <span>Télécharger un fichier</span>
                            <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                        </label>
                        <p class="pl-1">ou glisser-déposer</p>
                    </div>
                    <p class="text-xs text-slate-500">PNG, JPG, GIF jusqu'à 2MB</p>
                </div>
            </div>
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full py-4 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold text-sm shadow-lg shadow-emerald-900/20 transition flex items-center justify-center gap-2">
                <span>Créer mon compte livreur</span>
                <span class="material-symbols-outlined text-base">check_circle</span>
            </button>
        </div>
      </form>

      <!-- Lien de retour -->
      <div class="text-center pt-2">
        <p class="text-xs text-slate-500">
          Vous avez déjà un compte ? 
          <a href="{{ route('Connexion-Livreur') }}" class="font-bold text-emerald-600 hover:underline">Connectez-vous ici</a>
        </p>
      </div>

    </div>
  </main>

  <script src="{{ asset('assets/livreur/js/app.js') }}"></script>
</body>
</html>
