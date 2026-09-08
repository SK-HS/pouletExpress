@extends('layouts.livreur.main')
@section('content')

<!-- Notez la suppression de max-w-7xl, maintenant ça prend 100% de l'espace (w-full) -->
<main class="md:ml-64 w-full md:w-[calc(100%-16rem)] pb-20 md:pb-15 p-3 sm:p-4 md:p-8 mx-auto space-y-4 sm:space-y-6">

    <!-- En-tête & Solde -->
   <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white">Mes Retraits</h2>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">Historique de vos paiements et demandes</p>
        </div>
        
        <div class="flex items-center gap-4 bg-white dark:bg-slate-800 p-3 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm w-full md:w-auto">
            <div class="flex-1 md:flex-none">
                <p class="text-[10px] text-slate-500 dark:text-slate-400 uppercase font-bold tracking-widest">Solde Disponible</p>
                <p class="text-lg font-extrabold text-emerald-600 dark:text-emerald-400">{{ number_format($livreur->compte, 0, ',', ' ') }} FCFA</p>
            </div>
            <button onclick="openRetraitModal()" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg font-bold text-sm shadow-sm transition flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">add</span>
                Nouveau
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 text-emerald-700 rounded-xl font-bold text-sm border border-emerald-200">{{ session('success') }}</div>
    @endif

     @if ($errors->any())
        <div class="bg-error/10 border border-error text-error rounded-xl p-4 mb-6 text-sm">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

    <!-- ==================== BARRE DE FILTRES ==================== -->
    <!-- Mettez la bonne route de votre page d'affichage dans action="" -->
    <form method="GET" action="{{ url()->current() }}" class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col sm:flex-row gap-4 items-end">
        
        <!-- Filtre Date -->
        <div class="flex-1 w-full">
            <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Filtrer par date</label>
            <input type="date" name="date" value="{{ request('date') }}" class="w-full px-4 py-2 mt-1 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 text-slate-900 dark:text-white transition" />
        </div>
        
        <!-- Filtre Statut -->
        <div class="flex-1 w-full">
            <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Filtrer par statut</label>
            <select name="statut" class="w-full px-4 py-2 mt-1 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 text-slate-900 dark:text-white transition">
                <option value="">Tous les statuts</option>
                <option value="EN_ATTENTE" {{ request('statut') == 'EN_ATTENTE' ? 'selected' : '' }}>En attente</option>
                <option value="TRAITEE" {{ request('statut') == 'TRAITEE' ? 'selected' : '' }}>Traitée (Payée)</option>
                <option value="REJETEE" {{ request('statut') == 'REJETEE' ? 'selected' : '' }}>Rejetée</option>
            </select>
        </div>

        <!-- Boutons de filtrage -->
        <div class="w-full sm:w-auto flex gap-2">
            <!-- Bouton Réinitialiser (si un filtre est actif) -->
            @if(request('date') || request('statut'))
                <a href="{{ url()->current() }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-600 dark:text-white rounded-xl font-bold text-sm transition flex items-center justify-center" title="Effacer les filtres">
                    <span class="material-symbols-outlined text-sm">close</span>
                </a>
            @endif
            
            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 text-white rounded-xl font-bold text-sm shadow-sm transition flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-sm">search</span>
                Rechercher
            </button>
        </div>
    </form>

    <!-- Tableau Historique (Largeur maximale) -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden w-full">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider font-bold">
                        <th class="p-4 border-b border-slate-200 dark:border-slate-700">Date</th>
                        <th class="p-4 border-b border-slate-200 dark:border-slate-700">Montant</th>
                        <th class="p-4 border-b border-slate-200 dark:border-slate-700">Méthode</th>
                        <th class="p-4 border-b border-slate-200 dark:border-slate-700">Statut</th>
                        <th class="p-4 border-b border-slate-200 dark:border-slate-700 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-sm">
                    @forelse($demandes as $demande)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                        <td class="p-4 text-slate-900 dark:text-white font-medium">
                            {{ $demande->created_at->format('d/m/Y à H:i') }}
                        </td>
                        <td class="p-4 font-bold text-slate-900 dark:text-white">
                            {{ number_format($demande->montant, 0, ',', ' ') }} FCFA
                        </td>
                        <td class="p-4 text-slate-600 dark:text-slate-300">
                            <span class="capitalize font-semibold">{{ $demande->mode_paiement }}</span><br>
                            <span class="text-xs text-slate-400">{{ $demande->numero_paiement }}</span>
                        </td>
                        <td class="p-4">
                            @if($demande->statut == 'EN_ATTENTE')
                                <span class="px-2.5 py-1 bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 rounded-full text-xs font-bold">En attente</span>
                            @elseif($demande->statut == 'TRAITEE')
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 rounded-full text-xs font-bold">Payé</span>
                            @else
                                <span class="px-2.5 py-1 bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 rounded-full text-xs font-bold">Rejeté</span>
                            @endif
                        </td>
                        <td class="p-4 text-right">
                            @if($demande->statut == 'EN_ATTENTE')
                                <button onclick="openEditModal({{ $demande->id }}, {{ $demande->montant }}, '{{ $demande->mode_paiement }}', '{{ $demande->numero_paiement }}')" class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition" title="Modifier">
                                    <span class="material-symbols-outlined text-sm">edit</span>
                                    Modifier
                                </button>
                            @else
                                <span class="text-slate-400 text-xs italic">Verrouillé</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-slate-500">
                            Aucune demande de retrait trouvée.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
         @if($demandes->hasPages())
        <div class="p-4 border-t border-outline-variant bg-surface-container-lowest">
            {{ $demandes->links() }}
        </div>
        @endif
    </div>
