<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Quartier;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AuthClientController extends Controller
{
    /**
     * Affiche le formulaire de connexion client
     */
    public function showLogin_client()
    {
        return view('clients.login');
    }

    /**
     * Traite la connexion (guard "client", pas "web")
     */
    public function login_client(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (! Auth::guard('client')->attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Les identifiants fournis sont incorrects.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('Services'));
    }

    /**
     * Affiche le formulaire d'inscription client
     */
    public function showRegister_client()
    {
        $quartiers = Quartier::all();
        return view('clients.register',compact('quartiers'));
    }

    /**
     * Traite l'inscription client
     */
    public function register_client(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            // 'email' => 'required|string|email|max:255|unique:clients,email',
            'telephone' => 'required|string|max:20',
            'password' => 'required|string|min:4|confirmed',
            'quartier_id' =>'required',
            'type' =>'required',
        ]);

                if ($request->hasFile('image')) {
                    $imageName = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();

                    // Stocker dans le dossier public/storage/PhotosClients
                    $path = $request->file('image')->storeAs(
                        'PhotosClients', // dossier
                        $imageName,      // nom du fichier
                        'public'         // disque (config/filesystems.php)
                    );
                
                }else{
                    
                    $path = null;
                }
        
        $client = Client::create([
            'nom' => $validated['nom'],
            'telephone' => $validated['telephone'],
            'email' => $request->email,
            'contact' => $request->contact,
            'type' => $validated['type'],
            'adresse' => $request->adresse,
            'quartier_id' => $validated['quartier_id'],
            'image' => $path,
            'password' => Hash::make($validated['password']),
        ]);

        Auth::guard('client')->login($client);

        $request->session()->regenerate();

        return redirect()->intended(route('Services'));
    }

        public function modification_profil_client(Request $request)
    {
       
            $client = Auth::guard('client')->user();

            $validated = $request->validate([
                'type'        => ['required', 'string'],
                'quartier' => ['required', 'exists:quartiers,id'],
                'nom'         => ['required', 'string', 'max:255'],
                'email'       => ['nullable', 'email'],
                'tel'         => ['required', 'string'],
                'contact'     => ['nullable', 'string'],
                'image'       => ['nullable', 'image', 'max:2048'],
                'motpassword' => ['nullable', 'string', 'min:8'],
            ]);
            $oldImage = $client->image;
            $path=null;

  try {
    DB::transaction(function () use ($request, $validated, $client, $path) {
            $client->type = $validated['type'];
            $client->quartier_id = $validated['quartier'];
            $client->nom = $validated['nom'];
            $client->email = $validated['email']?? null;
            $client->telephone = $validated['tel'];
            $client->contact = $validated['contact'] ?? null;


            if ($request->hasFile('image')) {
                $imageName = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();

                    // Stocker dans le dossier public/storage/PhotosClients
                    $path = $request->file('image')->storeAs(
                        'PhotosClients', // dossier
                        $imageName,      // nom du fichier
                        'public'         // disque (config/filesystems.php)
                    );

                // $image = $request->file('image')->store('clients', 'public');

                $client->image = $path;
            }


            if (!empty($validated['motpassword'])) {
                $client->password = Hash::make($validated['motpassword']);
            }


            $client->save();

                   });

                // Si on arrive ici, la transaction a réussi. On supprime l'ancienne image.
            if ($path && $oldImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }
            return back()->with(['success' => true, 'message' => 'Votre profil a été mis à jour avec succès.']);
           
        } catch (Exception $e) {
            // La base de données a DÉJÀ annulé les changements toute seule grâce à la Closure.
            // On utilise le Catch uniquement pour nettoyer le fichier et renvoyer un joli message d'erreur.
            
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            Log::error('Erreur MAJ Profil Client : ' . $e->getMessage());
            return back()->withInput()->with(['success' => false,'message' => 'Une erreur est survenue lors de la mise à jour.']);
        }
            
            }


    /**
     * Déconnexion du client
     */
    public function logout(Request $request)
    {
        Auth::guard('client')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('Services');
    }
}
