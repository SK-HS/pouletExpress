<?php

namespace App\Http\Controllers;

use App\Models\ProduitFournisseur;
use App\Models\Quartier;
use Illuminate\Http\Request;

class PagesController extends Controller
{

    public function get_index()
    {
        $produits = ProduitFournisseur::where('quantite','>',0)->get();
        
        return view('pages.index', compact('produits'));
    }
    public function get_a_propos()
    {
        return view('pages.a_propos');
    }
    public function get_services()
    {
        $produits = ProduitFournisseur::where('quantite','>',0)->get();
        return view('pages.services', compact('produits'));
    }

    public function get_detail_produit($id)
    {
        $produit = ProduitFournisseur::find($id);

        return view('pages.detail_produit', compact('produit'));
    }
    public function get_paiement_commande(Request $request,$id)
    {
        $produit = ProduitFournisseur::find($id);

        $quartiers = Quartier::with('service')->get();
        return view('pages.paiement', compact('produit', 'quartiers'));
    }

    public function get_contact()
    {
        return view('pages.contact');
    }
}
