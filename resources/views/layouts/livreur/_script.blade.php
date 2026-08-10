<script src="{{asset('assets/livreur/js/app.js')}}"></script>
<script src="{{asset('assets/livreur/js/map.js')}}"></script>


<script>
    navigator.geolocation.getCurrentPosition((position) => {
    let lat = position.coords.latitude;
    let lng = position.coords.longitude;

    // On envoie le GPS à votre contrôleur Laravel
    fetch(`/livreur/livreur/commandes/disponibles/${lat}/${lng}`)
        .then(response => response.json())
        .then(commandes => {
            console.log("Voici les commandes autour de moi :", commandes);
        });
});




// === LA FONCTION QUI CRÉE VOTRE TOAST DYNAMIQUEMENT ===
function afficherToast(type, message) {
    // Supprime un ancien toast s'il y en a un
    const ancien = document.getElementById('toast-message-js');
    if (ancien) ancien.remove();

    const isSuccess = type === 'success';
    const borderColor = isSuccess ? 'border-emerald-500' : 'border-red-500';
    const iconColor = isSuccess ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400';
    const iconName = isSuccess ? 'check_circle' : 'error';

    // Crée le HTML du Toast
    const toastHTML = `
        <div id="toast-message-js" class="fixed top-5 right-5 z-50 flex items-center w-full max-w-sm p-4 space-x-3 bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border-l-4 ${borderColor} transition-all duration-500 animate-in fade-in slide-in-from-top-5" role="alert">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-xl ${iconColor}">
                <span class="material-symbols-outlined text-xl">${iconName}</span>
            </div>
            <div class="ml-3 text-sm font-bold text-slate-700 dark:text-slate-200">
                ${message}
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="ml-auto -mx-1.5 -my-1.5 text-slate-400 hover:text-slate-900 dark:hover:text-white rounded-lg p-1.5 hover:bg-slate-100 dark:hover:bg-slate-700 inline-flex items-center justify-center h-8 w-8 transition">
                <span class="material-symbols-outlined text-lg">close</span>
            </button>
        </div>
    `;

    // L'ajoute au document
    document.body.insertAdjacentHTML('beforeend', toastHTML);

    // Le supprime après 4 secondes
    setTimeout(() => {
        const toast = document.getElementById('toast-message-js');
        if(toast) {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 500);
        }
    }, 4000);
}


</script>
<!-- Gestion des notifications (Toasts) -->
@if (session('success') || session('error'))
<div id="toast-message" class="fixed top-5 right-5 z-50 flex items-center w-full max-w-sm p-4 space-x-3 bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border-l-4 {{ session('success') ? 'border-emerald-500' : 'border-red-500' }} transition-all duration-500" role="alert">
    
    <!-- Icône (Verte ou Rouge) -->
    <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-xl {{ session('success') ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400' }}">
        <span class="material-symbols-outlined text-xl">{{ session('success') ? 'check_circle' : 'error' }}</span>
    </div>
    
    <!-- Texte du message -->
    <div class="ml-3 text-sm font-bold text-slate-700 dark:text-slate-200">
        {{ session('success') ?? session('error') }}
    </div>
    
    <!-- Bouton Fermer -->
    <button type="button" onclick="document.getElementById('toast-message').remove()" class="ml-auto -mx-1.5 -my-1.5 text-slate-400 hover:text-slate-900 dark:hover:text-white rounded-lg p-1.5 hover:bg-slate-100 dark:hover:bg-slate-700 inline-flex items-center justify-center h-8 w-8 transition">
        <span class="material-symbols-outlined text-lg">close</span>
    </button>
</div>

<script>
    // Fait disparaître le toast tout seul après 5 secondes avec un effet fondu
    setTimeout(() => {
        const toast = document.getElementById('toast-message');
        if(toast) {
            toast.style.opacity = '0'; // Rend invisible
            setTimeout(() => toast.remove(), 500); // Supprime du code HTML après l'animation
        }
    }, 5000);
</script> 

 @endif

   <script>

function initStatusToggle(initialStatus) {
  // L'état initial n'est plus pris dans le navigateur, mais directement depuis la Base de Données
 
  let isOnline = {{ Auth::guard('livreur')->user()->disponible ? 'true' : 'false' }};

  function updateStatusUI() {
    document.querySelectorAll('.driver-status-badge').forEach(badge => {
      if (isOnline) {
        badge.className = 'driver-status-badge flex items-center gap-2 px-3 py-1 rounded-full badge-online text-xs font-bold transition-all';
        badge.innerHTML = `<span class="pulse-dot"></span><span>En ligne</span>`;
      } else {
        badge.className = 'driver-status-badge flex items-center gap-2 px-3 py-1 rounded-full bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-300 text-xs font-bold transition-all';
        badge.innerHTML = `<span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span><span>Hors ligne</span>`;
      }
    });
  }

  // Met à jour l'affichage au chargement de la page
  updateStatusUI();

  window.toggleDriverStatus = function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // On envoie l'ordre de changement à Laravel
    fetch('{{ route('Livreur-Disponibilite') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Le serveur nous confirme le nouveau statut
            isOnline = data.is_online;
            
            // On met à jour l'interface
            updateStatusUI();
            
            // On affiche le message (compatible avec votre ancienne fonction showToast ou afficherToast)
            const message = isOnline ? 'Vous êtes maintenant EN LIGNE' : 'Vous êtes maintenant HORS LIGNE';
            if (typeof afficherToast === 'function') afficherToast('success', message);
            else if (typeof showToast === 'function') showToast(message);
        } else {
            alert("Impossible de changer le statut.");
        }
    })
    .catch(() => {
        alert('Erreur réseau lors de la mise à jour du statut.');
    });
  };
}
</script>

