<script>

// Attache les boutons avec délégation (fonctionne même si les boutons
// sont chargés après le script, ou générés dynamiquement)
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.add-to-cart-btn');
    if (!btn) return;

    e.preventDefault();

    const produitId = btn.getAttribute('data-produit-id');
    if (!produitId) {
        console.error('data-produit-id manquant sur le bouton');
        return;
    }

    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    if (!csrfMeta) {
        console.error('meta[name="csrf-token"] introuvable dans le <head>');
        return;
    }

    btn.disabled = true;
    btn.classList.add('opacity-60');

    fetch('/panier/ajouter', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfMeta.getAttribute('content'),
            'Accept': 'application/json',
        },
        body: JSON.stringify({ produit_id: produitId, quantite: 1 }),
    })
    .then(function (res) {
        // Log brut pour voir exactement ce que le serveur renvoie
        console.log('Status HTTP :', res.status);
        return res.json();
    })
    .then(function (data) {
        console.log('Data reçue :', data);

        btn.disabled = false;
        btn.classList.remove('opacity-60');

        if (!data.success) {
            console.error('success = false :', data);
            return;
        }

        // Cherche le badge
        const badge = document.getElementById('cartCountBadge');
        console.log('Badge trouvé :', badge);
        document.getElementById("cart-link").addEventListener("click", function(e) {
            const count = parseInt(document.getElementById("cartCountBadge")?.textContent || 0);

            if (count === 0) {
                e.preventDefault();
            }
        });
        if (badge) {
            badge.textContent = data.count;
            badge.classList.remove('hidden');
            badge.classList.add('scale-150');
            setTimeout(() => badge.classList.remove('scale-150'), 200);
        } else {
            console.warn('Badge #cartCountBadge introuvable — regarde si l\'ID est bien dans le HTML rendu');
        }

        // Feedback bouton
        btn.classList.add('bg-emerald-600', 'scale-110');
        btn.innerHTML = '<span class="material-symbols-outlined">check</span>';
        setTimeout(function () {
            btn.classList.remove('bg-emerald-600', 'scale-110');
            btn.innerHTML = '<span class="material-symbols-outlined">add_shopping_cart</span>';
        }, 1500);
    })
    .catch(function (err) {
        btn.disabled = false;
        btn.classList.remove('opacity-60');
        console.error('Erreur fetch :', err);
    });
});

</script>
<script>
const menuButton = document.getElementById("user-menu");
const menu = document.getElementById("user-dropdown");
const chevron = document.getElementById("user-chevron");

if (menuButton && menu && chevron) {

    menuButton.addEventListener("click", function (e) {
        e.stopPropagation();

        menu.classList.toggle("hidden");

        chevron.style.transform = menu.classList.contains("hidden")
            ? "rotate(0deg)"
            : "rotate(180deg)";
    });

    document.addEventListener("click", function () {
        menu.classList.add("hidden");
        chevron.style.transform = "rotate(0deg)";
    });

}

const menuButtond = document.getElementById("user-menud");
const menud = document.getElementById("user-dropdownd");
const chevrond = document.getElementById("user-chevrond");

if (menuButtond && menud && chevrond) {

    menuButtond.addEventListener("click", function (e) {
        e.stopPropagation();

        menud.classList.toggle("hidden");

        chevrond.style.transform = menud.classList.contains("hidden")
            ? "rotate(0deg)"
            : "rotate(180deg)";
    });

    document.addEventListener("click", function () {
        menud.classList.add("hidden");
        chevrond.style.transform = "rotate(0deg)";
    });


}

    // new TomSelect("#zone_livraison", {
    //     create: false,
    //     sortField: {
    //         field: "text",
    //         direction: "asc"
    //     },
    //     placeholder: "Rechercher une zone...",
    // });

    
    </script>