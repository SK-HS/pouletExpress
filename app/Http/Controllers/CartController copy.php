<?php

namespace App\Http\Controllers;

use App\Models\Quartier;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartService $cart;

    public function __construct(CartService $cart)
    {
        $this->cart = $cart;
    }

    /**
     * Affiche la page panier complète
     */
    public function index()
    {
        $quartiers = Quartier::with('service')->get();

        return view('pages.panier', [
            'items' => $this->cart->getItemsWithDetails(),
            'total' => $this->cart->getTotal(),
            'quartiers'=>$quartiers,
        ]);
    }

    /**
     * Ajoute un produit au panier (appelé en AJAX depuis les boutons "add_shopping_cart")
     */
    public function add(Request $request)
    {
        $request->validate([
            'produit_id' => 'required|integer',
            'quantite' => 'nullable|integer|min:1',
        ]);

        $this->cart->add($request->input('produit_id'), $request->input('quantite', 1));

        return response()->json([
            'success' => true,
            'count' => $this->cart->count(),
        ]);
    }

    /**
     * Met à jour la quantité d'un produit dans le panier
     */
    public function update(Request $request)
    {
        $request->validate([
            'produit_id' => 'required|integer',
            'quantite' => 'required|integer|min:0',
        ]);

        $this->cart->update($request->input('produit_id'), $request->input('quantite'));

        return response()->json([
            'success' => true,
            'count' => $this->cart->count(),
            'total' => $this->cart->getTotal(),
        ]);
    }

    /**
     * Retire un produit du panier
     */
    public function remove(Request $request)
    {
        $request->validate([
            'produit_id' => 'required|integer',
        ]);

        $this->cart->remove($request->input('produit_id'));

        return response()->json([
            'success' => true,
            'count' => $this->cart->count(),
            'total' => $this->cart->getTotal(),
        ]);
    }

    /**
     * Vide le panier
     */
    public function clear()
    {
        $this->cart->clear();
        return redirect()->route('panier.index');
    }




}
