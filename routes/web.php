<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [PagesController::class, 'get_index'])->name('index');
Route::get('/A-propos', [PagesController::class, 'get_a_propos'])->name('A-Propos');
Route::match(['get', 'post'],'/Services', [PagesController::class, 'get_services'])->middleware('throttle:60,1')->name('Services');
Route::get('/Contact', [PagesController::class, 'get_contact'])->name('Contact');
Route::get('/Detail-Produit/{id}', [PagesController::class, 'get_detail_produit'])->name('Detail-Produit');
Route::get('/Paiement-Commande/{id}', [PagesController::class, 'get_paiement_commande'])->name('Paiement-Commande');
Route::get('/Detail-Fournisseurs/{id}', [PagesController::class, 'get_detail_fournisseur'])->name('Detail-Fournisseurs');
Route::get('/Ajouter-Panier/{id}', [PagesController::class, 'post_ajouter_panier'])->name('Ajouter-Panier');
Route::post('/Soumetre-Message', [PostsController::class, 'post_message_contact'])->name('Soumetre-Message');

///////////gestion du panier////////////////////
Route::get('/panier', [CartController::class, 'index'])->name('Panier-Produit');
Route::post('/panier/ajouter', [CartController::class, 'add'])->name('panier.add');
Route::post('/panier/modifier', [CartController::class, 'update'])->name('panier.update');
Route::post('/panier/retirer', [CartController::class, 'remove'])->name('panier.remove');
Route::post('/panier/vider', [CartController::class, 'clear'])->name('panier.clear');


// ============ AUTHENTIFICATION CLIENT (visiteurs non connectés uniquement) ============
Route::middleware('guest:client')->prefix('client')->group(function () {
    Route::get('/connexion-client', [AuthClientController::class, 'showLogin_client'])->name('Login-Client');
    Route::post('/connexion-client', [AuthClientController::class, 'login_client'])->name('login.attempt.client');

    Route::get('/Inscription-Client', [AuthClientController::class, 'showRegister_client'])->name('Inscription-Client');
    Route::post('/inscription-client', [AuthClientController::class, 'register_client'])->name('register.attempt.client');
});


// ============ FINALISATION DE COMMANDE (protégée par le guard "client") ============


Route::middleware('auth:client')->prefix('client')->group(function () {
    //   Gestion du panier client
    Route::post('/commande/valider/panier', [CartController::class, 'valider_commande'])->name('Valider-Commande-Panier');
    Route::get('/finaliser-commande', [CartController::class, 'finaliser_commande'])->name('Finaliser-Commande');
    Route::get('/commande/confirmation/{reference}', [CartController::class, 'confirmation_commande'])->name('Confirmation-Commande');
    //gestion client
    Route::put('/modification/profil/client', [AuthClientController::class, 'modification_profil_client'])->name('Modifier-Profil-Client');
    Route::get('/espace/client', [ClientsController::class, 'espace_client'])->name('Clients-Espace');
    Route::get('/profil/client', [ClientsController::class, 'profil_client'])->name('Client-Profil');
    Route::get('/suivi/commande/client', [ClientsController::class, 'suivi_commande_client'])->name('Suivi-Commande-Client');
    Route::get('/suivi/last/commande/client/{id}', [ClientsController::class, 'suivi_last_commande_client'])->name('Suivi-last-Commande');
    Route::get('/suivi/commandes/{id}/status', [ClientsController::class, 'suivi_statut_commande_client'])->name('Suivi-statut-Commande');
    Route::put('/valider/livraison/commande/{id}', [ClientsController::class, 'valider_livraison_commande'])->name('Valider-Livraison-Commande');

    });
Route::post('/deconnexion', [AuthClientController::class, 'logout'])->name('Client-Logout')->middleware('auth:client');
    
                         // gestion des livreurs
Route::middleware('guest:livreur')->prefix('livreur')->group(function () {
   
    Route::get('/connexion/livreur', [AuthLivreurController::class, 'login_livreur'])->name('Livreur-Login');
    Route::get('/inscription/livreur', [AuthLivreurController::class, 'inscription_livreur'])->name('Livreur-Inscription');
    Route::post('save/inscription/livreur', [AuthLivreurController::class, 'save_inscription_livreur'])->name('Save-Livreur-Inscription');
    Route::post('/connexion/livreur', [AuthLivreurController::class, 'post_login_livreur'])->name('Connexion-Livreur');
    });

