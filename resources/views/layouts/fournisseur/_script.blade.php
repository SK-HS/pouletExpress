<script src="{{asset('assets/fournisseur/js/main.js')}}"></script>

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
document.addEventListener('DOMContentLoaded', function() {
    
    let anciennesCommandes = 0; 

    // Fonction qui crée un véritable "BIP" électronique
    function faireUnBip() {
        const AudioContext = window.AudioContext || window.webkitAudioContext;
        if (!AudioContext) return; 
        
        const ctx = new AudioContext();
        const oscillateur = ctx.createOscillator();
        const volume = ctx.createGain();
        
        oscillateur.type = 'sine'; 
        oscillateur.frequency.value = 800; 
        volume.gain.value = 0.5; 
        
        oscillateur.connect(volume);
        volume.connect(ctx.destination);
        
        oscillateur.start();
        oscillateur.stop(ctx.currentTime + 0.3); 
    }

    // Fonction qui vérifie les commandes
    function verifierNotifications() {
        fetch('{{ route('Commande-Alert') }}')
            .then(response => response.json())
            .then(data => {
                // On récupère les éléments HTML
                const badge = document.getElementById('badge-notif');
                const messageDiv = document.getElementById('notif-message');
                
                // On récupère le chiffre renvoyé par PHP
                const nbActuel = data.nouvelleCommande;
                
                // 1. Déclenchement du BIP (si augmentation)
                if (nbActuel > anciennesCommandes) {
                    faireUnBip(); 
                }
                anciennesCommandes = nbActuel;
                
                // 2. Mise à jour de l'affichage (Badge + Texte du Menu)
                if (nbActuel > 0) {
                    // On affiche le petit point rouge
                    badge.textContent = nbActuel;
                    badge.classList.remove('hidden');
                    
                    // On affiche le beau texte rouge dans le menu
                    messageDiv.innerHTML = `Vous avez <strong class="text-status-error">${nbActuel} nouvelle(s) commande(s)</strong> en attente.`;
                } else {
                    // On cache le point rouge et on met un texte normal
                    badge.classList.add('hidden');
                    messageDiv.innerHTML = "Vous n'avez aucune nouvelle commande.";
                }
            })
            .catch(error => console.error('Erreur:', error));
    }

    // On lance une première vérification au chargement
    verifierNotifications();
    
    // On vérifie ensuite toutes les 5 SECONDES (5000 millisecondes)
    setInterval(verifierNotifications, 5000); 
});
</script>