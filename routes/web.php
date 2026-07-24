<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [PagesController::class, 'get_index'])->name('index');
Route::get('/A-propos', [PagesController::class, 'get_a_propos'])->name('A-Propos');
Route::match(['get', 'post'],'/Services', [PagesController::class, 'get_services'])->name('Services');
Route::get('/Contact', [PagesController::class, 'get_contact'])->name('Contact');
Route::get('/Detail-Produit/{id}', [PagesController::class, 'get_detail_produit'])->name('Detail-Produit');
Route::get('/Paiement-Commande/{id}', [PagesController::class, 'get_paiement_commande'])->name('Paiement-Commande');
Route::get('/Detail-Fournisseurs/{id}', [PagesController::class, 'get_detail_fournisseur'])->name('Detail-Fournisseurs');
Route::get('/Ajouter-Panier/{id}', [PagesController::class, 'post_ajouter_panier'])->name('Ajouter-Panier');

// Route::get('/', function () {
//     return redirect()->to('/admin/login');
// });
