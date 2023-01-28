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
        return view('layout.user..etats.list_etats', compact('etats'));
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
                'codeEtat' => 'required',
            ],
            [
                'codeEtat.required' => 'Le code est obligatoire',
            ]
        );
        if (Etat::where('code', $request->codeEtat)->exists() === true) {
            session()->flash('message1', 'Cet Enregistrement existe déjà');
            return back();
        } else {
            if ($request->codeEtat === 'ACT') {
                $insertion = Etat::firstOrCreate(
                    [
                        'code' => $request->codeEtat,
                    ],
                    [
                        'libelle' => 'Actif'
                    ]
                );
                if ($insertion) {
                    session()->flash('message1', 'Ajout confirmé ACT');
                    return back();
                } else {
                    session()->flash('echec2', 'Echec, veuillez réessayer');
                    return back();
                }
            } else if ($request->codeEtat === 'INA') {
                $insertion = Etat::firstOrCreate(
                    [
                        'code' => $request->codeEtat,
                    ],
                    [
                        'libelle' => 'Inactif'
                    ]
                );
                if ($insertion) {
                    session()->flash('message1', 'Ajout confirmé INA');
                    return back();
                } else {
                    session()->flash('echec2', 'Echec, veuillez réessayer');
                    return back();
                }
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
        $etat = Etat::find($id);
        return view('layout.user.etats.edit_etat', compact('etat'));
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
        $maj_etat = $eta->update([
            'code'    => request('codeEtat'),
            'libelle' => request('libelleEtat'),
        ]);
        if ($maj_etat) {
            session()->flash('message3', 'Modification Réussi !');

            return redirect()->route('list_etats');
        } else {
            session()->flash('echec3', 'Erreur intervenue, veuillez réessayer!');
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
        $suppression = Etat::destroy($id);
        if ($suppression) {
            session()->flash('message3', 'Suppression réussi!');
            return redirect()->route('list_etats');
        } else {
            session()->flash('echec3', 'Erreur intervenue, veuillez réessayer!');
            return back();
        }
    }
}
