<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Models\Personne;
use App\Models\User;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersonnesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function savePersonnes(Request $request)
    {
        $verif = Paiement::where('code_paiement', $request->codePai)->get();

        //return $verif;
        // dd($verif->code_paiement);
        $request->validate([
            'pseudo' => 'required',
            'name' => 'required',
            'codePai' => 'required',
            'codePar' => 'required',
            'adresse' => 'required',
            'phone' => 'required',
            'sexe' => 'required',
            'date' => 'required',
            'email' => 'required|email|unique:personnes',
            'password' => 'required|min:8',
            'passwordConf'    => 'required|min:8|same:password'
        ],
        [
            'pseudo.required' => 'Votre identifiant de connexion est requis',
            'name.required' => 'Le nom et prenom sont requis',
            'codePai.required' => 'Le code de paiement est obligatoire',
            'codePar.required' => 'Le code de parrainnage est obligatoire',
            'adresse.required' => 'Votre adresse est obligatoire',
            'sexe.required' => 'Le sexe est obligatoire',
            'phone.required' => 'Votre numéro de téléphone est obligatoire',
            'date.required' => 'Votre date de nissance est obligatoire',
            'email.required' => 'Votre mail est obligatoire ou ce mail existe déja',
            'password.required' => 'Le mot de passe doit dépasser 8 caractères',
            'passwordConf.required' => 'Les mots de passe ne correspondent pas veiller ressayer!'
        ]
    );
        if ($verif != null) {
            // Insertion dans la table users
            $user = User::create(
                [
                    'identifiant'    => strtoupper($request->pseudo),
                    'password'       => $request->password,
                    'status'       => $request->status,
                    'etat_id' => $request->etat,
                ]
            );
            // Insertion dans la table Personnes
            $person = Personne::create(
                [
                    'nom_prenom'    => strtoupper($request->name),
                    'code_parrainage'       => $request->codePar,
                    'adresse'       => $request->adresse,
                    'contact'    => $request->phone,
                    'date_naissance'    => $request->date,
                    'email'      => $request->email,
                    'sexe_id'       => $request->sexe,
                    'user_id'       => $user->id,
                    'paiement_id'       => $verif->id,
                ]
            );

            if ($person and $user) {
                session()->flash('message','Inscription Réussi!');
                return view('auth.login');
            } else {
               // dd('pas enregistrer');
               session()->flash('message','Inscription échoué, veiller ressayer!');
                return back();
            }
        } else {
            dd('le code de paiement ne correspond pas');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     
    public function show($id)
    {
        //
    }*/

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
