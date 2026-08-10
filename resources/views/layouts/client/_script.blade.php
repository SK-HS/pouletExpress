 <script src="{{asset('assets/client/js/script.js')}}"></script>


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