@extends('layouts.fournisseur.main')
@section('content')

<main class="lg:ml-64 p-margin-mobile pb-28 md:p-margin-desktop space-y-6 animate-in fade-in duration-500">

    <!-- En-tête & Solde -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-2">
        <div>
            <h2 class="font-headline-md text-on-surface">Mes Retraits</h2>
            <p class="font-body-md text-on-surface-variant">Historique de vos demandes de paiement</p>
        </div>
        
        <div class="flex items-center gap-4 bg-surface-container-lowest p-4 rounded-xl border border-outline-variant shadow-sm w-full md:w-auto">
            <div class="flex-1 md:flex-none">
                <p class="font-label-caps text-label-caps text-on-surface-variant mb-1 text-[10px]">Solde Disponible</p>
                <p class="font-headline-sm text-primary font-bold">{{ number_format($fournisseur->compte, 0, ',', ' ') }} FCFA</p>
            </div>
            <!-- Bouton Nouveau (Rond comme dans votre profil) -->
            <button onclick="openRetraitModal()" class="px-5 py-2.5 bg-primary text-on-primary hover:opacity-90 rounded-full font-label-md shadow-lg shadow-primary/20 transition flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">add</span>
                Nouveau
            </button>
        </div>
    </div>

    <!-- Alertes -->
    @if(session('success'))
        <div class="p-4 bg-green-100 text-green-700 rounded-xl font-label-md border border-green-200 flex items-center gap-2 shadow-sm">
            <span class="material-symbols-outlined text-lg">check_circle</span>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-red-100 text-red-700 rounded-xl font-label-md border border-red-200 flex items-center gap-2 shadow-sm">
            <span class="material-symbols-outlined text-lg">error</span>
            {{ session('error') }}
        </div>
    @endif

    <!-- ==================== BARRE DE FILTRES ==================== -->
    <form method="GET" action="{{ url()->current() }}" class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant shadow-sm flex flex-col sm:flex-row gap-4 items-end">
        <div class="flex-1 w-full">
            <label class="font-label-caps text-[10px] text-on-surface-variant mb-1.5 block">Filtrer par date</label>
            <input type="date" name="date" value="{{ request('date') }}" class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" />
        </div>
        
        <div class="flex-1 w-full">
            <label class="font-label-caps text-[10px] text-on-surface-variant mb-1.5 block">Filtrer par statut</label>
            <select name="statut" class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface">
                <option value="">Tous les statuts</option>
                <option value="EN_ATTENTE" {{ request('statut') == 'EN_ATTENTE' ? 'selected' : '' }}>En attente</option>
                <option value="TRAITEE" {{ request('statut') == 'TRAITEE' ? 'selected' : '' }}>Traitée (Payée)</option>
                <option value="REJETEE" {{ request('statut') == 'REJETEE' ? 'selected' : '' }}>Rejetée</option>
            </select>
        </div>

        <div class="w-full sm:w-auto flex gap-2">
            @if(request('date') || request('statut'))
                <a href="{{ url()->current() }}" class="px-4 py-2.5 bg-surface-container-highest text-on-surface-variant hover:bg-surface-variant hover:text-on-surface rounded-xl font-label-md transition flex items-center justify-center" title="Effacer">
                    <span class="material-symbols-outlined text-sm">close</span>
                </a>
            @endif
            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-secondary-container text-on-secondary-container rounded-xl font-label-md transition flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-sm">search</span>
                Filtrer
            </button>
        </div>
    </form>

    <!-- Tableau Historique -->
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm overflow-hidden w-full">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant text-on-surface-variant text-[11px] uppercase tracking-wider font-bold">
                        <th class="p-4">Date</th>
                        <th class="p-4">Montant</th>
                        <th class="p-4">Méthode</th>
                        <th class="p-4">Statut</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/50 text-sm">
                    @forelse($demandes as $demande)
                    <tr class="hover:bg-surface-container-low/50 transition">
                        <td class="p-4 text-on-surface font-body-md">
                            {{ $demande->created_at->format('d/m/Y à H:i') }}
                        </td>
                        <td class="p-4 font-body-md-bold text-primary">
                            {{ number_format($demande->montant, 0, ',', ' ') }} FCFA
                        </td>
                        <td class="p-4 text-on-surface-variant font-body-md">
                            <span class="capitalize font-bold">{{ $demande->mode_paiement }}</span><br>
                            <span class="text-[11px]">{{ $demande->numero_paiement }}</span>
                        </td>
                        <td class="p-4">
                            <!-- Badges M3 Identiques à ceux de votre profil -->
                            @if($demande->statut == 'EN_ATTENTE')
                                <span class="bg-secondary-container text-on-secondary-container px-3 py-1 rounded-full font-label-sm border border-secondary-container flex items-center gap-1 w-max">
                                    <span class="material-symbols-outlined text-[14px]">pending</span> En attente
                                </span>
                            @elseif($demande->statut == 'TRAITEE')
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full font-label-sm border border-green-200 flex items-center gap-1 w-max">
                                    <span class="material-symbols-outlined text-[14px]">check_circle</span> Payé
                                </span>
                            @else
                                <span class="bg-error-container text-on-error-container px-3 py-1 rounded-full font-label-sm border border-error-container flex items-center gap-1 w-max">
                                    <span class="material-symbols-outlined text-[14px]">cancel</span> Rejeté
                                </span>
                            @endif
                        </td>
                        <td class="p-4 text-right">
                            @if($demande->statut == 'EN_ATTENTE')
                                <button onclick="openEditModal({{ $demande->id }}, {{ $demande->montant }}, '{{ $demande->mode_paiement }}', '{{ $demande->numero_paiement }}')" class="p-2 text-primary bg-primary/10 hover:bg-primary/20 rounded-lg transition inline-flex items-center" title="Modifier">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                    Modifier
                                </button>
                            @else
                                <span class="text-on-surface-variant text-[11px] italic">Verrouillé</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-on-surface-variant">
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
  <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeRetraitModal()"></div>
  <div class="relative bg-surface-container-lowest rounded-3xl w-full max-w-md mx-4 shadow-2xl border border-outline-variant overflow-hidden transform transition-all">
    <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-low/50">
      <h3 class="font-headline-sm text-on-surface flex items-center gap-2">
          <span class="material-symbols-outlined text-primary">payments</span>
          Nouveau Retrait
      </h3>
      <button onclick="closeRetraitModal()" class="text-on-surface-variant hover:text-error transition"><span class="material-symbols-outlined">close</span></button>
    </div>
    
    <form action="{{ route('Nouvelle-Demande-Retrait-Fournisseur') }}" method="POST" class="p-6 space-y-5">
      @csrf
      
      <!-- Info Solde (Style M3) -->
      <div class="p-4 bg-primary/10 border border-primary/20 rounded-xl flex justify-between items-center">
        <span class="font-label-md text-on-surface-variant">Solde disponible :</span>
        <span class="font-headline-sm text-primary font-bold">{{ number_format($fournisseur->compte, 0, ',', ' ') }} FCFA</span>
      </div>

      <div>
        <label class="font-label-caps text-[10px] text-on-surface-variant uppercase">Montant à retirer</label>
        <input type="number" name="montant" min="1000" max="{{ $fournisseur->compte }}" required class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" placeholder="Ex: 50000" />
      </div>
      <div>
        <label class="font-label-caps text-[10px] text-on-surface-variant uppercase">Mode de paiement</label>
        <select name="mode_paiement" required class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface">
          <option value="wave">Wave</option>
          <option value="mobile">Mobile Money</option>
          <option value="espece">Espèce</option>
        </select>
      </div>
      <div>
        <label class="font-label-caps text-[10px] text-on-surface-variant uppercase">Numéro / Identifiant</label>
        <input type="text" name="numero_paiement" required class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" placeholder="Ex: 0102030405" />
      </div>
      
      <div class="flex gap-3 pt-4">
        <button type="button" onclick="closeRetraitModal()" class="flex-1 py-3 bg-surface-container-highest text-on-surface rounded-full font-label-md hover:bg-surface-variant transition">Annuler</button>
        <button type="submit" class="flex-1 py-3 bg-primary text-on-primary rounded-full font-label-md hover:opacity-90 shadow-lg shadow-primary/20 transition">Valider</button>
      </div>
    </form>
  </div>
