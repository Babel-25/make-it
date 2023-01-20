<?php

namespace App\Http\Controllers;

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

    public function listeEtats()
    {
        $etats = Etat::all();
        return view('layout.user.listeEtat', [
            'etats' => $etats,
        ]);
    }


    public function deleteEtat($id)
    {
        DB::table('etats')->where('id', $id)->delete();
        session()->flash('echec3', 'Suppression réussi!');
        return back();
    }

    public function showEditEtat()
    {
        $mod = Etat::where('id', Request('id'))->get();
        return view('layout.user.configModif', [
            'configModif' => $mod,
        ]);
    }

    public function modifEtat(Request $request, $id)
    {
        $index = Etat::findOrFail($id);

        $modif = $index->update($request->all());

        $modif = Etat::all();
        return view('layout.user.configuration', [
            'configuration' => $modif,
        ]);
    }


    //Fonction ajout d'etat
    public function addEtat(Request $request)
    {
        $request->validate(
            [
                'codeEtat'       => 'required',
                'libelleEtat'         => 'required'

            ],
            [
                'codeEtat.required'       => 'Le code est obligatoire',
                'libelleEtat.required'         => 'Le libelle est obligatoire'
            ]
        );


        $verifcodeEtat = DB::table('etats')->where('code', $request->codeEtat)->get()->first();
        //dd($verifcodeEtat);

        if ($verifcodeEtat != null) {
            session()->flash('echec2', 'Insertion echoué, cet état existe déja !');
            return back();
        } else {
            $etat = Etat::create(
                [
                    'code' => $request->codeEtat,
                    'libelle'      => $request->libelleEtat,
                ]
            );
            session()->flash('message2', 'Insertion Réussi !');
            return back();
        }
    }
}
