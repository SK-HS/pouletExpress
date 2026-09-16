
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('notif-bell-btn');
    const dropdown = document.getElementById('notif-dropdown');
    const badge = document.getElementById('notif-badge');
    const list = document.getElementById('notif-list');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const iconesCouleurs = {
        emerald: 'text-emerald-600 bg-emerald-50 dark:bg-emerald-900/30',
        blue:    'text-blue-600 bg-blue-50 dark:bg-blue-900/30',
        amber:   'text-amber-600 bg-amber-50 dark:bg-amber-900/30',
        red:     'text-red-600 bg-red-50 dark:bg-red-900/30',
        slate:   'text-slate-600 bg-slate-50 dark:bg-slate-700/30',
    };

    // Ouvre/ferme le dropdown 
    btn.addEventListener('click', function (e) {
        e.stopPropagation();
        dropdown.classList.toggle('hidden');
    });

    document.addEventListener('click', function (e) {
        if (!dropdown.contains(e.target) && !btn.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });

    // ── Rendu de la liste 
    function afficherNotifications(notifications) {
        if (notifications.length === 0) {
            list.innerHTML = `
                <div class="p-8 text-center text-slate-400 text-sm">
                    <span class="material-symbols-outlined text-3xl mb-2 block">notifications_off</span>
                    Aucune notification pour le moment.
                </div>`;
            return;
        }

        list.innerHTML = notifications.map(function (n) {
            const style = iconesCouleurs[n.couleur] || iconesCouleurs.slate;
            const contenu = `
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 ${style}">
                        <span class="material-symbols-outlined text-lg">${n.icone}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-sm text-slate-900 dark:text-white">${n.titre}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">${n.message}</p>
                        <p class="text-[10px] text-slate-400 mt-1">${n.created_at}</p>
                    </div>
                </div>`;

            return n.lien
                ? `<a href="${n.lien}" class="notif-item block p-4 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition" data-id="${n.id}">${contenu}</a>`
                : `<div class="notif-item p-4 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700/50 transition" data-id="${n.id}">${contenu}</div>`;
        }).join('');

        // Marque comme lue au clic
        list.querySelectorAll('.notif-item').forEach(function (item) {
            item.addEventListener('click', function () {
                const id = this.dataset.id;
                fetch(`/notifications/${id}/lue`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                }).catch(() => {});
            }); 
        });
    }

    // ── Marquer tout comme lu
    document.getElementById('notif-tout-lire').addEventListener('click', function () {
        fetch('/notifications/toutes-lues', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
        })
        .then(() => {
            badge.classList.add('hidden');
            polling(); // rafraîchit immédiatement
        })
        .catch(() => {});
    });

    // ── Polling 
let ancienCount = 0;
let premierPolling = true; //permet de distinguer "chargement initial" de "nouvelle arrivée"
const audioNotif = new Audio('/storage/notif/notif.mp3'); //créé une seule fois, pas à chaque poll

function polling() {
    fetch('{{ route("notifications.polling") }}', { headers: { 'Accept': 'application/json' } })
        .then(res => res.json())
        .then(data => {

            // Le son ne joue QUE si le compteur augmente APRÈS le premier chargement
            if (!premierPolling && data.count > ancienCount) {
                audioNotif.currentTime = 0; // permet de rejouer même si le précédent son n'est pas terminé
                audioNotif.play().catch(() => {});
            }

            premierPolling = false;
            ancienCount = data.count;

            if (data.count > 0) {
                badge.textContent = data.count > 99 ? '99+' : data.count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
            afficherNotifications(data.notifications);
        })
        .catch(() => {});
}

polling();
setInterval(polling, 20000);

// Débloque l'audio dès le premier clic n'importe où sur la page
document.addEventListener('click', function débloquerAudio() {
    audioNotif.play().then(() => {
        audioNotif.pause();
        audioNotif.currentTime = 0;
    }).catch(() => {});
    document.removeEventListener('click', débloquerAudio);
}, { once: true });

});
</script>