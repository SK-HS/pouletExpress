<!DOCTYPE html>
<html class="light" lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>{{config('app.name')}} | Connexion Fournisseur</title>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@100..900&family=JetBrains+Mono:wght@100..900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <script src="{{asset('assets/fournisseur/js/tailwind-config.js')}}"></script>
    <link rel="stylesheet" href="{{asset('assets/fournisseur/css/main.css')}}">

</head>
<body class="bg-background font-body-md text-on-surface min-h-screen overflow-x-hidden">
    <!-- Floating Dark Mode Toggle for login page -->
    <div class="absolute top-4 right-4 z-50">
        <button class="w-10 h-10 flex items-center justify-center rounded-full bg-surface-container hover:bg-surface-container-high transition-all shadow-sm border border-outline-variant" id="theme-toggle">
            <span class="material-symbols-outlined text-on-surface-variant text-xl dark:hidden">dark_mode</span>
            <span class="material-symbols-outlined text-on-surface-variant text-xl hidden dark:block">light_mode</span>
        </button>
    </div>

    <div class="flex min-h-screen">
        <!-- Side Illustration / Branding Canvas -->
        <div class="hidden lg:flex w-5/12 relative bg-primary-container overflow-hidden items-center justify-center">
            <div class="absolute inset-0 z-0">
                <div class="w-full h-full bg-cover bg-center opacity-40 mix-blend-overlay" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuC9_qKvLrOrL9sHSqBTMvUgk1zA-k2izbdoRjdObb4QRj8IcL1wEVk4QwVOSWN5LW477-6OjlBE471nO1OrEdTnUVPTYGE8G0M3FN9bGH_vAC1IZeew770WE3oLbI5PIbYRMTGZeWR7pNMAg7X4zmxZobWE0luuL693bwGyGq3QxpHfr79OGmrv6ilpW6Rw96UBTYM9FUN8QSuuAQoYZSRoiU_LdU7t1qP55x2-UaXUGAnEf8qb6jTyQebuJsD_rbW2ejMlOT57iBg')"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-primary-container via-transparent to-transparent opacity-80"></div>
            </div>
            <div class="relative z-10 p-12 text-on-primary-container">
                <img alt="AgriManager Logo" class="h-16 mb-8 filter drop-shadow-md" src="https://lh3.googleusercontent.com/aida/AP1WRLttKA77ABGaQ_h945A0tu1wuK_db0agLA7ebq40A9Z5k_zktRzrk9Gz4Fs9VWJuIPV5lY5Ci1iavfeAe6wBhxNKUda0BvE97y3HKbV-IJ7QuMx3A8Fvn71OdnlwPvjDIUbjzy4f2LUB7jD0tm6qAzwiqYs5c70dkhiZJMJfUsd2q74zOJvG_XYBR7eNUgiDgi2n_XKWHahbPoiJTQq8NoQ4RtiCdV2IlrY1nk5l4z9Y6DlJq60uEFonFRo"/>
                <h1 class="font-headline-lg text-headline-lg mb-4 text-primary font-bold">Heureux de vous revoir.</h1>
                <p class="font-body-lg text-body-lg text-on-surface-variant mb-8">Accédez à votre tableau de bord AgriManager pour gérer vos commandes, suivre vos ventes et développer votre activité.</p>
                
                <!-- Graphic element -->
                <div class="p-6 bg-surface/40 backdrop-blur-md rounded-2xl border border-surface/50 shadow-lg mt-12 max-w-sm">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-primary text-on-primary rounded-full flex items-center justify-center shadow-md">
                            <span class="material-symbols-outlined">monitoring</span>
                        </div>
                        <div>
                            <h4 class="font-body-md-bold text-on-surface">Ventes du jour</h4>
                            <p class="text-primary font-bold text-xl">+24% <span class="text-sm font-normal text-on-surface-variant">vs hier</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Login Form Side -->
        <main class="w-full lg:w-7/12 flex flex-col p-6 md:p-12 lg:p-16 justify-center items-center bg-surface relative">
            <div class="w-full max-w-md relative z-10">
                <!-- Mobile Header -->
                <div class="lg:hidden flex flex-col items-center justify-center mb-10">
                    <img alt="AgriManager Logo" class="h-12 mb-4" src="https://lh3.googleusercontent.com/aida/AP1WRLttKA77ABGaQ_h945A0tu1wuK_db0agLA7ebq40A9Z5k_zktRzrk9Gz4Fs9VWJuIPV5lY5Ci1iavfeAe6wBhxNKUda0BvE97y3HKbV-IJ7QuMx3A8Fvn71OdnlwPvjDIUbjzy4f2LUB7jD0tm6qAzwiqYs5c70dkhiZJMJfUsd2q74zOJvG_XYBR7eNUgiDgi2n_XKWHahbPoiJTQq8NoQ4RtiCdV2IlrY1nk5l4z9Y6DlJq60uEFonFRo"/>
                </div>
                
                <div class="mb-10 text-center lg:text-left">
                    <h2 class="font-headline-lg text-headline-lg text-on-surface mb-2 font-bold">Connexion</h2>
                    <p class="text-on-surface-variant font-body-md">Saisissez vos identifiants pour accéder à votre espace.</p>
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
                <form class="space-y-6" id="loginForm" action="{{ route('Connexion-Fournisseur') }}" method="POST">
                    @csrf
                    <div class="space-y-2 group">
                        <label class="font-label-caps text-label-caps text-on-surface-variant group-focus-within:text-primary transition-colors">Email ou Téléphone</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">person</span>
                            <input name="telephone" value="{{ old('telephone') }}" class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" placeholder="exemple@email.com ou 0700000000" type="text" required/>
                        </div>
                    </div>
                    
                    <div class="space-y-2 group">
                        <div class="flex justify-between items-center">
                            <label class="font-label-caps text-label-caps text-on-surface-variant group-focus-within:text-primary transition-colors">Mot de Passe</label>
                            <a href="#" class="text-primary hover:text-primary-fixed-dim font-label-sm text-sm hover:underline transition-colors">Mot de passe oublié ?</a>
                        </div>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">lock</span>
                            <input name="password" class="w-full h-14 pl-12 pr-12 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" placeholder="••••••••" type="password" required id="password-input"/>
                            <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors" id="toggle-password">
                                <span class="material-symbols-outlined">visibility</span>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <input type="checkbox" id="remember" class="w-5 h-5 rounded border-2 border-outline-variant text-primary focus:ring-primary focus:ring-offset-0 bg-surface-container-lowest transition-all cursor-pointer"/>
                        <label for="remember" class="font-body-md text-on-surface-variant cursor-pointer">Se souvenir de moi</label>
                    </div>

                    <button type="submit" class="w-full h-14 mt-4 bg-primary hover:bg-primary/90 text-on-primary rounded-xl font-body-md-bold shadow-lg shadow-primary/20 hover:shadow-xl hover:shadow-primary/30 hover:-translate-y-0.5 active:scale-95 transition-all flex items-center justify-center gap-2 text-lg">
                        Se connecter
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </button>
                </form>

                <div class="mt-10 text-center">
                    <p class="font-body-md text-on-surface-variant">
                        Vous n'avez pas encore de compte ? 
                        <a href="{{route('Fournisseur-Inscription')}}" class="text-primary font-bold hover:underline transition-all">Inscrivez-vous ici</a>
                    </p>
                </div>
            </div>
            
            <!-- Decorative background blob for right side -->
            <div class="absolute bottom-0 right-0 w-64 h-64 bg-primary-container/30 rounded-full blur-3xl -z-10 translate-x-1/3 translate-y-1/3"></div>
            <div class="absolute top-0 left-0 w-48 h-48 bg-secondary-container/30 rounded-full blur-3xl -z-10 -translate-x-1/2 -translate-y-1/2"></div>
        </main>
    </div>

       <script src="{{asset('assets/fournisseur/js/main.js')}}"></script>
    <script>
        // Specific logic for login page
        document.addEventListener('DOMContentLoaded', () => {
            const togglePasswordBtn = document.getElementById('toggle-password');
            const passwordInput = document.getElementById('password-input');
            
            if(togglePasswordBtn && passwordInput) {
                togglePasswordBtn.addEventListener('click', () => {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    togglePasswordBtn.querySelector('span').textContent = type === 'password' ? 'visibility' : 'visibility_off';
                });
            }
        });
    </script>
</body>
</html>
