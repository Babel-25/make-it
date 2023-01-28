<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Models\Personne;
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
        $verif_code_pay_status = Paiement::where('code_paiement', '100000')->first();
        dd($verif_code_pay_status);
        return view('layout.user.paiements.list_paiements', compact('paiements'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('layout.user.paiements.add_paiement');
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
                'codePaie'    => 'required',
                'libellePaie' => 'required'
            ],
            [
                'codePaie.required'     => 'Le code est obligatoire',
                'libellePaie.required'  => 'Le libelle est obligatoire'
            ]
        );

        $verifCodePaie = DB::table('paiements')->where('code_paiement', $request->codePaie)->get()->first();
        if ($verifCodePaie != null) {
            session()->flash('echec4', 'Insertion echouée, cet enregistrement existe déja !');
            return back();
        } else {
            $insertion_paiement = Paiement::create(
                [
                    'code_paiement'    => $request->codePaie,
                    'libelle_paiement' => $request->libellePaie,
                    'montant_paiement' => 3000
                ]
            );

            if ($insertion_paiement) {
                session()->flash('message4', 'Code de paiement enregistré !');
                return back();
            } else {
                session()->flash('echec4', 'Insertion echouée, veuillez réessayer !');
                return back();
            }
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
        $paiement = Paiement::find($id);
        return view('layout.user.paiements.edit_paiement', compact('paiement'));
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
        $paiement = Paiement::findOrFail($id);
        $maj = $paiement->update([
            'code_paiement'    => request('codePaie'),
            'libelle_paiement' => request('libellePaie'),
        ]);
        if ($maj) {
            session()->flash('message4', 'Modification Réussi !');

            return redirect()->route('list_paiements');
        } else {
            session()->flash('echec4', 'Echec  !');

            return redirect()->route('list_paiements');
        }

        // $input = Paiement::all();

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
