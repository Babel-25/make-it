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
                'codeSexe'       => 'required',
                'libelleSexe'         => 'required'

            ],
            [
                'codeSexe.required'       => 'Le code est obligatoire',
                'libelleSexe.required'         => 'Le libelle est obligatoire'
            ]
        );

        $verifCodeSexe = DB::table('sexes')->where('code', $request->codeSexe)->get()->first();
        if ($verifCodeSexe != null) {
            //dd($valueCode);
            session()->flash('echec1', 'Insertion echoué, cet enregistrement existe déja !');
            return back();
        } else {
            $sexe = Sexe::create(
                [
                    'code' => $request->codeSexe,
                    'libelle'      => $request->libelleSexe,
                ]
            );

            session()->flash('message1', 'Ajout Réussi !');
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
        $sexe = Sexe::find($id);
        return view('layout.user.configModif')->with('sexes', $sexe);
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
        $input = $sex->update($request->all());
        session()->flash('message1', 'Modification Réussi !');
        /** return view('layout.user.configuration', [
            'sexes' => $input,
        ]);*/
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
        Sexe::destroy($id);
        session()->flash('echec1', 'Suppression réussi!');
        return back();
    }
}
