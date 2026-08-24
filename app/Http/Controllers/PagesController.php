<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Fournisseur;
// use App\Models\Produit;
use App\Models\ProduitFournisseur;
use App\Models\Publicite;
use App\Models\Quartier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PagesController extends Controller
{

    public function get_index_last()
    {
        $produits = ProduitFournisseur::with('fournisseur','produit','categorie','taille')->where('quantite','>',0)->get();
        
        $fournisseurs = Fournisseur::where('etat', 1)->get();

        $publicites = Publicite::where('est_actif', true)
                            ->where(function ($query) {
                                $query->whereNull('date_debut')->orWhere('date_debut', '<=', now());
                            })
                            ->where(function ($query) {
                                $query->whereNull('date_fin')->orWhere('date_fin', '>=', now());
                            })
                            ->get();

        return view('pages.index', compact('produits','fournisseurs','publicites'));
    }
    

public function get_index()
{
    $produits = Cache::remember('accueil_produits_v1', 300, function () {
        return ProduitFournisseur::with([
                'fournisseur:id,nom,nom_ferme',
                'produit:id,nom,image',
                'categorie:id,nom',
                'taille:id,taille',
            ])
            ->where('quantite', '>', 0)
            ->orderBy('created_at', 'desc')
            ->limit(12)
            ->get();
    });

    // Vérification défensive : si le cache est corrompu (pas une Collection),
    // on l'ignore et on relance une requête fraîche immédiatement
    if (!($produits instanceof \Illuminate\Support\Collection)) {
        Cache::forget('accueil_produits_v1');
        $produits = ProduitFournisseur::with([
                'fournisseur:id,nom,nom_ferme',
                'produit:id,nom,image',
                'categorie:id,nom',
                'taille:id,taille',
            ])
            ->where('quantite', '>', 0)
            ->orderBy('created_at', 'desc')
            ->limit(12)
            ->get();
    }

    $fournisseurs = Cache::remember('accueil_fournisseurs_v1', 300, function () {
        return Fournisseur::select('id', 'nom', 'nom_ferme', 'image', 'type', 'note_moyenne')
            ->where('etat', 1)
            ->whereHas('produits', function ($q) {
                $q->where('quantite', '>', 0);
            })
            ->limit(8)
            ->get();
    });

    if (!($fournisseurs instanceof \Illuminate\Support\Collection)) {
        Cache::forget('accueil_fournisseurs_v1');
        $fournisseurs = Fournisseur::select('id', 'nom', 'nom_ferme', 'image', 'type', 'note_moyenne')
            ->where('etat', 1)
            ->whereHas('produits', function ($q) {
                $q->where('quantite', '>', 0);
            })
            ->limit(8)
            ->get();
    }

    $publicites = Cache::remember('accueil_publicites_v1', 300, function () {
        return Publicite::where('est_actif', true)
            ->where(function ($query) {
                $query->whereNull('date_debut')->orWhere('date_debut', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('date_fin')->orWhere('date_fin', '>=', now());
            })
            ->limit(5)
            ->get();
    });

    // Vérifie que chaque élément est bien une instance Publicite,
    // pas une chaîne (c'est précisément le bug rencontré : "$pub" était un string)
    if (!($publicites instanceof \Illuminate\Support\Collection)
        || ($publicites->isNotEmpty() && !($publicites->first() instanceof \App\Models\Publicite))) {
        Cache::forget('accueil_publicites_v1');
        $publicites = Publicite::where('est_actif', true)
            ->where(function ($query) {
                $query->whereNull('date_debut')->orWhere('date_debut', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('date_fin')->orWhere('date_fin', '>=', now());
            })
            ->limit(5)
            ->get();
    }

    $statsElevateurs = Cache::remember('accueil_stats_elevateurs_v1', 300, function () {
        return Fournisseur::where('etat', 1)->count();
    });

    $statsProduits = Cache::remember('accueil_stats_produits_v1', 300, function () {
        return ProduitFournisseur::where('quantite', '>', 0)->count();
    });

    return view('pages.index', [
        'produits'        => $produits,
        'fournisseurs'    => $fournisseurs,
        'publicites'      => $publicites,
        'statsElevateurs' => $statsElevateurs . '+',
        'statsProduits'   => $statsProduits . '+',
    ]);
}

    public function get_a_propos()
    {
        return view('pages.a_propos');
    }
    // public function get_services(Request $request)
    // {
    //     $produits = ProduitFournisseur::where('quantite','>',0)->get();
    //     $categories = Categorie::all();
    //     $fournisseurs = Fournisseur::all();
    //     $quartiers = Quartier::with('commune')->get();

    //     return view('pages.services', compact('produits','categories','fournisseurs','quartiers'));
    // }

    public function get_services(Request $request)
{  
      $validated = $request->validate([
        'categories'   => 'nullable|array',
        'categories.*' => 'integer|exists:categories,id',

        'fournisseurs'   => 'nullable|array',
        'fournisseurs.*' => 'integer|exists:fournisseurs,id',

        'quartier' => 'nullable|integer|exists:quartiers,id',
        'prix_max' => 'nullable|numeric|min:0',
        'tri'      => 'nullable|string|in:prix_desc,prix_asc,recent',
        'page'     => 'nullable|integer|min:1|max:1000', // borne la pagination, évite les OFFSET démesurés
    ]);

    // 2. REQUÊTE avec eager loading limité aux colonnes utiles
    $query = ProduitFournisseur::with([
            'produit:id,nom,image',
            'categorie:id,nom',
            'fournisseur:id,nom_ferme,quartier_id,nom',
            'taille:id,taille',
        ])
        ->where('quantite', '>', 0)
        ->select('id','nom_produit','quantite','prix','statuts','etat','commande_min','temps_preparation',
                 'fournisseur_id','produit_id','taille_id','categorie_id','images','description');

    // Filtre catégories
    if (!empty($validated['categories'])) {
        $query->whereIn('categorie_id', $validated['categories']);
    }

    // Filtre fournisseurs
    if (!empty($validated['fournisseurs'])) {
        $query->whereIn('fournisseur_id', $validated['fournisseurs']);
    }

    // Filtre quartier — whereIn + sous-requête plutôt que whereHas,
    // plus performant à volumétrie élevée (évite le coût d'un EXISTS corrélé)
    if (!empty($validated['quartier'])) {
        $query->whereIn('fournisseur_id', function ($q) use ($validated) {
            $q->select('id')
              ->from('fournisseurs')
              ->where('quartier_id', $validated['quartier']);
        });
    }

    // Filtre prix maximum
    if (!empty($validated['prix_max'])) {
        $query->where('prix', '<=', $validated['prix_max']);
    }

    // 3. TRI — lu depuis $validated (cohérent avec la validation, pas depuis $request brut)
    match ($validated['tri'] ?? 'prix_asc') {
        'prix_desc' => $query->orderBy('prix', 'desc'),
        'recent'    => $query->orderBy('created_at', 'desc'),
        default     => $query->orderBy('prix', 'asc'),
    };

    // Tri secondaire stable (évite un ordre incohérent entre 2 pages si plusieurs lignes ont le même prix)
    $query->orderBy('id', 'asc');

    // 4. PAGINATION
    $produits = $query->paginate(12)->withQueryString();
    

    $categories = Categorie::select('id','nom')->get();
    $fournisseurs = Fournisseur::select('id','nom')->get();
    $quartiers = Quartier::with('commune.ville')->select('commune_id','id','nom_quartier')->get();

    return view('pages.services', compact('produits', 'categories', 'fournisseurs', 'quartiers'));
}

    public function get_detail_produit(int $id)
    {
        $produit = ProduitFournisseur::with('fournisseur','produit','categorie','taille')->find($id);

        return view('pages.detail_produit', compact('produit'));
    }
    public function get_paiement_commande(Request $request,int $id)
    {
        $produit = ProduitFournisseur::find($id);

        $quartiers = Quartier::with('service')->get();
        return view('pages.paiement', compact('produit', 'quartiers'));
    }

    public function get_contact()
    {
        return view('pages.contact');
    }

    public function get_detail_fournisseur(int $id)
    {
            $fournisseurs = Fournisseur::findOrFail($id);
            $produits = ProduitFournisseur::where('fournisseur_id', $id)->get();

    return view('pages.fournisseur',compact('fournisseurs','produits'));

    }
}
