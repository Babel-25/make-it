<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaiementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paiements = Paiement::all();
        return view('layout.user.paiement')->with('paiements', $paiements);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('layout.user.paiement');
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
                'codePaie'       => 'required',
                'libellePaie'         => 'required'

            ],
            [
                'codePaie.required'       => 'Le code est obligatoire',
                'libellePaie.required'         => 'Le libelle est obligatoire'
            ]
        );

        $verifCodePaie = DB::table('paiements')->where('code_paiement', $request->codePaie)->get()->first();
        if ($verifCodePaie != null) {
            //dd($valueCode);
            session()->flash('echec4', 'Insertion echoué, cet enregistrement existe déja !');
            return back();
        } else {
            $paiem = Paiement::create(
                [
                    'code_paiement' => $request->codePaie,
                    'libelle_paiement'      => $request->libellePaie,
                ]
            );

            session()->flash('message4', 'Ajout Réussi !');
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
        $paie = Paiement::find($id);
        return view('layout.user.paiemenModif')->with('paiements', $paie);
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
        $spaiement = Paiement::findOrFail($id);
        $input = $spaiement->update([
            'code_paiement' => request('codePaie'),
            'libelle_paiement' => request('libellePaie'),
        ]);
        $input = Paiement::all();
        session()->flash('message4', 'Modification Réussi !');
        
        return redirect('Paiement');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Paiement::destroy($id);
        session()->flash('echec4', 'Suppression réussi!');
        return back();
    }
}
