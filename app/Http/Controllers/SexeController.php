<?php

namespace App\Http\Controllers;

use App\Models\Sexe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SexeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sexes = Sexe::all();
        return view('layout.user.configuration')->with('sexes', $sexes);
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
                'codeSexe'    => 'required',
                'libelleSexe' => 'required'

            ],
            [
                'codeSexe.required'    => 'Le code est obligatoire',
                'libelleSexe.required' => 'Le libelle est obligatoire'
            ]
        );

        if (Sexe::where('code', $request->codeSexe)->exists() === true) {
            session()->flash('message1', 'Cet Enregistrement existe déjà');
            return back();
        } else {
            $insertion = Sexe::firstOrCreate(
                [
                    'code'    => $request->codeSexe,
                ],
                [
                    'libelle' => ucfirst($request->libelleSexe),
                ]
            );

            if ($insertion) {
                session()->flash('message1', 'Ajout Réussi !');
                return back();
            } else {
                session()->flash('echec1', 'Echec,veuillez réessayer !');
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
        $sexe = Sexe::find($id);
        return view('layout.user.sexes.edit_sexe', compact('sexe'));
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
        $sex = Sexe::findOrFail($id);
        $maj = $sex->update([
            'code' => request('codeSexes'),
            'libelle' => request('libelleSexes'),
        ]);
        if ($maj) {
            session()->flash('message1', 'Modification Réussi !');
            return redirect()->route('config_form');
        } else {
            session()->flash('echec1', 'Echec, veuillez réessayer !');
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
        Sexe::destroy($id);
        session()->flash('echec1', 'Suppression réussi!');
        return back();
    }
}
