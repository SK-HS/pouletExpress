<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use App\Models\Quartier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AuthFournisseurController extends Controller
{
    

    public function login_fournisseur()
    {

    return view('fournisseurs.login');
    }

    public function inscription_fournisseur()
    {
        $quartiers = Quartier::get();
    return view('fournisseurs.inscription', compact('quartiers'));
    }

    public function save_inscription_fournisseur(Request $request)
    {

     // 1. Validation des données
        $validatedData = $request->validate([
            'nom' => 'required|string|max:192',
            'telephone' => 'required|string|max:20|unique:fournisseurs,telephone',
            'contact' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:192|unique:fournisseurs,email',
            'adresse' => 'required|string',
            'type' => 'required|string',
            'password' => 'required|string|min:4',
            
            'nom_ferme' => 'required|string',
            'nom_gerant' => 'required|string',
            'quartier_id' => 'required|integer|exists:quartiers,id',
            'type_produit' => 'required|array',
            'type_produit.*' => 'string',
            'capacite_ferme' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'piece_fournisseur' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'certification_sanitaire' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'image_ferme' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            
            'cgu' => 'accepted',
        ]);
        // dd()
        $cheminsFichiers = [];
        // 2. Début de la transaction
      
            // 3. Gestion des fichiers (Upload)
            if ($request->hasFile('piece_fournisseur')) {
                $cheminsFichiers['piece_fournisseur'] = $request->file('piece_fournisseur')->store('fournisseurs/documents', 'public');
            }
            
            if ($request->hasFile('certification_sanitaire')) {
                $cheminsFichiers['certification_sanitaire'] = $request->file('certification_sanitaire')->store('fournisseurs/certifications', 'public');
            }
            
            if ($request->hasFile('image_ferme')) {
                $cheminsFichiers['image_ferme'] = $request->file('image_ferme')->store('fournisseurs/fermes', 'public');
            }
            
            if ($request->hasFile('image')) {
                $cheminsFichiers['image'] = $request->file('image')->store('fournisseurs/profils', 'public');
            }
            //dd($validatedData);
            // Génération de la référence unique
            // $reference = 'FRN-' . date('Ym') . '-' . strtoupper(Str::random(4));
            // 4. Création du fournisseur
            $fournisseur = Fournisseur::create([
                // 'reference' => $reference,
                'nom' => $validatedData['nom'],
                'telephone' => $validatedData['telephone'],
                'contact' => $validatedData['contact'] ?? null,
                'email' => $validatedData['email'] ?? null,
                'adresse' => $validatedData['adresse'],
                'type' => $validatedData['type'],
                'password' => Hash::make($request->password), 
                
                'nom_ferme' => $validatedData['nom_ferme'],
                'nom_gerant' => $validatedData['nom_gerant'],
                'quartier_id' => $validatedData['quartier_id'],
                'type_produit' => $validatedData['type_produit'],
                'capacite_ferme' => $validatedData['capacite_ferme'] ?? null,
                'description' => $validatedData['description'] ?? null,
                
                'latitude' => $validatedData['latitude'] ?? null,
                'longitude' => $validatedData['longitude'] ?? null,
                
                'piece_fournisseur' => $cheminsFichiers['piece_fournisseur'] ?? null,
                'certification_sanitaire' => $cheminsFichiers['certification_sanitaire'] ?? null,
                'image_ferme' => $cheminsFichiers['image_ferme'] ?? null,
                'image' => $cheminsFichiers['image'] ?? null,
                'etat' => 0,
                'disponible' => 0,
                'compte' => 0,
                'statut' => "EN_ATTENTE",
            ]);
            // 5. Tout s'est bien passé, on valide la transaction
           
            return redirect()->route('Fournisseur-Login')
                             ->with('success', 'Votre compte fournisseur a été créé avec succès.');
    
    }



    public function post_login_fournisseur(Request $request)
{
    // 1. Validation de base
    $request->validate([
        'telephone' => 'required|string',
        'password'  => 'required',
    ]);

    $identifiant = $request->input('telephone');
    $password = $request->input('password');

    // Déterminer si l'identifiant est un email ou un téléphone
    $champBaseDeDonnees = filter_var($identifiant, FILTER_VALIDATE_EMAIL) ? 'email' : 'telephone';

    $credentials = [
        $champBaseDeDonnees => $identifiant,
        'password'          => $password,
    ];

    // 2. On tente de connecter l'utilisateur
    if (! Auth::guard('fournisseur')->attempt($credentials, $request->boolean('remember'))) {
        throw ValidationException::withMessages([
            'telephone' => 'Les identifiants fournis sont incorrects.',
        ]);
    }

    // 3. La connexion a réussi (le mot de passe est bon).
    // On récupère maintenant les données du fournisseur pour vérifier son statut
    $fournisseur = Auth::guard('fournisseur')->user();

    // Vérification de l'état
    if ($fournisseur->etat == 0) {
        
        // Le compte n'est pas actif, on le déconnecte tout de suite
        Auth::guard('fournisseur')->logout();
        
        // On affiche le message personnalisé selon le statut
        if ($fournisseur->statut == 'EN_ATTENTE') {
            throw ValidationException::withMessages([
                'telephone' => 'Votre compte est en cours de traitement.', // Ou 'message' selon votre fichier Blade
            ]);
        }

        if ($fournisseur->statut == 'BLOQUE') {
            throw ValidationException::withMessages([
                'telephone' => "Votre compte a été suspendu, veuillez contacter l'administrateur.",
            ]);
        }
        
        // Par sécurité, si l'état est à 0 mais qu'il n'a ni l'un ni l'autre statut :
        throw ValidationException::withMessages([
            'telephone' => 'Votre compte est inactif.',
        ]);
    }

    // 4. Si tout est parfait (le mot de passe est bon, et l'état n'est pas à 0)
    $request->session()->regenerate();

    return redirect()->intended(route('Fournisseur-Espace'));
}

    

    public function update_profil_fournisseur(Request $request)
{
    // Récupération de l'utilisateur connecté via le guard
    $fournisseur = Auth::guard('fournisseur')->user();
    // 1. Validation des données
    $validatedData = $request->validate([
        // Infos perso
        'nom' => 'required|string|max:255',
        'telephone' => 'required|string|max:20|unique:fournisseurs,telephone,' . $fournisseur->id,
        'contact' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:255|unique:fournisseurs,email,' . $fournisseur->id,
        'adresse' => 'required|string|max:255',
        'type' => 'required|string',
        'password' => 'nullable|string|min:4', // Optionnel
        'description' => 'nullable|string', // Optionnel
        
        // Infos Ferme
        'nom_ferme' => 'required|string|max:255',
        'nom_gerant' => 'required|string|max:255',
        'quartier_id' => 'required|exists:quartiers,id',
        'capacite_ferme' => 'nullable|numeric|min:0',
        'type_produit' => 'required|array', // Car c'est un select multiple
        
        // Coordonnées GPS (remplies automatiquement par le bouton)
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        // Fichiers (tous optionnels car le fournisseur peut choisir de les garder)
        'piece_fournisseur' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        'certification_sanitaire' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        'image_ferme' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
    ]);
    // 2. Traitement spécifique du mot de passe
    if (!empty($validatedData['password'])) {
        // S'il a tapé un nouveau mot de passe, on le hache
        $validatedData['password'] = Hash::make($validatedData['password']);
    } else {
        // Sinon, on retire la clé 'password' du tableau pour ne pas écraser l'ancien
        unset($validatedData['password']);
    }
    // 3. Gestion intelligente des fichiers
    $fichiers = ['piece_fournisseur', 'certification_sanitaire', 'image_ferme', 'image'];
    
    foreach ($fichiers as $champ) {
        if ($request->hasFile($champ)) {
            // A. Supprimer l'ancien fichier du disque s'il existe
            if ($fournisseur->$champ && Storage::disk('public')->exists($fournisseur->$champ)) {
                Storage::disk('public')->delete($fournisseur->$champ);
            }
            
            // B. Enregistrer le nouveau fichier dans le bon dossier
            $dossier = in_array($champ, ['image', 'image_ferme']) ? 'fournisseurs/images' : 'fournisseurs/documents';
            $validatedData[$champ] = $request->file($champ)->store($dossier, 'public');
        }
    }
    // 4. Mise à jour de la base de données
    $fournisseur->update($validatedData);
    // 5. Redirection avec message de succès
    return back()->with('success','Votre profil a été mis à jour avec succès.');
           
}

     public function profil_fournisseur()
    {
        $fournisseur = Auth::guard('fournisseur')->user();
        //dd($fournisseur);
         $quartiers = Quartier::get();

        return view('fournisseurs.profil',compact('fournisseur','quartiers'));
    }
     public function modification_profil()
    {
        $fournisseur = Auth::guard('fournisseur')->user();
        //dd($fournisseur);
         $quartiers = Quartier::get();

        return view('fournisseurs.modification_profil',compact('fournisseur','quartiers'));
    }

     public function logout_fournisseur(Request $request)
    {
        Auth::guard('fournisseur')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('Fournisseur-Login');
    }


}
