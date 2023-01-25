<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Mail\MessageGoogle;
use App\Models\Etat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        return view('layout.user.configuration');
    }

    public function paiementForm()
    {
        return view('layout.user.paiement');
    }

    public function sendEmail(Request $req)
    {
        $data = [
            'name' =>$req->name,
            'email' =>$req->email,
            'message' =>$req->message
        ];
        Mail::to('badioubabel1@gmail.com')->send(new ContactMail($data));
        session()->flash('message7', 'Email envoyé avec succès!');
        return back();
    }

   

    //Fonction ajout d'etat
    
}
