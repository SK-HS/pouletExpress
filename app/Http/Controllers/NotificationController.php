<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Détermine l'utilisateur connecté, peu importe le guard
     */
    protected function utilisateurConnecte()
    {
        foreach (['client', 'livreur', 'fournisseur', 'web'] as $guard) {
            if (Auth::guard($guard)->check()) {
                return Auth::guard($guard)->user();
            }
        }

        abort(403, 'Non authentifié.');
    }

    /**
     * Endpoint de polling — retourne les notifications non lues
     */
    public function polling()
    {
        $user = $this->utilisateurConnecte();

        $notifications = $user->unreadNotifications()->limit(15)->get();

        return response()->json([
            'count' => $user->unreadNotifications()->count(),
            'notifications' => $notifications->map(fn ($n) => [
                'id'         => $n->id,
                'titre'      => $n->data['titre'] ?? '',
                'message'    => $n->data['message'] ?? '',
                'lien'       => $n->data['lien'] ?? null,
                'icone'      => $n->data['icone'] ?? 'notifications',
                'couleur'    => $n->data['couleur'] ?? 'slate',
                'created_at' => $n->created_at->diffForHumans(),
            ]),
        ]);
    }

    /**
     * Marque une notification comme lue (appelé au clic)
     */
    public function marquerLue(Request $request, string $id)
    {
        $user = $this->utilisateurConnecte();

        // IDOR bloqué : on ne cherche que DANS les notifications de cet utilisateur
        $notification = $user->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json(['success' => true]);
    }

    /**
     * Marque toutes les notifications comme lues
     */
    public function marquerToutesLues()
    {
        $user = $this->utilisateurConnecte();
        $user->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }
}