<div class="px-6 py-4">
    <h5 class="font-headline-md text-headline-md text-primary">Filtres du Marché</h5>
    <p class="text-on-surface-variant text-sm">Affinez votre recherche</p>
</div>

<form id="filterForm" method="GET" action="{{ route('Services') }}">

    <nav class="flex flex-col gap-1 px-4">
        <span class="text-xs font-bold text-outline-variant uppercase px-2 mb-2 tracking-widest">Catégories</span>
       @foreach ($categories as $categorie)
<label class="{{ in_array($categorie->id, (array) request('categories', [])) ? 'bg-secondary-container text-on-secondary-container' : 'text-on-surface' }} font-body-md-bold rounded-lg px-4 py-3 flex items-center gap-3 transition-all cursor-pointer">
    <input type="checkbox" name="categories[]" value="{{ $categorie->id }}" class="hidden filter-checkbox"
           {{ in_array($categorie->id, (array) request('categories', [])) ? 'checked' : '' }}>
    <span class="material-symbols-outlined">agriculture</span>
                 {{ $categorie->nom }}
</label>
@endforeach
    </nav>

    <div class="px-6 py-6 border-t border-outline-variant/30 mt-4">
        <span class="text-xs font-bold text-outline-variant uppercase mb-4 block tracking-widest">Éleveurs Certifiés</span>
        <div class="mb-4 relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline-variant text-sm">search</span>
            <input type="text" id="fournisseurSearch" placeholder="Rechercher un éleveur..."
                   class="w-full bg-surface border border-outline-variant rounded-lg pl-9 pr-3 py-2 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all">
        </div>
        <div class="space-y-3 max-h-48 overflow-y-auto custom-scrollbar pr-2" id="fournisseursList">
            @foreach ($fournisseurs as $fournisseur)
            <label class="flex items-center gap-3 cursor-pointer group fournisseur-item">
                <input type="checkbox" name="fournisseurs[]" value="{{ $fournisseur->id }}"
                       class="rounded border-outline text-primary focus:ring-primary w-5 h-5 filter-checkbox"
                       {{ in_array($fournisseur->id, request('fournisseurs', [])) ? 'checked' : '' }}>
                <span class="text-body-md group-hover:text-primary transition-colors fournisseur-name">{{ $fournisseur->nom }}</span>
            </label>
            @endforeach
        </div>
    </div>

    <div class="px-6 py-6 border-t border-outline-variant/30">
        <span class="text-xs font-bold text-outline-variant uppercase mb-4 block tracking-widest">Localisation</span>
        <select name="quartier" class="w-full bg-surface border border-outline-variant rounded-lg px-3 py-2 text-body-md focus:ring-2 focus:ring-primary filter-select">
            <option value="">Abidjan (Toutes zones)</option>
            @foreach ($quartiers as $quartier)
            <option value="{{ $quartier->id }}" {{ request('quartier') == $quartier->id ? 'selected' : '' }}>
                {{ $quartier->commune?->ville?->nom_ville }}-{{ $quartier->commune?->nom_commune }}-{{ $quartier->nom_quartier }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="px-6 py-6 border-t border-outline-variant/30">
        <div class="flex justify-between items-center mb-4">
            <span class="text-xs font-bold text-outline-variant uppercase tracking-widest">Prix (FCFA)</span>
            <span class="text-sm font-bold text-primary price-display">{{ request('prix_max', 50000) / 1000 }}k max</span>
        </div>
        <input class="w-full h-2 bg-outline-variant rounded-lg appearance-none cursor-pointer accent-primary price-slider"
               max="50000" min="0" step="500" type="range"
               name="prix_max" value="{{ request('prix_max', 50000) }}">
    </div>
</form>

<script>
    // document.querySelectorAll('.filter-checkbox, .filter-select').forEach(el => {
    //     el.addEventListener('change', () => document.getElementById('filterForm').submit());
    // });

    // document.querySelectorAll('.price-slider').forEach(slider => {
    //     let debounce;
    //     slider.addEventListener('input', function () {
    //         const val = parseInt(this.value);
    //         const formatted = (val / 1000).toFixed(0) + 'k';
    //         document.querySelectorAll('.price-display').forEach(d => d.textContent = `${formatted} max`);
    //         clearTimeout(debounce);
    //         debounce = setTimeout(() => document.getElementById('filterForm').submit(), 500);
    //     });
    // });

    // const fournisseurSearch = document.getElementById('fournisseurSearch');
    // if (fournisseurSearch) {
    //     fournisseurSearch.addEventListener('input', function () {
    //         const query = this.value.toLowerCase();
    //         document.querySelectorAll('.fournisseur-item').forEach(item => {
    //             const name = item.querySelector('.fournisseur-name').textContent.toLowerCase();
    //             item.style.display = name.includes(query) ? 'flex' : 'none';
    //         });
    //     });
    // }


    
</script>

<script>
    const filterForm = document.getElementById('filterForm');

    // 1. LA FONCTION MAGIQUE AJAX
    function fetchFilteredProducts() {
        // Crée l'URL avec tous les paramètres cochés (ex: ?categories[]=1&prix_max=20000)
        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData).toString();
        const url = `${filterForm.action}?${params}`;

        // Optionnel : Effet visuel de chargement sur la grille (devient un peu transparente)
        const productGrid = document.getElementById('productGrid');
        if (productGrid) {
            productGrid.style.opacity = '0.4';
            productGrid.style.pointerEvents = 'none'; // Empêche de cliquer pendant le chargement
        }

        // Met à jour l'URL dans la barre d'adresse du navigateur en direct (très bon pour le SEO et le bouton "Retour")
        window.history.pushState({}, '', url);

        // Lancement de la requête silencieuse
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text()) // On récupère le code HTML complet
        .then(html => {
            // On transforme le texte HTML en un vrai "Document" manipulable
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // On extrait LA nouvelle grille calculée par le serveur
            const newGrid = doc.getElementById('productGrid');
            
            // On remplace le contenu de l'ancienne grille par la nouvelle !
            if (newGrid && productGrid) {
                productGrid.innerHTML = newGrid.innerHTML;
                productGrid.style.opacity = '1'; // On enlève le voile de chargement
                productGrid.style.pointerEvents = 'auto'; // On réactive les clics
            }
        })
        .catch(error => {
            console.error("Erreur lors du filtrage :", error);
            if (productGrid) {
                productGrid.style.opacity = '1';
                productGrid.style.pointerEvents = 'auto';
            }
        });
    }

        // 2. ÉCOUTEURS D'ÉVÉNEMENTS
    
    // --- Pour les Select (ex: Quartier) ---
    document.querySelectorAll('.filter-select').forEach(el => {
        el.addEventListener('change', fetchFilteredProducts);
    });

    // --- Pour les Checkboxes (Catégories et Éleveurs) ---
    document.querySelectorAll('.filter-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            
            // A. Changement visuel instantané pour les Catégories (qui sont des gros boutons)
            const label = this.closest('label');
            
            // Si c'est un bouton "Catégorie" (on vérifie s'il a les classes de design)
            if (label && this.classList.contains('hidden')) {
                if (this.checked) {
                    // On l'allume (Actif)
                    label.classList.add('bg-secondary-container', 'text-on-secondary-container');
                    label.classList.remove('text-on-surface');
                } else {
                    // On l'éteint (Inactif)
                    label.classList.remove('bg-secondary-container', 'text-on-secondary-container');
                    label.classList.add('text-on-surface');
                }
            }
            
            // B. Lancement de la requête AJAX après avoir changé la couleur
            fetchFilteredProducts();
        });
    });

    // --- Pour le Prix ---
    document.querySelectorAll('.price-slider').forEach(slider => {
        let debounceTimer;
        slider.addEventListener('input', function () {
            const val = parseInt(this.value);
            const formatted = (val / 1000).toFixed(0) + 'k';
            document.querySelectorAll('.price-display').forEach(d => d.textContent = `${formatted} max`);
            
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(fetchFilteredProducts, 500);
        });
    });

</script>


