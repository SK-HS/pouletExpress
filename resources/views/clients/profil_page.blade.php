@extends('layouts.client.main')
@section('content')
 
<main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6 sm:space-y-8">
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-1.5 sm:gap-2 text-xs font-semibold text-slate-500 mb-2 overflow-x-auto">
            <a href="index.html" class="hover:text-emerald-600 shrink-0">Accueil</a>
            <span class="material-symbols-outlined text-xs shrink-0">chevron_right</span>
            <a href="index.html" class="hover:text-emerald-600 shrink-0">Espace Client</a>
            <span class="material-symbols-outlined text-xs shrink-0">chevron_right</span>
            <span class="shrink-0">Mon Profil</span>
        </nav>

        <!-- Main Card Grid -->
        <div class="max-w-5xl mx-auto bg-white dark:bg-[#152238] rounded-2xl sm:rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl overflow-hidden grid grid-cols-1 md:grid-cols-2">
            
            <!-- Visual Left Side Banner -->
            <div class="relative hidden md:flex flex-col justify-between p-8 lg:p-10 bg-gradient-to-br from-emerald-900 to-emerald-950 text-white overflow-hidden">
                <div class="absolute inset-0 opacity-20 bg-cover bg-center pointer-events-none" style="background-image: url('https://images.unsplash.com/photo-1548550023-2bdb3c5beed7?q=80&w=800&auto=format&fit=crop');"></div>
                
                <div class="relative z-10 space-y-4">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-800/80 text-emerald-200 text-xs font-semibold uppercase tracking-wider border border-emerald-500/20">
                        Réseau Agricole N°1 CI
                    </span>
                    <h2 class="text-2xl lg:text-3xl font-extrabold leading-tight">Le frais, directement de la ferme à votre table.</h2>
                    <p class="text-emerald-100/80 text-xs lg:text-sm">Rejoignez notre réseau de clients privilégiés et bénéficiez de volailles garanties saines et de livraisons ultra-rapides en Côte d'Ivoire.</p>
                </div>

                <div class="relative z-10 pt-8 border-t border-emerald-800/80 flex items-center gap-4">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-full bg-amber-500 text-slate-900 font-extrabold flex items-center justify-center text-xs lg:text-sm shadow-md">
                        100%
                    </div>
                    <span class="text-xs font-semibold text-emerald-100">Traçabilité & Hygiène Bio garanties</span>
                </div>
            </div>

            <!-- Registration / Profile Edit Form -->
            <div class="p-6 sm:p-8 lg:p-10 space-y-5 sm:space-y-6">
                <div>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white mb-1">Mon Profil Client</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Gérez vos informations de compte et adresses de livraison</p>
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

                <form  method="POST" action="{{route('Modifier-Profil-Client')}}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                         @method('PUT')

                    <!-- Account Type Radio Toggle -->
                   
                        <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider block">Type</label>
                        <div class="flex gap-2">
                            <div class="flex items-center px-2.5 sm:px-3 bg-slate-100 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold">
                               <span class="material-symbols-outlined icon">storefront</span>
                            </div>
                            <div class="input-icon-wrapper flex-grow">
                                
                            <select class="form-input" id="type" name="type" required>
                                <option value="{{$infos->type}}">{{$infos->type}}</option>
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
                        </div>
                    </div>
                        <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider block">Lieu d'habitation</label>
                        <div class="flex gap-2">
                            <div class="flex items-center px-2.5 sm:px-3 bg-slate-100 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold">
                               <span class="material-symbols-outlined icon">home</span>
                            </div>
                            <div class="input-icon-wrapper flex-grow">
                                <select class="form-input" id="quartier" name="quartier" required>
                                 @foreach($quartiers as $quartier)
                                {{-- <option {{$infos->quartier_id === $quartier->id ? 'selected': ''}} value="{{ $quartier->id }}"> --}}
                                <option  value="{{ $quartier->id }}"
                                     @selected($infos?->quartier_id === $quartier->id)>
                                    {{ $quartier->nom_quartier }}
                                </option>
                            @endforeach
                            </select>
                            </div>
                        </div>
                    </div>


                    <!-- Full Name -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider block">Nom Complet</label>
                        <div class="input-icon-wrapper">
                            <span class="material-symbols-outlined icon">badge</span>
                            <input id="reg-fullname" type="text" class="form-input" placeholder="Ex: Jean Koffi" name="nom" value="{{$infos->nom}}" required>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider block">Adresse Email</label>
                        <div class="input-icon-wrapper">
                            <span class="material-symbols-outlined icon">mail</span>
                            <input type="email" class="form-input" placeholder="jean.koffi@gmail.com" value="{{$infos->email}}" name="email">
                        </div>
                    </div>

                    <!-- Phone Number -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider block">Téléphone (Côte d'Ivoire)</label>
                        <div class="flex gap-2">
                            <div class="flex items-center px-2.5 sm:px-3 bg-slate-100 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold">
                                +225
                            </div>
                            <div class="input-icon-wrapper flex-grow">
                                <span class="material-symbols-outlined icon">call</span>
                                <input type="tel" class="form-input" placeholder="07 00 00 00 00" value="{{$infos->telephone}}" name="tel" required>
                            </div>
                        </div>
                    </div>
                    <!-- Phone Contact -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider block">Contact (Côte d'Ivoire)</label>
                        <div class="flex gap-2">
                            <div class="flex items-center px-2.5 sm:px-3 bg-slate-100 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold">
                                +27
                            </div>
                            <div class="input-icon-wrapper flex-grow">
                                <span class="material-symbols-outlined icon">call</span>
                                <input type="tel" class="form-input" placeholder="07 00 00 00 00" value="{{$infos->contact}}" name="contact" >
                            </div>
                        </div>
                    </div>
                    <!-- Phone image -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider block">Image</label>
                        <div class="flex gap-2">
                            <div class="flex items-center px-2.5 sm:px-3 bg-slate-100 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold">
                                
                                <span class="material-symbols-outlined icon">account_box</span>
                            </div>
                            <div class="input-icon-wrapper flex-grow">
                                <input type="file" class="form-input" name="image" >
                            </div>
                        </div>
                    </div>
                    
                    <!-- Password -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider block">Mot de passe</label>
                        <div class="input-icon-wrapper">
                            <span class="material-symbols-outlined icon">lock</span>
                            <input id="passwordInput" type="password" name="motpassword" class="form-input" placeholder="••••••••">
                            <button type="button" id="togglePasswordBtn" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <span class="material-symbols-outlined text-lg">visibility</span>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-primary w-full py-3.5 text-xs sm:text-sm uppercase tracking-wider font-bold shadow-lg">
                        Mettre à jour mon profil
                    </button>
                </form>
            </div>
        </div>
    </main

   

@endsection