</main>


<!-- ==================== MODAL 1 : NOUVEAU RETRAIT ==================== -->
<div id="retraitModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
  <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeRetraitModal()"></div>
  <div class="relative bg-white dark:bg-slate-800 rounded-2xl w-full max-w-md mx-4 shadow-2xl">
    <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between">
      <h3 class="font-extrabold text-slate-900 dark:text-white">Nouvelle demande</h3>
      <button onclick="closeRetraitModal()" class="text-slate-400 hover:text-red-500"><span class="material-symbols-outlined">close</span></button>
    </div>
    
    <form action="{{ route('Nouvelle-Demande-Retrait-Livreur') }}" method="POST" class="p-6 space-y-4">
      @csrf
      <div>
        <label class="text-xs font-bold text-slate-500 uppercase">Montant</label>
        <input type="number" name="montant" min="1000" max="{{ $livreur->compte }}" required class="w-full px-4 py-2 mt-1 bg-slate-50 border border-slate-200 rounded-xl" />
      </div>
      <div>
        <label class="text-xs font-bold text-slate-500 uppercase">Mode de paiement</label>
        <select name="mode_paiement" required class="w-full px-4 py-2 mt-1 bg-slate-50 border border-slate-200 rounded-xl">
            <option value="espece">Espèce</option>
          <option value="wave">Wave</option>
          <option value="mobile">Mobile Money</option>
        </select>
      </div>
      <div>
        <label class="text-xs font-bold text-slate-500 uppercase">Numéro</label>
        <input type="text" name="numero_paiement" required class="w-full px-4 py-2 mt-1 bg-slate-50 border border-slate-200 rounded-xl" />
      </div>
      <button type="submit" class="w-full py-2.5 mt-2 bg-emerald-700 text-white rounded-xl font-bold">Valider</button>
    </form>
  </div>
</div>
<!-- ==================== MODAL 2 : MODIFIER RETRAIT ==================== -->
<div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
  <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeEditModal()"></div>
  <div class="relative bg-white dark:bg-slate-800 rounded-2xl w-full max-w-md mx-4 shadow-2xl">
    <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between">
      <h3 class="font-extrabold text-slate-900 dark:text-white">Modifier la demande</h3>
      <button onclick="closeEditModal()" class="text-slate-400 hover:text-red-500"><span class="material-symbols-outlined">close</span></button>
    </div>
    
    <!-- On utilise une route d'update, on passera l'ID via JS -->
    <form id="editForm" action="{{ route('Update-Demande-Retrait-Livreur') }}" method="POST" class="p-6 space-y-4">
      @csrf
      @method('PUT')
      <!-- Champ caché pour l'ID de la demande -->
      <input type="hidden" name="demande_id" id="edit_id">
      
      <div>
        <label class="text-xs font-bold text-slate-500 uppercase">Montant</label>
        <input type="number" name="montant" id="edit_montant" required class="w-full px-4 py-2 mt-1 bg-slate-50 border border-slate-200 rounded-xl" />
      </div>
      <div>
        <label class="text-xs font-bold text-slate-500 uppercase">Mode de paiement</label>
        <select name="mode_paiement" id="edit_mode" required class="w-full px-4 py-2 mt-1 bg-slate-50 border border-slate-200 rounded-xl">
          <option value="wave">Wave</option>
          <option value="mobile">Mobile Money</option>
          <option value="espece">Espèce</option>
        </select>
      </div>
      <div>
        <label class="text-xs font-bold text-slate-500 uppercase">Numéro</label>
        <input type="text" name="numero_paiement" id="edit_numero" required class="w-full px-4 py-2 mt-1 bg-slate-50 border border-slate-200 rounded-xl" />
      </div>
      <button type="submit" class="w-full py-2.5 mt-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold transition">Enregistrer les modifications</button>
    </form>
  </div>
</div>

<script>
  // Modal Nouveau
  function openRetraitModal() { document.getElementById('retraitModal').classList.remove('hidden'); }
  function closeRetraitModal() { document.getElementById('retraitModal').classList.add('hidden'); }
  // Modal Modifier (Avec pré-remplissage des données)
  function openEditModal(id, montant, mode, numero) {
      document.getElementById('edit_id').value = id;
      document.getElementById('edit_montant').value = montant;
      document.getElementById('edit_mode').value = mode;
      document.getElementById('edit_numero').value = numero;
      document.getElementById('editModal').classList.remove('hidden');
  }
  function closeEditModal() { document.getElementById('editModal').classList.add('hidden'); }
</script>


@endsection

