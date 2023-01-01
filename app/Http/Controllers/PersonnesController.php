<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Models\Personne as ModelsPersonne;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Models\Personne;
use Illuminate\Support\Facades\DB;

class PersonnesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
        $verif = Paiement::where(
            [
                'code_paiement','=', $request->codePai
            ])->get();

        // Insertion dans la table Personnes
        $person = ModelsPersonne::create(
            [
                'nom_prenom'    => strtoupper($request->name),
            ],
            [
                'code_parrainage'       => $request->codePar,
                'adresse'       => $request->adresse,
                'contact'    => $request->phone,
                'date_naissance'    => $request->date,
                'email'      => $request->email,
                'date' => $request->date,
                'sexe_id'       => $request->sexe,
                'user_id'       => $request->age,
                'paiement_id'       => $request->age,
            ]
        );

        // Insertion dans la table users
        $user = User::create(
            [
                'identifiant'    => strtoupper($request->pseudo),

            ],
            [
                'password'       => $request->password,
                'status'       => $request->sexe,
                'etat' => $request->date,
            ]
        );

        if ($person and $user) {
            return view('auth.login');
        } else {
            
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

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
