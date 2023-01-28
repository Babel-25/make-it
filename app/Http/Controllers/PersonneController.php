<?php

namespace App\Http\Controllers;

use App\Models\Personne;
use App\Models\Sexe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersonneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $personnes = DB::table('personnes')
            ->join('sexes', 'sexes.id', '=', 'personnes.sexe_id')
            ->join('paiements', 'paiements.id', '=', 'personnes.paiement_id')
            ->join('users', 'users.id', '=', 'personnes.user_id')
            ->get();

        return view('layout.user.personnes.list_personnes', compact('personnes',));
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
    public function store(Request $request)
    {
        //
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
        $personne = Personne::find($id);

        $data = $request->validate([
            'nom_prenom' => 'required',
            'contact'    => 'required',
            'email'      => 'required',
            'adresse'    => 'required',
        ]);

        //Action de mise à jour
        $update_personne = $personne->update([
            'nom_prenom'  => $data['nom_prenom'],
            'contact'     => $data['contact'],
            'email'       => $data['email'],
            'adresse'     => $data['adresse'],
        ]);

        if ($update_personne) {
            session()->flash('message', 'Mise à jour effectuée');
            return back();
        } else {
            session()->flash('message', 'Echec de la mise à jour');
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $suppression = Personne::destroy($id);

        if ($suppression) {
            session()->flash('message', 'Suppression réussi!');
            return back();
        } else {
            session()->flash('echec', 'Echec de la suppression');
            return back();
        }
    }
}
