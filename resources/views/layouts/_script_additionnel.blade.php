<script>

    document.addEventListener('DOMContentLoaded', function () {
    const prixUnitaire = {{ (float) $produit->prix }};

    const qteInput        = document.getElementById('qte_commande');
    const checkboxLivraison = document.getElementById('souhaite_livraison');
    const blocLivraison    = document.getElementById('bloc_livraison');
    const zoneSelect       = document.getElementById('zone_livraison');
    const adresseInput     = document.getElementById('adresse_livraison');

    const recapQte       = document.getElementById('recap_qte');
    const recapSoustotal = document.getElementById('recap_soustotal');
    const recapFraisLigne= document.getElementById('recap_frais_ligne');
    const recapFrais     = document.getElementById('recap_frais');
    const recapTotal     = document.getElementById('recap_total');

    function formatFCFA(montant) {
        return new Intl.NumberFormat('fr-FR').format(montant) + ' FCFA';
    }

    function calculerTotal() {
        const qte = parseInt(qteInput.value) || 0;
        const soustotal = qte * prixUnitaire;

        let frais = 0;
        if (checkboxLivraison.checked && zoneSelect.value) {
            const option = zoneSelect.options[zoneSelect.selectedIndex];
            frais = parseFloat(option.dataset.frais) || 0;
        }

        recapQte.textContent = qte;
        recapSoustotal.textContent = formatFCFA(soustotal);

        if (checkboxLivraison.checked) {
            recapFraisLigne.style.display = 'flex';
            recapFrais.textContent = formatFCFA(frais);
        } else {
            recapFraisLigne.style.setProperty('display', 'none', 'important');
             recapFrais.textContent = formatFCFA(0);
        }

        recapTotal.textContent = formatFCFA(soustotal + frais);
    }

    // Affiche/masque le bloc livraison et rend les champs obligatoires seulement si visible
    document.getElementById('souhaite_livraison').addEventListener('change', function () {
        console.log('checkbox change déclenché, checked =', this.checked);
        const actif = this.checked;
        blocLivraison.style.display = actif ? 'flex' : 'none';
        zoneSelect.required = actif;
        adresseInput.required = actif;

        if (!actif) {
            zoneSelect.value = '';
            adresseInput.value = '';
        }
        
        calculerTotal();
    });

    qteInput.addEventListener('input', calculerTotal);
    zoneSelect.addEventListener('change', calculerTotal);

    // Empêche la soumission si la quantité dépasse le stock disponible
    document.getElementById('quoteForm').addEventListener('submit', function (e) {
        const qte = parseInt(qteInput.value);
        const max = parseInt(qteInput.getAttribute('max'));
        if (qte > max) {
            e.preventDefault();
            alert('La quantité demandée dépasse le stock disponible (' + max + ').');
        }
    });

    calculerTotal(); // calcul initial
});
</script>