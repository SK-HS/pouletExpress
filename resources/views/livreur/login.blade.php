<!DOCTYPE html>
<html lang="fr" class="light">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title>{{config('app.name')}} | Connexion </title>

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <!-- Tailwind Dark Mode: class-based -->
  <script>tailwind.config = { darkMode: 'class' }</script>
  <!-- Anti-flash: Apply theme before render -->
  <script>if(localStorage.getItem('theme')==='dark'){document.documentElement.classList.add('dark')}else{document.documentElement.classList.remove('dark')}</script>

  <!-- Google Fonts & Material Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

  <!-- Custom Stylesheet -->
  <link rel="stylesheet" href="{{asset('assets/livreur/css/css/styles.css')}}" />
</head>
<body class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 min-h-screen flex flex-col justify-center items-center p-4 antialiased selection:bg-emerald-500 selection:text-white">

  <!-- Main Login Card -->
  <main class="w-full max-w-md">
    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 md:p-8 border border-slate-200 dark:border-slate-700 shadow-2xl space-y-6">
      
      <!-- Branding Area -->
      <div class="text-center space-y-2">
        <div class="w-16 h-16 rounded-2xl bg-emerald-700 text-white mx-auto flex items-center justify-center shadow-lg shadow-emerald-900/30">
          <span class="material-symbols-outlined text-3xl">local_shipping</span>
        </div>
        <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">PouletExpress</h1>
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest">Portail de Connexion Livreur</p>
      </div>
        @if ($errors->any())
        <div class="bg-error/10 border border-error text-error rounded-xl p-4 mb-6 text-sm">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif
      <!-- Login Form -->
      <form id="loginForm" class="space-y-4" method="POST"  action="{{route('Connexion-Livreur')}}">
         @csrf
        <div class="space-y-1">
          <label for="email" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Téléphone Livreur</label>
          <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">phone</span>
            <input type="text" id="email" name="telephone" value="{{ old('telephone') }}" required class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 transition" />
          </div>
        </div>

        <div class="space-y-1">
          <div class="flex items-center justify-between">
            <label for="password" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Mot de passe</label>
            <a href="#" class="text-xs font-bold text-emerald-600 hover:underline">Oublié ?</a>
          </div>
          <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">lock</span>
            <input type="password" id="password" name="password" placeholder="••••••••" required class="w-full pl-10 pr-10 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 transition" />
            <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
              <span id="passIcon" class="material-symbols-outlined text-lg">visibility</span>
            </button>
          </div>
        </div>

        <div class="flex items-center gap-2">
          <input type="checkbox" name="remember" id="remember" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500" />
          <label for="remember" class="text-xs font-medium text-slate-600 dark:text-slate-400">Se souvenir de cet appareil</label>
        </div>

        <button type="submit" class="w-full py-3.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold text-sm shadow-lg shadow-emerald-900/20 transition flex items-center justify-center gap-2">
          <span>Se connecter à mon espace</span>
          <span class="material-symbols-outlined text-base">arrow_forward</span>
        </button>
      </form>

      <div class="pt-4 border-t border-slate-100 dark:border-slate-700 text-center space-y-3">
        <p class="text-xs text-slate-500">Pas encore de compte livreur ?</p>
        <button onclick="window.location.href='{{route('Livreur-Inscription')}}'" class="w-full py-2.5 rounded-xl border border-orange-500 text-orange-600 font-bold text-xs hover:bg-orange-50 dark:hover:bg-orange-950/40 transition">
          Devenir livreur partenaire PouletExpress
        </button>

        <div class="pt-2">
          <a href="{{route('Livreur-Espace')}}" class="text-xs font-semibold text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 underline">
            Accéder directement au tableau de bord
          </a>
        </div>
      </div>

    </div>
  </main>

  <script src="{{asset('assets/livreur/js/app.js')}}"></script>
</body>
</html>
