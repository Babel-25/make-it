<?php

namespace App\Http\Controllers;

use App\Models\Etat;
use App\Models\Paiement;
use App\Models\Personne;
use App\Models\Sexe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
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

        $sexes = DB::table('sexes')->get();
        $paiements = DB::table('paiements')->get();
        $user = DB::table('users')->get();

        return view('layout.user.listeUsers', ['personnes' => $personnes, 'sexes' => $sexes, 'paiements' => $paiements, 'users' => $user]);
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
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $personneShow = Personne::find($id);
        return view('layout.user.usersShow')->with('utilisateur', $personneShow);
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
        Personne::destroy($id);
        session()->flash('echec6', 'Suppression réussi!');
        return back();
    }
}