</div>

<!-- ==================== MODAL 2 : MODIFIER RETRAIT ==================== -->
<div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
  <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeEditModal()"></div>
  <div class="relative bg-surface-container-lowest rounded-3xl w-full max-w-md mx-4 shadow-2xl border border-outline-variant overflow-hidden transform transition-all">
    <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-low/50">
      <h3 class="font-headline-sm text-on-surface flex items-center gap-2">
          <span class="material-symbols-outlined text-primary">edit</span>
          Modifier Retrait
      </h3>
      <button onclick="closeEditModal()" class="text-on-surface-variant hover:text-error transition"><span class="material-symbols-outlined">close</span></button>
    </div>
    
    <form id="editForm" action="{{ route('Update-Demande-Retrait-Fournisseur') }}" method="POST" class="p-6 space-y-5">
      @csrf
      @method('PUT')
      <input type="hidden" name="demande_id" id="edit_id">
      
      <div>
        <label class="font-label-caps text-[10px] text-on-surface-variant uppercase">Montant</label>
        <input type="number" name="montant" id="edit_montant" required class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" />
      </div>
      <div>
        <label class="font-label-caps text-[10px] text-on-surface-variant uppercase">Mode de paiement</label>
        <select name="mode_paiement" id="edit_mode" required class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface">
            <option value="espece">Espèce</option>
          <option value="wave">Wave</option>
          <option value="mobile">Mobile Money</option>
        </select>
      </div>
      <div>
        <label class="font-label-caps text-[10px] text-on-surface-variant uppercase">Numéro</label>
        <input type="text" name="numero_paiement" id="edit_numero" required class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest text-on-surface" />
      </div>
      
      <div class="flex gap-3 pt-4">
        <button type="button" onclick="closeEditModal()" class="flex-1 py-3 bg-surface-container-highest text-on-surface rounded-full font-label-md hover:bg-surface-variant transition">Annuler</button>
        <button type="submit" class="flex-1 py-3 bg-primary text-on-primary rounded-full font-label-md hover:opacity-90 shadow-lg shadow-primary/20 transition">Enregistrer</button>
      </div>
    </form>
  </div>
</div>

<script>
  function openRetraitModal() { document.getElementById('retraitModal').classList.remove('hidden'); }
  function closeRetraitModal() { document.getElementById('retraitModal').classList.add('hidden'); }

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
