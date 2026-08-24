<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Exception;
use App\Models\Livreur;
use App\Models\Quartier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthLivreurController extends Controller
{

  public function login_livreur()
    {
        
        return view('livreur.login');
    }
   
    public function post_login_livreur(Request $request)
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
    if (! Auth::guard('livreur')->attempt($credentials, $request->boolean('remember'))) {
        throw ValidationException::withMessages([
            'telephone' => 'Les identifiants fournis sont incorrects.',
        ]);
    }

    // 3. La connexion a réussi (le mot de passe est bon).
    // On récupère maintenant les données du livreur pour vérifier son statut
    $livreur = Auth::guard('livreur')->user();

    // Vérification de l'état
    if ($livreur->etat == 0) {
        
        // Le compte n'est pas actif, on le déconnecte tout de suite
        Auth::guard('livreur')->logout();
        
        // On affiche le message personnalisé selon le statut
        if ($livreur->statut == 'EN_ATTENTE') {
            throw ValidationException::withMessages([
                'telephone' => 'Votre compte est en cours de traitement.', // Ou 'message' selon votre fichier Blade
            ]);
        }

        if ($livreur->statut == 'BLOQUE') {
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

     return redirect()->intended(route('Livreur-Espace'));
}


    public function logout_livreur(Request $request)
    {
        Auth::guard('livreur')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('Livreur-Login');
    }

    public function disponibilite_livreur(Request $request)
{
    // Récupère le livreur connecté
    $livreur = Auth::guard('livreur')->user();
    
    // Inverse le statut actuel (s'il est à 1 il passe à 0, et inversement)
    // Remplacez 'is_online' par le vrai nom de votre colonne si différent
    $livreur->disponible = !$livreur->disponible; 
   // dd($livreur->disponible);
    $livreur->save();

    // Renvoie la réponse au format JSON pour le Javascript
    return response()->json([
        'success' => true,
        'is_online' => $livreur->disponible,
        'message' => 'Statut mis à jour'
    ]);
}


    public function inscription_livreur()
    {
        $quartiers = Quartier::get();

        return view('livreur.register', compact('quartiers'));
    }
    public function save_inscription_livreur(Request $request)
    {
        $validatedData = $request->validate([
            'nom'         => 'required|string|max:255',
            'telephone'   => 'required|string|max:20|unique:livreurs,telephone',
            'email'       => 'nullable|email|max:255|unique:livreurs,email',
            'type'        => 'required',
            'categorie'        => 'nullable',
            'quartier_id' => 'required|exists:quartiers,id', // Vérifie que le quartier existe
            'adresse'     => 'nullable|string|max:255',
            'password'    => 'required|string|min:4|confirmed', // 'confirmed' vérifie password_confirmation
            'image'       => 'nullable|image|max:2048', // 2MB Max
        ], [
            // Messages d'erreur personnalisés (en français)
            'telephone.unique'   => 'Ce numéro de téléphone est déjà utilisé par un autre livreur.',
            'email.unique'       => 'Cette adresse email est déjà utilisée.',
            'password.confirmed' => 'Les deux mots de passe ne correspondent pas.',
            'password.min'       => 'Le mot de passe doit contenir au moins 4 caractères.',
            'image.image'        => 'Le fichier doit être une image valide.',
        ]);
        // 2. Traitement de l'image (si le livreur a uploadé une photo)
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('Livreurs', 'public');
        }
        // 3. Génération d'une référence unique (ex: LIV-20260730-ABCD)
        // $reference = 'LIV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        // 4. Création du Livreur dans la base de données
        $livreur = Livreur::create([
            // 'reference'   => $reference,
            'nom'         => $validatedData['nom'],
            'type'        => $validatedData['type'],
            'telephone'   => $validatedData['telephone'],
            'email'       => $validatedData['email'],
            'adresse'     => $validatedData['adresse'],
            'quartier_id' => $validatedData['quartier_id'],
            'categorie' => $validatedData['adresse'],
            'image'       => $imagePath,
            'password'    => Hash::make($request->password), // Cryptage obligatoire du mot de passe
            
            'disponible'  => 1, // On le met disponible pour des courses dès son inscription
            'compte'      => 0, // Solde financier de départ à 0
            'etat'      => 0, // Solde financier de départ à 0
            'statut'      => "EN_ATTENTE", // Solde financier de départ à 0
        ]);
        // 5. Redirection vers la page de connexion avec un message de succès
     return redirect()->route('Livreur-Login');

    }

      public function update_profil_livreur(Request $request)
    {
        // 1. Récupérer le livreur connecté (en supposant que vous utilisez l'authentification standard ou un guard spécifique)
        $livreur = Auth::guard('livreur')->user();
        
        // 2. Validation des données envoyées par le formulaire
        $validatedData = $request->validate([
            'nom'         => 'required|string|max:255',
            // On ignore l'email du livreur actuel pour la règle unique
            'email'       => 'nullable|email|max:255|unique:livreurs,email,' . $livreur->id,
            'telephone'   => 'required|string|max:255',
            'contact'     => 'nullable|string|max:255',
            'adresse'     => 'nullable|string|max:255',
            'quartier_id' => 'nullable|integer', // Vous pouvez ajouter |exists:quartiers,id si vous avez une table quartiers
            'type'        => 'nullable|string',
            'categorie'        => 'nullable|string',
            'image'       => 'nullable|image', // 2Mo Max
            'password'    => 'nullable|string|min:4|confirmed', // 'confirmed' vérifie le champ 'password_confirmation'
        ]);
        //  Mise à jour des informations basiques
        $oldImage = $livreur->image;
        $newImagePath = null;
        if ($request->hasFile('image')) {
            $newImagePath = $request->file('image')->store('livreurs', 'public');
        }
        try {
            // Laravel s'occupe du Begin, Commit et Rollback automatiquement ici !
            DB::transaction(function () use ($livreur, $validatedData, $request, $newImagePath) {
                $livreur->nom = $validatedData['nom'];
                $livreur->email = $validatedData['email'];
                $livreur->telephone = $validatedData['telephone'];
                $livreur->contact = $validatedData['contact'];
                $livreur->adresse = $validatedData['adresse'];
                $livreur->quartier_id = $validatedData['quartier_id'];
                $livreur->type = $validatedData['type'];
                $livreur->categorie = $validatedData['categorie'];
                $livreur->disponible = $request->has('disponible') ? 1 : 0;
                if ($request->filled('password')) {
                    $livreur->password = Hash::make($request->password);
                }
                if ($newImagePath) {
                    $livreur->image = $newImagePath;
                }
                $livreur->save();
            });
            // Si on arrive ici, la transaction a réussi. On supprime l'ancienne image.
            if ($newImagePath && $oldImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }
            return back()->with(['success' => true, 'message' => 'Votre profil a été mis à jour avec succès.']);
           
        } catch (Exception $e) {
            // La base de données a DÉJÀ annulé les changements toute seule grâce à la Closure.
            // On utilise le Catch uniquement pour nettoyer le fichier et renvoyer un joli message d'erreur.
            
            if ($newImagePath && Storage::disk('public')->exists($newImagePath)) {
                Storage::disk('public')->delete($newImagePath);
            }
            Log::error('Erreur MAJ Profil Livreur : ' . $e->getMessage());
            return back()->withInput()->with(['success' => false,'message' => 'Une erreur est survenue lors de la mise à jour.']);
        }
        }
}