Route::middleware('auth:livreur')->prefix('livreur')->group(function () {

    Route::get('/espace/livreur', [LivreursController::class, 'espace_livreur'])->name('Livreur-Espace');
    Route::get('/livreur/commandes/disponibles/{lat}/{lon}', [LivreursController::class, 'livreur_commandes_disponibles'])->name('Livreur-Commandes-Disponibles');
    Route::get('/livreur/commandes', [LivreursController::class, 'livreur_commandes'])->name('Commande-Livreur');
    Route::get('/livreur/commande/{id}/detail', [LivreursController::class, 'detail_commande_livreur'])->name('Detail-Commande-Livreur');
    Route::get('/mes-livraisons', [LivreursController::class, 'mesLivraisons'])->name('livreur.mes-livraisons');
    
    Route::get('/commandes-disponibles/polling', [LivreursController::class, 'pollingCommandes'])->name('livreur.polling');
    Route::post('/accepter/{id}/commande', [LivreursController::class, 'Livreur_accepter_commande'])->name('Livreur-Accepter-Commande');
    Route::put('/livraison/demarrer/{id}', [LivreursController::class, 'demarre_livraison'])->name('Livraison-Demarrer');
    Route::put('/livraison/En/Route/{id}', [LivreursController::class, 'en_route_livraison'])->name('Livraison-En-Route');
    Route::put('/livraison/terminer/{id}', [LivreursController::class, 'terminer_livraison'])->name('Livraison-Terminer');
    Route::post('/actualiser/position/gps', [LivreursController::class, 'actualiser_position_gps'])->name('Actualiser-Position-gps');
    
    Route::get('/localisation/produit', [LivreursController::class, 'localisation_produit'])->name('Localisation-Produit-livreur');
    Route::get('/livreur/profil', [LivreursController::class, 'profil_livreur'])->name('Profil-Livreur');
    Route::put('/livreur/profil/update', [AuthLivreurController::class, 'update_profil_livreur'])->name('Livreur-Profil-Update');
    Route::post('/livreur/disponibilite', [AuthLivreurController::class, 'disponibilite_livreur'])->name('Livreur-Disponibilite');
    });

Route::post('/livreur/deconnexion', [AuthLivreurController::class, 'logout_livreur'])
    ->name('Livreur-Logout')
    ->middleware('auth:livreur');

    // Endpoint polling suivi temps réel (appelé depuis le JS de la page suivi)
Route::get('/commande/suivi/{reference}/polling', [ClientsController::class, 'suiviPolling'])
    ->name('commande.suivi.polling')
    ->middleware('auth:client');

Route::middleware('guest:fournisseur')->prefix('fournisseur')->group(function () {

    Route::get('/connexion/fournisseur', [AuthFournisseurController::class, 'login_fournisseur'])->name('Fournisseur-Login');
    Route::get('/inscription/fournisseur', [AuthFournisseurController::class, 'inscription_fournisseur'])->name('Fournisseur-Inscription');
    Route::post('save/inscription/fournisseur', [AuthFournisseurController::class, 'save_inscription_fournisseur'])->name('Save-fournisseur-Inscription');
    Route::post('/connexion/fournisseur', [AuthFournisseurController::class, 'post_login_fournisseur'])->name('Connexion-Fournisseur');
    
    });

Route::post('/fournisseur/deconnexion', [AuthFournisseurController::class, 'logout_fournisseur'])
    ->name('Fournisseur-Logout')
    ->middleware('auth:fournisseur');

Route::middleware('auth:fournisseur')->prefix('fournisseur')->group(function () {

    Route::get('/profil/fournisseur', [AuthFournisseurController::class, 'profil_fournisseur'])->name('Fournisseur-Profil');
    Route::get('/modification/profil', [AuthFournisseurController::class, 'modification_profil'])->name('Modification-Profil');
    Route::put('update/espace/fournisseur', [AuthFournisseurController::class, 'update_profil_fournisseur'])->name('Update-Fournisseu-Profil');
    Route::get('/espace/fournisseur', [FournisseurController::class, 'espace_fournisseur'])->name('Fournisseur-Espace');
    Route::get('/produit/fournisseur', [FournisseurController::class, 'produit_fournisseur'])->name('Fournisseur-Produit');
    Route::get('/edite/produit/fournisseur/{id}', [FournisseurController::class, 'edite_produit_fournisseur'])->name('Edite-Fournisseur-Produit');
    Route::get('/commande/fournisseur', [FournisseurController::class, 'commande_fournisseur'])->name('Fournisseur-Commande');
    Route::get('/catalogue/fournisseur', [FournisseurController::class, 'catalogue_fournisseur'])->name('Fournisseur-Catalogue');
    Route::post('/save/produit/fournisseur', [FournisseurController::class, 'save_produit_fournisseur'])->name('Save-Produit-Fournisseur');
    Route::put('/save/edite/produit/fournisseur/{id}', [FournisseurController::class, 'save_edite_produit_fournisseur'])->name('Save-Edite-Fournisseur-Produit');
    Route::get('/export/Commandes/fournisseur', [FournisseurController::class, 'export_Commandes_fournisseur'])->name('Export-Commande-Fournisseur');
    
    Route::get('/suivi/Commandes/fournisseur/{id}', [FournisseurController::class, 'suivi_Commandes_fournisseur'])->name('Suivi-Commande-Fournisseur');
    Route::get('/suivi/position/commande/{id}', [FournisseurController::class, 'suivi_position_commande'])->name('Suivi-Position-Commande');
    Route::put('/commande/livree/fournisseur/{id}', [FournisseurController::class, 'commande_livree_fournisseur'])->name('Commande-Livree-Fournisseur');
    
    Route::get('/approvisionnement/produit', [FournisseurController::class, 'approvisonnement'])->name('Approvisionnement-Produit');
    Route::get('/approvisionnement/historique', [FournisseurController::class, 'approvisionnement_historique'])->name('Approvisionnement-Historique');
    Route::put('/reapprovisionner/produit/{id}', [FournisseurController::class, 'reapprovisionner'])->name('Reapprovisionner-Produit');

  });

