@extends('layouts.livreur.main')
@section('content')
 
  <!-- ==================== MAIN CONTENT AREA ==================== -->
  <main class="md:ml-64 flex-1 pb-20 md:pb-8 p-3 sm:p-4 md:p-8 max-w-7xl mx-auto space-y-4 sm:space-y-6">
    
    <form action="{{ route('Livreur-Profil-Update') }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <!-- Section Header -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <div>
          <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white">Paramètres du compte</h2>
          <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">Gérez vos informations de livraison et de facturation</p>
        </div>
        <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl font-bold text-sm shadow-sm transition flex justify-center items-center gap-2">
          <span class="material-symbols-outlined text-sm">save</span>
          Enregistrer les modifications
        </button>
      </div>
      <!-- Success/Error Alerts -->
      @if (session('success'))
      <div class="mb-4 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-sm font-bold">
        {{ session('success') }}
      </div>
      @endif
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Profile Card & Account Balance -->
        <div class="space-y-6 lg:col-span-1">
          
          <!-- Profile Picture Card -->
          <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 flex flex-col items-center text-center shadow-sm">
            <div class="relative mb-4 group cursor-pointer">
              <img src="{{ $livreur->image ? asset('storage/'.$livreur->image) : 'https://ui-avatars.com/api/?name='.urlencode($livreur->nom).'&background=047857&color=fff' }}" alt="Profile" class="w-24 h-24 rounded-full object-cover border-4 border-slate-50 dark:border-slate-700 shadow-md">
              <label for="imageUpload" class="absolute inset-0 bg-black/40 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition cursor-pointer">
                <span class="material-symbols-outlined text-white">photo_camera</span>
              </label>
              <input type="file" id="imageUpload" name="image" class="hidden" accept="image/*">
            </div>
            <h3 class="font-extrabold text-lg text-slate-900 dark:text-white">{{ $livreur->nom }}</h3>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mb-3">Réf: {{ $livreur->reference }}</p>
            
            <div class="flex flex-wrap items-center gap-2 justify-center mt-2">
              <!-- Disponibilité Toggle -->
              <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" name="disponible" value="1" class="sr-only peer" {{ $livreur->disponible ? 'checked' : '' }}>
                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-emerald-600"></div>
                <span class="ml-3 text-xs font-bold text-slate-700 dark:text-slate-300">Disponible pour livrer</span>
              </label>
            </div>
          </div>
          <!-- Account / Wallet Card -->
          <div class="bg-gradient-to-br from-emerald-700 to-emerald-900 rounded-2xl p-6 text-white shadow-md shadow-emerald-900/20 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10">
              <span class="material-symbols-outlined text-6xl">account_balance_wallet</span>
            </div>
            <h4 class="text-emerald-100 text-sm font-semibold mb-1 uppercase tracking-wider">Solde du Compte</h4>
            <div class="text-3xl font-extrabold mb-4">{{ number_format($livreur->compte, 0, ',', ' ') }} <span class="text-lg font-bold text-emerald-200">FCFA</span></div>
            <a href="{{route('Demande-Retrait-Livreur')}}" class="w-full py-2.5 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-xl text-white font-bold text-sm transition flex justify-center items-center gap-2">
              <span class="material-symbols-outlined text-sm">payments</span>
              Demander un retrait
            </a>
          </div>
        </div>
        <!-- Right Column: Edit Form -->
        <div class="lg:col-span-2">
          <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-4 sm:p-6">
            <h3 class="text-lg font-extrabold text-slate-900 dark:text-white border-b border-slate-100 dark:border-slate-700 pb-4 mb-6">
              Informations Personnelles
            </h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
              <!-- Nom -->
              <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Nom complet</label>
                <input type="text" name="nom" value="{{ old('nom', $livreur->nom) }}" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 transition text-slate-900 dark:text-white" />
                @error('nom') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
              </div>
              
              <!-- Email -->
              <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Adresse E-mail</label>
                <input type="email" name="email" value="{{ old('email', $livreur->email) }}" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 transition text-slate-900 dark:text-white" />
                @error('email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
              </div>
              <!-- Téléphone -->
              <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Téléphone principal</label>
                <input type="tel" name="telephone" value="{{ old('telephone', $livreur->telephone) }}" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 transition text-slate-900 dark:text-white" />
              </div>
              <!-- Contact Urgence -->
              <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Contact secondaire</label>
                <input type="tel" name="contact" value="{{ old('contact', $livreur->contact) }}" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 transition text-slate-900 dark:text-white" />
              </div>
            </div>
            <!-- Adresse -->
            <div class="space-y-1.5 mt-5">
              <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Adresse de résidence</label>
              <input type="text" name="adresse" value="{{ old('adresse', $livreur->adresse) }}" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 transition text-slate-900 dark:text-white" />
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mt-5">
              <!-- Quartier ID -->
              <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Quartier (Secteur)</label>
                <select name="quartier_id" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 transition text-slate-900 dark:text-white">
                  <!-- Exemple de boucle si vous passez la variable $quartiers -->
                   @foreach($quartiers as $quartier)
                    <option value="{{ $quartier->id }}" {{ old('quartier_id', $livreur->quartier_id) == $quartier->id ? 'selected' : '' }}>
                      {{ $quartier->nom_quartier }}
                    </option>
                  @endforeach 
                </select>
              </div>
              <!-- Type de Véhicule -->
              <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Type de véhicule</label>
                <select name="type" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 transition text-slate-900 dark:text-white">
              
                    <option value="VELO" {{ old('type') == 'VELO' ? 'selected' : '' }}>VELO</option>
                            <option value="MOTO" {{ old('type' , $livreur->type) == 'MOTO' ? 'selected' : '' }}>MOTO</option>
                            <option value="TRICYCLE" {{ old('type' , $livreur->type) == 'TRICYCLE' ? 'selected' : '' }}>TRICYCLE</option>
                            <option value="VOITURE" {{ old('type' , $livreur->type) == 'VOITURE' ? 'selected' : '' }}>VOITURE</option>
                            <option value="CAMIONNETTE" {{ old('type' , $livreur->type) == 'CAMIONNETTE' ? 'selected' : '' }}>CAMIONNETTE</option>
                            <option value="CAMION" {{ old('type' , $livreur->type) == 'CAMION' ? 'selected' : '' }}>CAMION</option>
                        </select>
                </select>
              </div>
            </div>
            
            <!-- Sécurité / Mot de passe -->
            <h3 class="text-lg font-extrabold text-slate-900 dark:text-white border-b border-slate-100 dark:border-slate-700 pb-4 mb-6 mt-8 pt-4">
              Sécurité
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
              <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Nouveau mot de passe</label>
                
                <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">lock</span>
                <input type="password" id="password" name="password" placeholder="••••••••" required class="w-full pl-10 pr-10 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 transition" />
                <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                  <span id="passIcon" class="material-symbols-outlined text-lg">visibility</span>
                </button>
              </div>
              </div>
              <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Confirmer le mot de passe</label>
                
              <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">lock</span>
            <input type="password" id="password_confirmation"  name="password_confirmation" placeholder="••••••••" required class="w-full pl-10 pr-10 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 transition" />
            <button type="button" onclick="togglePasswordconfirm()" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
              <span id="passIcon2" class="material-symbols-outlined text-lg">visibility</span>
            </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
         <div class="h-20 md:hidden w-full"></div>
    </form>
  </main>

  <script>
   function togglePassword() {
    const passwordInput = document.getElementById('password');
    const icon = document.getElementById('passIcon');
    if (!passwordInput) return;
    if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      icon.textContent = 'visibility_off';
    } else {
      passwordInput.type = 'password';
      icon.textContent = 'visibility';
    }
  };
   function togglePasswordconfirm() {
    const passwordInput = document.getElementById('password_confirmation');
    const icon = document.getElementById('passIcon2');
    if (!passwordInput) return;
    if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      icon.textContent = 'visibility_off';
    } else {
      passwordInput.type = 'password';
      icon.textContent = 'visibility';
    }
  };
    
</script>

@endsection
