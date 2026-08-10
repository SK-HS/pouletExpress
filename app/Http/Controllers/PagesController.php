<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Fournisseur;
use App\Models\Produit;
use App\Models\ProduitFournisseur;
use App\Models\Publicite;
use App\Models\Quartier;
use Illuminate\Http\Request;

class PagesController extends Controller
{

    public function get_index()
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
    'categories' => 'integer|exists:categories,id',
    'fournisseurs' => 'integer|exists:fournisseurs,id',
    'quartier' => 'integer|exists:quartiers,id',
]);
    $query = ProduitFournisseur::with(['produit', 'categorie', 'fournisseur', 'taille'])
                                ->where('quantite', '>', 0);

    // Filtre par catégories (plusieurs sélections possibles)
    if ($request->filled('categories')) {
        $query->whereIn('categorie_id', $request->categories);
    }

    // Filtre par fournisseurs (plusieurs sélections possibles)
    if ($request->filled('fournisseurs')) {
        $query->whereIn('fournisseur_id', $request->fournisseurs);
    }

    // Filtre par localité (quartier du fournisseur)
    if ($request->filled('quartier')) {
        $query->whereHas('fournisseur', function ($q) use ($request) {
            $q->where('quartier_id', $request->quartier);
        });
    }

    // Filtre par prix maximum
    if ($request->filled('prix_max')) {
        $query->where('prix', '<=', $request->input('prix_max'));
    }

    // Tri
    switch ($request->input('tri')) {
        case 'prix_desc':
            $query->orderBy('prix', 'desc');
            break;
        case 'recent':
            $query->orderBy('created_at', 'desc');
            break;
        default:
            $query->orderBy('prix', 'asc');
    }

    $produits = $query->paginate(12)->withQueryString();

    $categories = Categorie::all();
    $fournisseurs = Fournisseur::all();
    $quartiers = Quartier::with('commune.ville')->get();

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
