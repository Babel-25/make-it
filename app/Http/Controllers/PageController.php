<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Models\Etat;
use App\Models\Sexe;
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
        $etat = Etat::where('id',auth()->user()->etat_id)->first();
        return view('layout.user.profile', compact('user','etat'));
    }

    //Page ajout sexe + ajout etat
    public function configForm()
    {
        $sexes = Sexe::all();
        return view('layout.user.configuration',compact('sexes'));
    }

    public function paiementForm()
    {
        return view('layout.user.paiement');
    }

    //Envoie de mail
    public function sendEmailSiteAction(Request $request)
    {
        $data = [
            'name'    => $request->name,
            'email'   => $request->email,
            'message' => $request->message
        ];
        Mail::to('badioubabel1@gmail.com')->send(new ContactMail($data));
        session()->flash('message7', 'Email envoyé avec succès!');
        return back();
    }

}
