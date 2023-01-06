<?php

namespace App\Http\Controllers;

use App\Models\Personne;
use App\Models\User;
use Illuminate\Http\Request;

class PageController extends Controller
{
    //Fonction Page home
    public function home()
    {
        return view('layout.home.home');
    }

    //fonction page vitrine
    public function vitrine()
    {
        return view('layout.site.vitrine');
    }

    //Page index utilisateur
    public function indexUser()
    {
        return view('layout.user.user');
    }

    //fonction page mon reseau
    public function dashboard()
    {
        return view('layout.user.dashboard');
    }

    //Fonction page mon profil
    public function monProfil()
    {
        //Informations user
        $user = User::find(auth()->user()->id)->personne;
        return view('layout.user.profile',compact('user'));
    }

    //Informations de l'utilisateur connecté
    public function updatePersonne(Request $request)
    {
        $user = User::find(auth()->user()->id)->personne;

        $data = $request->validate([
            'nom_prenom' => 'required',
            'contact'    => 'required',
            'email'      => 'required',
            'adresse'    => 'required',
        ]);

        //Action de mise à jour
        $update_personne = $user->update([
            'nom_prenom'  => $data['name'],
            'contact'     => $data['contact'],
            'email'       => $data['email'],
            'adresse'     => $data['adresse'],
        ]);

        if ($update_personne) {
            session()->flash('text', 'Mise à jour effectuée');
            return back();
        }
    }
}
