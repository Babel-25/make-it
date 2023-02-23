<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Models\Etat;
use App\Models\Membre;
use App\Models\Personne;
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
        $user_connected = User::find(auth()->user()->id)->personne;
        $personnes = Personne::all();
        $fieuls = getDescendants($user_connected->id);

        $niv1_fieuls = getDescendants($user_connected->id)->take(2);

        $niv2_fieuls1 = getDescendants($user_connected->id)->skip(2)->take(2);
        $niv2_fieuls2 = getDescendants($user_connected->id)->skip(4)->take(2);

        $niv3_fieuls1 = getDescendants($user_connected->id)->skip(6)->take(4);
        $niv3_fieuls2 = getDescendants($user_connected->id)->skip(10)->take(4);

        $niv4_fieuls1 = getDescendants($user_connected->id)->skip(14)->take(8);
        $niv4_fieuls2 = getDescendants($user_connected->id)->skip(22)->take(8);


        return view('layout.user.dashboard', compact(
            'niv1_fieuls',
            'niv2_fieuls1',
            'niv2_fieuls2',
            'niv3_fieuls1',
            'niv3_fieuls2',
            'niv4_fieuls1',
            'niv4_fieuls2',
            'fieuls',
            'personnes',
        ));
    }

    //Fonction page mon profil
    public function monProfil()
    {
        //Informations sur l'utilisateur
        $personne = User::find(auth()->user()->id)->personne;
        $fieuls = getDescendants($personne->id);
        $etat = Etat::where('id', auth()->user()->etat_id)->first();
        return view('layout.user.profile', compact('personne','fieuls', 'etat'));
    }

    //Page ajout sexe + ajout etat
    public function configForm()
    {
        $sexes = Sexe::all();
        return view('layout.user.configuration', compact('sexes'));
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
