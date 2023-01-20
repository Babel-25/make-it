<?php

namespace App\Http\Controllers;

use App\Models\Etat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EtatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $etats = Etat::all();
        return view('layout.user.listeEtat')->with('etats', $etats);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('layout.user.configuration');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
        $etat = Etat::find($id);
        return view('layout.user.configModif')->with('etat', $etat);
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
        $eta = Etat::findOrFail($id);
        $input = $eta->update($request->all());
        session()->flash('message2', 'Modification Réussi !');

        return redirect('Configuration');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Etat::destroy($id);
        session()->flash('echec2', 'Suppression réussi!');
        return redirect('Liste_Etat');
    }
}
