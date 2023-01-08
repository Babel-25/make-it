<?php

namespace App\Http\Controllers;

use App\Mail\MessageGoogle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
        return view('layout.user.profile', compact('user'));
    }

    public function configForm()
    {
        return view('layout.user.config');
    }

    //Fonction ajout sexe
    public function addSexe()
    {
        dd('Ajouter sexe');
    }

    //Fonction ajout sexe
    public function addEtat()
    {
        dd('Ajouter etat');
    }
}
