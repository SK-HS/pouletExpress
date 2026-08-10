<?php

namespace App\Http\Controllers;

use App\Models\MessageContact;
use Illuminate\Http\Request;

class PostsController extends Controller
{
      public function post_message_contact(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'contact' => 'required',
            'email' => 'required',
            'subject' => 'required',
            'message' => 'required',
           ]);

           MessageContact::create([
            'nom' => $request->nom,
            'telephone' => $request->contact,
            'email' => $request->email,
            'sujet' => $request->subject,
            'message' => $request->message,
        ]);

         return back()->with('success', 'Le message a été envoyé avec succès');
    }
}
