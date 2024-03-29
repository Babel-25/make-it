<?php

namespace App\Http\Controllers;

use App\Mail\MessageGoogle;
use App\Models\Etat;
use App\Models\Level;
use App\Models\Membre;
use App\Models\Montant;
use App\Models\Paiement;
use App\Models\Personne;
use App\Models\Phase;
use App\Models\User;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    //Fonction d'inscription
    public function registerForm()
    {
        return view('layout.auth.inscription');
    }

    //Fonction d'action inscription + creation personne
    public function registerAction(Request $request)
    {
        //Controle des champs
        $request->validate(
            [
                'pseudo'       => 'required',
                'name'         => 'required',
                'codePai'      => 'required',
                'codePar'      => 'required',
                'adresse'      => 'required',
                'phone'        => 'required',
                'sexe'         => 'required',
                'date'         => 'required',
                'email'        => 'required|email|unique:personnes',
                'password'     => 'required|min:8',
                'passwordConf' => 'required|min:8|same:password'
            ],
            [
                'pseudo.required'       => 'Votre identifiant est obligatoire',
                'name.required'         => 'Le nom et prénom(s) sont requis',
                'codePai.required'      => 'Le code de paiement est obligatoire',
                'codePar.required'      => 'Le code de parrainage est obligatoire',
                'adresse.required'      => 'Votre adresse est obligatoire',
                'sexe.required'         => 'Le sexe est obligatoire',
                'phone.required'        => 'Votre numéro de téléphone est obligatoire',
                'date.required'         => 'Votre date de naissance est obligatoire',
                'email.required'        => 'Votre mail est obligatoire ou ce mail existe déja',
                'password.required'     => 'Caractères minimum requis 8 caractères',
                'passwordConf.required' => 'Les mots de passe ne correspondent pas, veuiller réessayer!'
            ]
        );

        //Générateur de code parrainage
        $generateCodePar = IdGenerator::generate([
            'table'  => 'Personnes',
            'field'  => 'lien_parrainage',
            'length' => 10,
            'prefix' => 'MAK-'
        ]);

        //Verifier l'existence du code de paiement
        $exist_code_payment = Paiement::where('code_paiement', $request->codePai)->exists();
        //Recuperation du statut du code de paiement
        $verif_code_pay_status = Paiement::where('code_paiement', $request->codePai)->first();

        //Vérification de l'existence du code de paiement
        $verif = Paiement::where('code_paiement', $request->codePai)->get();

        $verifCodePar = DB::table('personnes')->where('lien_parrainage', $request->codePar)->get()->first();
        $recupInfo = json_decode($verif, true);

        //Recuperation de l'Etat inactif
        $etat = Etat::where('code', 'INA')->first();

        if ($recupInfo != null && $recupInfo != 'undefined' && $recupInfo[0] != 0 &&  $exist_code_payment === true && $verif_code_pay_status->status === 0) {
            $valueId = $recupInfo[0]['id'];
            if ($verifCodePar != null) {

                //Parrain supreme
                $parrain_supreme = Membre::where('parrain', 0)->first();

                //Informations sur le parrain
                $person_parrain = Personne::where('lien_parrainage', request('codePar'))->first();
                $user_parrain = User::where('id', $person_parrain->id)->first();
                $membre_parrain = Membre::where('parrain', $person_parrain)->first();


                //Verification de l'existence d'un montant du parrain au prealable dans la BDD
                $parrain_montant_exists = Montant::where('personne_id', $person_parrain->id)->first();


                //Verification existance fieuls
                $exist_fieuls = Membre::where('parrain', $person_parrain)->where('position', '<>', 0)->exists();

                $get_parrains = Membre::where('personne_id', $person_parrain->id)->get();


                //Mise à jour de l'etat du code de paiement
                $maj_paiement = $verif_code_pay_status->update([
                    'status' => 1
                ]);

                //***** PHASE A */
                $phase1 = Phase::where('libelle_phase', 'Phase A')->first();
                //Niveau 0
                $level0_p1 = Level::where('phase_id', $phase1->id)->where('libelle_niveau', 'Niveau 0')->first();

                //Niveau 1
                $level1_p1 = Level::where('phase_id', $phase1->id)->where('libelle_niveau', 'Niveau 1')->first();

                //Niveau 2
                $level2_p1 = Level::where('phase_id', $phase1->id)->where('libelle_niveau', 'Niveau 2')->first();

                //Niveau 3
                $level3_p1 = Level::where('phase_id', $phase1->id)->where('libelle_niveau', 'Niveau 3')->first();
                //Nombre de fieuls au niveau 3
                $count_fieul_p1_l3 = Membre::where('phase_id', $phase1->id)->where('level_id', $level3_p1->id)->where('parrain', $membre_parrain->id)->count();

                //Niveau 4
                $level4_p1 = Level::where('phase_id', $phase1->id)->where('libelle_niveau', 'Niveau 4')->first();
                //Nombre de fieuls au niveau 4
                $count_fieul_p1_l4 = Membre::where('phase_id', $phase1->id)->where('level_id', $level4_p1->id)->where('parrain', $membre_parrain->id)->count();

                //Fieuls parrain supreme
                $count_fieul_parrain_sup = Membre::where('phase_id', $phase1->id)->where('parrain', $parrain_supreme->personne_id)->count();

                //***** PHASE B */
                $phase2 = Phase::where('libelle_phase', 'Phase B')->first();
                //Niveau 0
                $level0_p2 = Level::where('phase_id', $phase2->id)->where('libelle_niveau', 'Niveau 0')->first();

                //Niveau 1
                $level1_p2 = Level::where('phase_id', $phase2->id)->where('libelle_niveau', 'Niveau 1')->first();

                //Niveau 2
                $level2_p2 = Level::where('phase_id', $phase2->id)->where('libelle_niveau', 'Niveau 2')->first();

                //Niveau 3
                $level3_p2 = Level::where('phase_id', $phase2->id)->where('libelle_niveau', 'Niveau 3')->first();

                //Niveau 4
                $level4_p2 = Level::where('phase_id', $phase2->id)->where('libelle_niveau', 'Niveau 4')->first();


                if ($membre_parrain->phase_id === $phase1->id and $membre_parrain->etat === 1) {

                    foreach ($get_parrains as $value) {
                        //Verification parrain supreme

                        if ($value->parrain === 0) {
                            //Total Fieuls du parrain supreme Niveau 1 Phase 1
                            $count_fieuls_parrain_sup_l1_p1 = Membre::where('phase_id', $phase1->id)
                                ->where('level_id', $level1_p1->id)
                                ->where('parrain', $value->personne_id)->count();

                            //Total Fieuls du parrain supreme Niveau 2 Phase 1
                            $count_fieuls_parrain_sup_l2_p1 = Membre::where('phase_id', $phase1->id)
                                ->where('level_id', $level2_p1->id)
                                ->where('parrain', $value->personne_id)->count();

                            //Total Fieuls du parrain supreme Niveau 3 Phase 1
                            $count_fieuls_parrain_sup_l3_p1 = Membre::where('phase_id', $phase1->id)
                                ->where('level_id', $level3_p1->id)
                                ->where('parrain', $value->personne_id)->count();

                            //Total Fieuls du parrain supreme Niveau 4 Phase 1
                            $count_fieuls_parrain_sup_l4_p1 = Membre::where('phase_id', $phase1->id)
                                ->where('level_id', $level4_p1->id)
                                ->where('parrain', $value->personne_id)->count();

                            //Information sur la personne
                            $info_person = Personne::where('id', $value->personn_id)->first();


                            #Niveau 1
                            //Parrain Supreme - Total Niveau 1 = 0
                            if ($count_fieuls_parrain_sup_l1_p1 === 0) {
                                // Insertion dans la table users
                                $user = User::create(
                                    [
                                        'identifiant' => strtolower($request->pseudo),
                                        'password'    => Hash::make($request->password),
                                        'status'      => $request->status,
                                        'etat_id'     => $etat->id
                                    ]
                                );

                                // Insertion dans la table Personnes
                                $person = Personne::create(
                                    [
                                        'nom_prenom'      => $request->name,
                                        'code_parrainage' => $request->codePar,
                                        'lien_parrainage' => $generateCodePar,
                                        'adresse'         => $request->adresse,
                                        'contact'         => $request->phone,
                                        'date_naissance'  => $request->date,
                                        'email'           => $request->email,
                                        'sexe_id'         => $request->sexe,
                                        'paiement_id'     => $valueId,
                                        //Recuperation de l'id user
                                        'user_id'         => $user->id
                                    ]
                                );

                                $membre = Membre::firstOrCreate([
                                    'ref_membre'   => Str::random(10),
                                    'phase_id'     => $phase1->id,
                                    'level_id'     => $level1_p1->id,
                                    'personne_id'  => $person->id,
                                    'parrain'      => $person_parrain->id,
                                    'position'     => 1,
                                    'etat'         => 1,
                                    'sponsor_link' => $value->sponsor_link
                                ]);
                                $montant = Montant::firstOrCreate([
                                    'phase_id'        => $phase1->id,
                                    'personne_id'     => $person->id,
                                    'gain_parrainage' => 0,
                                    'gain_niv1'       => 0,
                                    'gain_niv2'       => 0,
                                    'gain_niv3'       => 0,
                                    'gain_niv4'       => 0,
                                    'sponsor_link'    => $membre->sponsor_link
                                ]);

                                //Si le montant parrain existe, on fait une mise à jour sur le montant
                                if (!empty($parrain_montant_exists)) {
                                    $montant_parrain_update = $parrain_montant_exists->update([
                                        'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                        'gain_niv1'       => $parrain_montant_exists->gain_niv1 + 200
                                    ]);
                                }

                                if ($person and $user) {
                                    session()->flash('message', 'Inscription Réussie!');
                                    return back();
                                } else {
                                    session()->flash('message1', 'Inscription échouée, veuillez réessayer!');
                                    return back();
                                }
                            }

                            //Parrain Supreme - Total Niveau 1 = 1
                            if ($count_fieuls_parrain_sup_l1_p1 === 1) {
                                $user = User::create(
                                    [
                                        'identifiant' => strtolower($request->pseudo),
                                        'password'    => Hash::make($request->password),
                                        'status'      => $request->status,
                                        'etat_id'     => $etat->id
                                    ]
                                );
                                $person = Personne::create(
                                    [
                                        'nom_prenom'      => $request->name,
                                        'code_parrainage' => $request->codePar,
                                        'lien_parrainage' => $generateCodePar,
                                        'adresse'         => $request->adresse,
                                        'contact'         => $request->phone,
                                        'date_naissance'  => $request->date,
                                        'email'           => $request->email,
                                        'sexe_id'         => $request->sexe,
                                        'paiement_id'     => $valueId,
                                        //Recuperation de l'id user
                                        'user_id'         => $user->id
                                    ]
                                );
                                $membre = Membre::firstOrCreate([
                                    'ref_membre'  => Str::random(10),
                                    'phase_id'    => $phase1->id,
                                    'level_id'    => $level1_p1->id,
                                    'personne_id' => $person->id,
                                    'parrain'     => $person_parrain->id,
                                    'position'    => 2,
                                    'etat'        => 1,
                                    'sponsor_link' => $value->sponsor_link

                                ]);
                                $montant = Montant::firstOrCreate([
                                    'phase_id'        => $phase1->id,
                                    'personne_id'     => $person->id,
                                    'gain_parrainage' => 0,
                                    'gain_niv1'       => 0,
                                    'gain_niv2'       => 0,
                                    'gain_niv3'       => 0,
                                    'gain_niv4'       => 0,
                                    'sponsor_link'    => $membre->sponsor_link
                                ]);

                                $actif = Etat::where('code', 'ACT')->where('libelle', 'Actif')->first();
                                $update_state = $user_parrain->update([
                                    'etat_id' => $actif->id
                                ]);

                                //Si le montant parrain existe, on fait une mise à jour sur le montant
                                if (!empty($parrain_montant_exists)) {
                                    $montant_parrain_update = $parrain_montant_exists->update([
                                        'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                        'gain_niv1'       => $parrain_montant_exists->gain_niv1 + 200
                                    ]);
                                }

                                if ($person and $user) {
                                    session()->flash('message', 'Inscription Réussie!');
                                    return back();
                                } else {
                                    session()->flash('message1', 'Inscription échouée, veuillez réessayer!');
                                    return back();
                                }
                            }

                            //Parrain Supreme - Niveau 1 plein
                            if ($count_fieuls_parrain_sup_l1_p1 === 2) {


                                #Parrain Supreme - Niveau 2
                                switch ($count_fieuls_parrain_sup_l2_p1) {
                                    case 0:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level2_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 1,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link
                                        ]);
                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);

                                        //Si le montant parrain existe, on fait une mise à jour sur le montant
                                        if (!empty($parrain_montant_exists)) {
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv2'       => $parrain_montant_exists->gain_niv2 + 350
                                            ]);
                                        }

                                        if ($person and $user) {
                                            session()->flash('message', 'Inscription Réussie!');
                                            return back();
                                        } else {
                                            session()->flash('message1', 'Inscription échouée, veuillez réessayer!');
                                            return back();
                                        }
                                        break;
                                    case 1:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level2_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 2,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link
                                        ]);
                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);

                                        //Si le montant parrain existe, on fait une mise à jour sur le montant
                                        if (!empty($parrain_montant_exists)) {
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv2'       => $parrain_montant_exists->gain_niv2 + 350
                                            ]);
                                        }

                                        if ($person and $user) {
                                            session()->flash('message', 'Inscription Réussie!');
                                            return back();
                                        } else {
                                            session()->flash('message1', 'Inscription échouée, veuillez réessayer!');
                                            return back();
                                        }

                                        break;
                                    case 2:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level2_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 3,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link
                                        ]);
                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);

                                        //Si le montant parrain existe, on fait une mise à jour sur le montant
                                        if (!empty($parrain_montant_exists)) {
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv2'       => $parrain_montant_exists->gain_niv2 + 350
                                            ]);
                                        }

                                        if ($person and $user) {
                                            session()->flash('message', 'Inscription Réussie!');
                                            return back();
                                        } else {
                                            session()->flash('message1', 'Inscription échouée, veuillez réessayer!');
                                            return back();
                                        }
                                        break;
                                    case 3:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level2_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 4,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link
                                        ]);
                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);

                                        //Si le montant parrain existe, on fait une mise à jour sur le montant
                                        if (!empty($parrain_montant_exists)) {
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv2'       => $parrain_montant_exists->gain_niv2 + 350
                                            ]);
                                        }

                                        if ($person and $user) {
                                            session()->flash('message', 'Inscription Réussie!');
                                            return back();
                                        } else {
                                            session()->flash('message1', 'Inscription échouée, veuillez réessayer!');
                                            return back();
                                        }
                                        break;
                                }
                            }
                            //Parrain Supreme - Niveau 2 plein
                            if ($count_fieuls_parrain_sup_l2_p1 === 4) {
                                #Parrain Supreme - Niveau 3
                                switch ($count_fieuls_parrain_sup_l3_p1) {
                                    case 0:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level2_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 1,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link
                                        ]);
                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);

                                        //Si le montant parrain existe, on fait une mise à jour sur le montant
                                        if (!empty($parrain_montant_exists)) {
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                            ]);
                                        }


                                        if ($person and $user) {
                                            session()->flash('message', 'Inscription Réussie!');
                                            return back();
                                        } else {
                                            session()->flash('message1', 'Inscription échouée, veuillez réessayer!');
                                            return back();
                                        }
                                        break;
                                    case 1:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level2_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 2,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link
                                        ]);
                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        //Si le montant parrain existe, on fait une mise à jour sur le montant
                                        if (!empty($parrain_montant_exists)) {
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                            ]);
                                        }


                                        if ($person and $user) {
                                            session()->flash('message', 'Inscription Réussie!');
                                            return back();
                                        } else {
                                            session()->flash('message1', 'Inscription échouée, veuillez réessayer!');
                                            return back();
                                        }
                                        break;
                                    case 2:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level2_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 3,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link
                                        ]);
                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        //Si le montant parrain existe, on fait une mise à jour sur le montant
                                        if (!empty($parrain_montant_exists)) {
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                            ]);
                                        }


                                        if ($person and $user) {
                                            session()->flash('message', 'Inscription Réussie!');
                                            return back();
                                        } else {
                                            session()->flash('message1', 'Inscription échouée, veuillez réessayer!');
                                            return back();
                                        }
                                        break;
                                    case 3:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level2_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 4,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link
                                        ]);
                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);

                                        //Si le montant parrain existe, on fait une mise à jour sur le montant
                                        if (!empty($parrain_montant_exists)) {
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                            ]);
                                        }


                                        if ($person and $user) {
                                            session()->flash('message', 'Inscription Réussie!');
                                            return back();
                                        } else {
                                            session()->flash('message1', 'Inscription échouée, veuillez réessayer!');
                                            return back();
                                        }
                                        break;
                                    case 4:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level2_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 5,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link
                                        ]);
                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);

                                        //Si le montant parrain existe, on fait une mise à jour sur le montant
                                        if (!empty($parrain_montant_exists)) {
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                            ]);
                                        }


                                        if ($person and $user) {
                                            session()->flash('message', 'Inscription Réussie!');
                                            return back();
                                        } else {
                                            session()->flash('message1', 'Inscription échouée, veuillez réessayer!');
                                            return back();
                                        }
                                        break;
                                    case 5:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level2_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 6,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link
                                        ]);
                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);

                                        //Si le montant parrain existe, on fait une mise à jour sur le montant
                                        if (!empty($parrain_montant_exists)) {
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                            ]);
                                        }


                                        if ($person and $user) {
                                            session()->flash('message', 'Inscription Réussie!');
                                            return back();
                                        } else {
                                            session()->flash('message1', 'Inscription échouée, veuillez réessayer!');
                                            return back();
                                        }
                                        break;
                                    case 6:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level2_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 7,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link
                                        ]);
                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);

                                        //Si le montant parrain existe, on fait une mise à jour sur le montant
                                        if (!empty($parrain_montant_exists)) {
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                            ]);
                                        }


                                        if ($person and $user) {
                                            session()->flash('message', 'Inscription Réussie!');
                                            return back();
                                        } else {
                                            session()->flash('message1', 'Inscription échouée, veuillez réessayer!');
                                            return back();
                                        }
                                        break;

                                    case 7:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level2_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 8,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link
                                        ]);
                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);

                                        //Si le montant parrain existe, on fait une mise à jour sur le montant
                                        if (!empty($parrain_montant_exists)) {
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                            ]);
                                        }


                                        if ($person and $user) {
                                            session()->flash('message', 'Inscription Réussie!');
                                            return back();
                                        } else {
                                            session()->flash('message1', 'Inscription échouée, veuillez réessayer!');
                                            return back();
                                        }
                                        break;
                                }
                            }

                            //Parrain Supreme - Niveau 3 plein
                            if ($count_fieuls_parrain_sup_l3_p1 === 8) {
                                #Parrain Supreme - Niveau 4
                                switch ($count_fieuls_parrain_sup_l4_p1) {
                                    case 0:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level4_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 1,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link
                                        ]);

                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);

                                        //Si le montant parrain existe, on fait une mise à jour sur le montant
                                        if (!empty($parrain_montant_exists)) {
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv4'       => $parrain_montant_exists->gain_niv4 + 1250
                                            ]);
                                        }

                                        if ($person and $user) {
                                            session()->flash('message', 'Inscription Réussie!');
                                            return back();
                                        } else {
                                            session()->flash('message1', 'Inscription échouée, veuillez réessayer!');
                                            return back();
                                        }

                                        break;
                                    case 1:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level4_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 2,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link
                                        ]);

                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);

                                        //Si le montant parrain existe, on fait une mise à jour sur le montant
                                        if (!empty($parrain_montant_exists)) {
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv4'       => $parrain_montant_exists->gain_niv4 + 1250
                                            ]);
                                        }

                                        if ($person and $user) {
                                            session()->flash('message', 'Inscription Réussie!');
                                            return back();
                                        } else {
                                            session()->flash('message1', 'Inscription échouée, veuillez réessayer!');
                                            return back();
                                        }
                                        break;
                                    case 2:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level4_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 3,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link
                                        ]);

                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);

                                        //Si le montant parrain existe, on fait une mise à jour sur le montant
                                        if (!empty($parrain_montant_exists)) {
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv4'       => $parrain_montant_exists->gain_niv4 + 1250
                                            ]);
                                        }

                                        if ($person and $user) {
                                            session()->flash('message', 'Inscription Réussie!');
                                            return back();
                                        } else {
                                            session()->flash('message1', 'Inscription échouée, veuillez réessayer!');
                                            return back();
                                        }
                                        break;

                                    case 3:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level4_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 4,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link
                                        ]);

                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);

                                        //Si le montant parrain existe, on fait une mise à jour sur le montant
                                        if (!empty($parrain_montant_exists)) {
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv4'       => $parrain_montant_exists->gain_niv4 + 1250
                                            ]);
                                        }

                                        if ($person and $user) {
                                            session()->flash('message', 'Inscription Réussie!');
                                            return back();
                                        } else {
                                            session()->flash('message1', 'Inscription échouée, veuillez réessayer!');
                                            return back();
                                        }
                                        break;
                                    case 4:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level4_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 5,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link
                                        ]);

                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);

                                        //Si le montant parrain existe, on fait une mise à jour sur le montant
                                        if (!empty($parrain_montant_exists)) {
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv4'       => $parrain_montant_exists->gain_niv4 + 1250
                                            ]);
                                        }

                                        if ($person and $user) {
                                            session()->flash('message', 'Inscription Réussie!');
                                            return back();
                                        } else {
                                            session()->flash('message1', 'Inscription échouée, veuillez réessayer!');
                                            return back();
                                        }
                                        break;
                                    case 5:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level4_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 6,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link
                                        ]);

                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);

                                        //Si le montant parrain existe, on fait une mise à jour sur le montant
                                        if (!empty($parrain_montant_exists)) {
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv4'       => $parrain_montant_exists->gain_niv4 + 1250
                                            ]);
                                        }

                                        if ($person and $user) {
                                            session()->flash('message', 'Inscription Réussie!');
                                            return back();
                                        } else {
                                            session()->flash('message1', 'Inscription échouée, veuillez réessayer!');
                                            return back();
                                        }
                                        break;
                                    case 6:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level4_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 7,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link
                                        ]);

                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);

                                        //Si le montant parrain existe, on fait une mise à jour sur le montant
                                        if (!empty($parrain_montant_exists)) {
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv4'       => $parrain_montant_exists->gain_niv4 + 1250
                                            ]);
                                        }

                                        if ($person and $user) {
                                            session()->flash('message', 'Inscription Réussie!');
                                            return back();
                                        } else {
                                            session()->flash('message1', 'Inscription échouée, veuillez réessayer!');
                                            return back();
                                        }
                                        break;
                                    case 7:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level4_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 8,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link
                                        ]);

                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        //Si le montant parrain existe, on fait une mise à jour sur le montant
                                        if (!empty($parrain_montant_exists)) {
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv4'       => $parrain_montant_exists->gain_niv4 + 1250
                                            ]);
                                        }

                                        if ($person and $user) {
                                            session()->flash('message', 'Inscription Réussie!');
                                            return back();
                                        } else {
                                            session()->flash('message1', 'Inscription échouée, veuillez réessayer!');
                                            return back();
                                        }
                                        break;
                                    case 8:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level4_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 9,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link
                                        ]);

                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        //Si le montant parrain existe, on fait une mise à jour sur le montant
                                        if (!empty($parrain_montant_exists)) {
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv4'       => $parrain_montant_exists->gain_niv4 + 1250
                                            ]);
                                        }

                                        if ($person and $user) {
                                            session()->flash('message', 'Inscription Réussie!');
                                            return back();
                                        } else {
                                            session()->flash('message1', 'Inscription échouée, veuillez réessayer!');
                                            return back();
                                        }
                                        break;
                                    case 9:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level4_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 10,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link
                                        ]);

                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        //Si le montant parrain existe, on fait une mise à jour sur le montant
                                        if (!empty($parrain_montant_exists)) {
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv4'       => $parrain_montant_exists->gain_niv4 + 1250
                                            ]);
                                        }

                                        if ($person and $user) {
                                            session()->flash('message', 'Inscription Réussie!');
                                            return back();
                                        } else {
                                            session()->flash('message1', 'Inscription échouée, veuillez réessayer!');
                                            return back();
                                        }
                                        break;
                                    case 10:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level4_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 11,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link
                                        ]);

                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        //Si le montant parrain existe, on fait une mise à jour sur le montant
                                        if (!empty($parrain_montant_exists)) {
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv4'       => $parrain_montant_exists->gain_niv4 + 1250
                                            ]);
                                        }

                                        if ($person and $user) {
                                            session()->flash('message', 'Inscription Réussie!');
                                            return back();
                                        } else {
                                            session()->flash('message1', 'Inscription échouée, veuillez réessayer!');
                                            return back();
                                        }
                                        break;
                                    case 11:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level4_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 12,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link
                                        ]);

                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        //Si le montant parrain existe, on fait une mise à jour sur le montant
                                        if (!empty($parrain_montant_exists)) {
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv4'       => $parrain_montant_exists->gain_niv4 + 1250
                                            ]);
                                        }

                                        if ($person and $user) {
                                            session()->flash('message', 'Inscription Réussie!');
                                            return back();
                                        } else {
                                            session()->flash('message1', 'Inscription échouée, veuillez réessayer!');
                                            return back();
                                        }
                                        break;
                                    case 12:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level4_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 13,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link
                                        ]);

                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        //Si le montant parrain existe, on fait une mise à jour sur le montant
                                        if (!empty($parrain_montant_exists)) {
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv4'       => $parrain_montant_exists->gain_niv4 + 1250
                                            ]);
                                        }

                                        if ($person and $user) {
                                            session()->flash('message', 'Inscription Réussie!');
                                            return back();
                                        } else {
                                            session()->flash('message1', 'Inscription échouée, veuillez réessayer!');
                                            return back();
                                        }
                                        break;
                                    case 13:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level4_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 14,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link
                                        ]);

                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        //Si le montant parrain existe, on fait une mise à jour sur le montant
                                        if (!empty($parrain_montant_exists)) {
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv4'       => $parrain_montant_exists->gain_niv4 + 1250
                                            ]);
                                        }

                                        if ($person and $user) {
                                            session()->flash('message', 'Inscription Réussie!');
                                            return back();
                                        } else {
                                            session()->flash('message1', 'Inscription échouée, veuillez réessayer!');
                                            return back();
                                        }
                                        break;
                                    case 14:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level4_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 15,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link
                                        ]);

                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        //Si le montant parrain existe, on fait une mise à jour sur le montant
                                        if (!empty($parrain_montant_exists)) {
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv4'       => $parrain_montant_exists->gain_niv4 + 1250
                                            ]);
                                        }

                                        if ($person and $user) {
                                            session()->flash('message', 'Inscription Réussie!');
                                            return back();
                                        } else {
                                            session()->flash('message1', 'Inscription échouée, veuillez réessayer!');
                                            return back();
                                        }
                                        break;

                                    case 15:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level4_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 16,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link
                                        ]);

                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);

                                        //Si le montant parrain existe, on fait une mise à jour sur le montant
                                        if (!empty($parrain_montant_exists)) {
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv4'       => $parrain_montant_exists->gain_niv4 + 1250
                                            ]);
                                        }


                                        if ($person and $user) {
                                            session()->flash('message', 'Inscription Réussie!');
                                            return back();
                                        } else {
                                            session()->flash('message1', 'Inscription échouée, veuillez réessayer!');
                                            return back();
                                        }

                                        //Inscription vers phase II
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase2->id,
                                            'level_id'    => $level1_p2->id,
                                            'personne_id' => $value->personne_id,
                                            'parrain'     => $value->parrain,
                                            'position'    => $value->position,
                                            'etat'        => 2,
                                        ]);

                                        //Changement etat dans la table membre à 0
                                        $etat_p1_parrain_sup = Membre::where('personne_id', $value->personne_id)->where('parrain', $value->parrain)->first();
                                        $etat_update_to_p2 = $etat_p1_parrain_sup->update([
                                            'etat' => 2
                                        ]);

                                        break;
                                }
                            }
                            //Parrain Supreme - Niveau 4 plein
                            if ($count_fieul_p1_l4 === 16) {
                                session()->flash('message', 'Maximum de parrainage atteint');
                                return back();
                            }
                        }
                        //Verification parrain simple
                        else {
                            //Total fieuls du parrain supreme Niveau 1 Phase 1
                            $count_fieuls_parrain_sup_l1_p1 = Membre::where('phase_id', $phase1->id)
                                ->where('level_id', $level1_p1->id)
                                ->where('sponsor_link', $value->sponsor_link)->count();

                            //Total fieuls du parrain supreme Niveau 2 Phase 1
                            $count_fieuls_parrain_sup_l2_p1 = Membre::where('phase_id', $phase1->id)
                                ->where('level_id', $level2_p1->id)
                                ->where('sponsor_link', $value->sponsor_link)->count();

                            //Total fieuls du parrain supreme Niveau 3 Phase 1
                            $count_fieuls_parrain_sup_l3_p1 = Membre::where('phase_id', $phase1->id)
                                ->where('level_id', $level3_p1->id)
                                ->where('sponsor_link', $value->sponsor_link)->count();

                            //Total fieuls du parrain supreme Niveau 4 Phase 1
                            $count_fieuls_parrain_sup_l4_p1 = Membre::where('phase_id', $phase1->id)
                                ->where('level_id', $level4_p1->id)
                                ->where('sponsor_link', $value->sponsor_link)->count();

                            //Total fieuls du parrain supreme Niveau 4 Phase 1
                            $count_fieuls_parrain_sup_l4_p1 = Membre::where('phase_id', $phase1->id)
                                ->where('level_id', $level4_p1->id)
                                ->where('sponsor_link', $value->sponsor_link)->count();

                            // $person_sup = Personne::

                            //Total Fieuls du parrain direct Niveau 1 Phase 1
                            $count_fieuls_l1_p1 = Membre::where('phase_id', $phase1->id)
                                ->where('level_id', $level1_p1->id)
                                ->where('parrain', $person_parrain->id)->count();

                            //Total Fieuls du parrain direct Niveau 2 Phase 1
                            $count_fieuls_l2_p1 = Membre::where('phase_id', $phase1->id)
                                ->where('level_id', $level2_p1->id)
                                ->where('parrain', $person_parrain->id)->count();

                            //Total Fieuls du parrain direct Niveau 3 Phase 1
                            $count_fieuls_l3_p1 = Membre::where('phase_id', $phase1->id)
                                ->where('level_id', $level3_p1->id)
                                ->where('parrain', $person_parrain->id)->count();

                            //Total Fieuls du parrain direct Niveau 4 Phase 1
                            $count_fieuls_l4_p1 = Membre::where('phase_id', $phase1->id)
                                ->where('level_id', $level4_p1->id)
                                ->where('parrain', $person_parrain->id)->count();


                            //Parrain Supreme
                            $parrain_sup = Membre::where('parrain', 0)->where('sponsor_link', $value->sponsor_link)->first();

                            //Montant Parrain Supreme
                            $montant_parrain_sup = Montant::where('personne_id', $parrain_sup->personne_id)->first();

                            //Parrain indirect
                            $mon_parrain_membre = Membre::where('personne_id', $value->parrain)->first();

                            //Total Fieuls de mon parrain Niveau 1 Phase 1
                            $count_fieuls_parrain_sup_l1_p1 = Membre::where('phase_id', $phase1->id)
                                ->where('level_id', $level1_p1->id)
                                ->where('parrain', $mon_parrain_membre->personne_id)->count();

                            //Total Fieuls de mon parrain Niveau 2 Phase 1
                            $count_fieuls_parrain_indirect_l2_p1 = Membre::where('phase_id', $phase1->id)
                                ->where('level_id', $level2_p1->id)
                                ->where('parrain', $mon_parrain_membre->personne_id)->count();

                            //Total Fieuls de mon parrain Niveau 3 Phase 1
                            $count_fieuls_parrain_indirect_l3_p1 = Membre::where('phase_id', $phase1->id)
                                ->where('level_id', $level3_p1->id)
                                ->where('parrain', $mon_parrain_membre->personne_id)->count();

                            //Total Fieuls de mon parrain Niveau 4 Phase 1
                            $count_fieuls_parrain_indirect_l4_p1 = Membre::where('phase_id', $phase1->id)
                                ->where('level_id', $level4_p1->id)
                                ->where('parrain', $mon_parrain_membre->personne_id)->count();

                            //Montant parrain indirect
                            $montant_mon_parrain = Montant::where('personne_id', $value->parrain)->first();


                            //Inscription Fieul chez Parrain Simple - Niveau 1 - Total fieuls = 0
                            if ($count_fieuls_l1_p1 === 0) {
                                $user = User::create(
                                    [
                                        'identifiant' => strtolower($request->pseudo),
                                        'password'    => Hash::make($request->password),
                                        'status'      => $request->status,
                                        'etat_id'     => $etat->id
                                    ]
                                );
                                $person = Personne::create(
                                    [
                                        'nom_prenom'      => $request->name,
                                        'code_parrainage' => $request->codePar,
                                        'lien_parrainage' => $generateCodePar,
                                        'adresse'         => $request->adresse,
                                        'contact'         => $request->phone,
                                        'date_naissance'  => $request->date,
                                        'email'           => $request->email,
                                        'sexe_id'         => $request->sexe,
                                        'paiement_id'     => $valueId,
                                        //Recuperation de l'id user
                                        'user_id'         => $user->id
                                    ]
                                );
                                $membre = Membre::firstOrCreate([
                                    'ref_membre'  => Str::random(10),
                                    'phase_id'    => $phase1->id,
                                    'level_id'    => $level1_p1->id,
                                    'personne_id' => $person->id,
                                    'parrain'     => $person_parrain->id,
                                    'position'    => 1,
                                    'etat'        => 1,
                                    'sponsor_link' => $value->sponsor_link
                                ]);
                                $montant = Montant::firstOrCreate([
                                    'phase_id'        => $phase1->id,
                                    'personne_id'     => $person->id,
                                    'gain_parrainage' => 0,
                                    'gain_niv1'       => 0,
                                    'gain_niv2'       => 0,
                                    'gain_niv3'       => 0,
                                    'gain_niv4'       => 0,
                                    'sponsor_link'    => $membre->sponsor_link
                                ]);
                                //Verification du parrain indirect et le parrain supreme
                                if ($montant_parrain_sup->personne_id != $mon_parrain_membre->personne_id) {

                                    //Save fieul membre chez Parrain Supreme Niveau 2 - Total Fieuls  = 4
                                    switch ($count_fieuls_parrain_sup_l2_p1) {
                                        case 0:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $parrain_sup->personne_id,
                                                'position'       => 1,
                                                'etat'           => 0,
                                                'sponsor_link'   => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //MAJ montant parrain supreme
                                            $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                'gain_niv2' => $montant_parrain_sup->gain_niv2 + 350
                                            ]);
                                            break;
                                        case 1:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $parrain_sup->personne_id,
                                                'position'       => 2,
                                                'etat'           => 0,
                                                'sponsor_link'   => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //MAJ montant parrain supreme
                                            $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                'gain_niv2' => $montant_parrain_sup->gain_niv2 + 350
                                            ]);
                                            break;
                                        case 2:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $parrain_sup->personne_id,
                                                'position'       => 3,
                                                'etat'           => 0,
                                                'sponsor_link'   => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //MAJ montant parrain supreme
                                            $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                'gain_niv2' => $montant_parrain_sup->gain_niv2 + 350
                                            ]);
                                            break;
                                        case 3:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $parrain_sup->personne_id,
                                                'position'       => 4,
                                                'etat'           => 0,
                                                'sponsor_link'   => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //MAJ montant parrain supreme
                                            $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                'gain_niv2' => $montant_parrain_sup->gain_niv2 + 350
                                            ]);
                                            break;
                                    }

                                    //Niveau 2  Parrain Supreme plein
                                    if ($count_fieuls_parrain_sup_l2_p1 === 4) {
                                        //Save fieul membre Niveau 3 Parrain supreme - Total fieul 8
                                        switch ($count_fieuls_parrain_sup_l3_p1) {
                                            case 0:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 1,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv3' => $montant_parrain_sup->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 1:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 2,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv3' => $montant_parrain_sup->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 2:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 3,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv3' => $montant_parrain_sup->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 3:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 4,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv3' => $montant_parrain_sup->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 4:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 5,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv3' => $montant_parrain_sup->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 5:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 6,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv3' => $montant_parrain_sup->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 6:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 7,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv3' => $montant_parrain_sup->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 7:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 8,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv3' => $montant_parrain_sup->gain_niv3 + 400
                                                ]);
                                                break;
                                        }
                                    }
                                    //Niveau 3 Parrain supreme plein
                                    if ($count_fieuls_parrain_sup_l3_p1 === 8) {
                                        //Save Fieul membre Parrain Supreme Niveau 4 - total fieul = 16

                                        switch ($count_fieuls_parrain_sup_l4_p1) {
                                            case 0:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 1,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 1:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 2,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 2:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 3,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 3:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 4,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 4:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 5,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 5:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 6,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 6:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 7,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 7:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 8,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 8:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 9,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;

                                            case 9:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 10,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 10:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 11,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 11:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 12,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 12:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 13,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 13:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 14,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 14:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 15,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 15:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 16,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                        }
                                    }
                                    //Niveau 4 plein Parrain Supreme
                                    if ($count_fieuls_parrain_sup_l4_p1) {
                                        session()->flash('message', 'Maximum de fieuls atteints!');
                                        return back();
                                    }

                                    //Verification Fieuls Mon parrain Niveau 2
                                    switch ($count_fieuls_parrain_indirect_l2_p1) {
                                        case 0:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $mon_parrain_membre->personne_id,
                                                'position'       => 1,
                                                'etat'           => 0,
                                                'sponsor_link' => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //Mise à jour du montant du parrain indirect
                                            $montant_parrain_update = $montant_mon_parrain->update([
                                                'gain_niv2' => $montant_mon_parrain->gain_niv2 + 350
                                            ]);
                                            break;
                                        case 1:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $mon_parrain_membre->personne_id,
                                                'position'       => 2,
                                                'etat'           => 0,
                                                'sponsor_link' => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //Mise à jour du montant du parrain indirect
                                            $montant_parrain_update = $montant_mon_parrain->update([
                                                'gain_niv2' => $montant_mon_parrain->gain_niv2 + 350
                                            ]);
                                            break;
                                        case 2:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $mon_parrain_membre->personne_id,
                                                'position'       => 3,
                                                'etat'           => 0,
                                                'sponsor_link' => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);
                                            //Mise à jour du montant du parrain indirect
                                            $montant_parrain_update = $montant_mon_parrain->update([
                                                'gain_niv2' => $montant_mon_parrain->gain_niv2 + 350
                                            ]);
                                            break;
                                        case 3:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'    => Str::random(10),
                                                'phase_id'      => $phase1->id,
                                                'level_id'      => $level2_p1->id,
                                                'personne_id'   => $person->id,
                                                'parrain'       => $mon_parrain_membre->personne_id,
                                                'position'      => 4,
                                                'etat'          => 0,
                                                'parrain_direct' => 'NON'
                                            ]);
                                            //Mise à jour du montant du parrain indirect
                                            $montant_parrain_update = $montant_mon_parrain->update([
                                                'gain_niv2' => $montant_mon_parrain->gain_niv2 + 350
                                            ]);
                                            break;
                                    }

                                    //Mon parrain - Niveau 2 plein
                                    if ($count_fieuls_parrain_indirect_l2_p1 === 4) {
                                        //Save fieul membre chez Mon parrain Niveau 4 -total fieul 16
                                        switch ($count_fieuls_parrain_indirect_l3_p1) {
                                            case 0:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 1,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv3' => $montant_mon_parrain->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 1:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        =>  $mon_parrain_membre->personne_id,
                                                    'position'       => 2,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv3' => $montant_mon_parrain->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 2:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 3,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv3' => $montant_mon_parrain->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 3:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 4,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv3' => $montant_mon_parrain->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 4:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 5,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv3' => $montant_mon_parrain->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 5:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 6,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv3' => $montant_mon_parrain->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 6:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 7,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv3' => $montant_mon_parrain->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 7:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 8,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv3' => $montant_mon_parrain->gain_niv3 + 400
                                                ]);
                                                break;
                                        }
                                    }
                                    if ($count_fieuls_parrain_indirect_l3_p1 === 8) {
                                        //Verification Fieuls Mon parrain Niveau 4
                                        switch ($count_fieuls_parrain_indirect_l4_p1) {
                                            case 0:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 1,
                                                    'etat'           => 0,
                                                    'sponsor_link' => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 1:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 2,
                                                    'etat'           => 0,
                                                    'sponsor_link' => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 2:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 3,
                                                    'etat'           => 0,
                                                    'sponsor_link' => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 3:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 4,
                                                    'etat'           => 0,
                                                    'sponsor_link' => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 4:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 5,
                                                    'etat'           => 0,
                                                    'sponsor_link' => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 5:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 6,
                                                    'etat'           => 0,
                                                    'sponsor_link' => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 6:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 7,
                                                    'etat'           => 0,
                                                    'sponsor_link' => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 7:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 8,
                                                    'etat'           => 0,
                                                    'sponsor_link' => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 8:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 9,
                                                    'etat'           => 0,
                                                    'sponsor_link' => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 9:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 10,
                                                    'etat'           => 0,
                                                    'sponsor_link' => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 10:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 11,
                                                    'etat'           => 0,
                                                    'sponsor_link' => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 11:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 12,
                                                    'etat'           => 0,
                                                    'sponsor_link' => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 12:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 13,
                                                    'etat'           => 0,
                                                    'sponsor_link' => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 13:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 14,
                                                    'etat'           => 0,
                                                    'sponsor_link' => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 14:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 15,
                                                    'etat'           => 0,
                                                    'sponsor_link' => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 15:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 16,
                                                    'etat'           => 0,
                                                    'sponsor_link' => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                        }
                                    }
                                }
                                //Verification SI le parrain indirect est le parrain supreme
                                else {

                                    //Verification Fieuls Mon parrain Niveau 2
                                    switch ($count_fieuls_parrain_indirect_l2_p1) {
                                        case 0:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $mon_parrain_membre->personne_id,
                                                'position'       => 1,
                                                'etat'           => 0,
                                                'sponsor_link' => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //Mise à jour du montant du parrain indirect
                                            $montant_parrain_update = $montant_mon_parrain->update([
                                                'gain_niv2' => $montant_mon_parrain->gain_niv2 + 350
                                            ]);
                                            break;
                                        case 1:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $mon_parrain_membre->personne_id,
                                                'position'       => 2,
                                                'etat'           => 0,
                                                'sponsor_link' => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //Mise à jour du montant du parrain indirect
                                            $montant_parrain_update = $montant_mon_parrain->update([
                                                'gain_niv2' => $montant_mon_parrain->gain_niv2 + 350
                                            ]);
                                            break;
                                        case 2:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $mon_parrain_membre->personne_id,
                                                'position'       => 3,
                                                'etat'           => 0,
                                                'sponsor_link' => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);
                                            //Mise à jour du montant du parrain indirect
                                            $montant_parrain_update = $montant_mon_parrain->update([
                                                'gain_niv2' => $montant_mon_parrain->gain_niv2 + 350
                                            ]);
                                            break;
                                        case 3:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'    => Str::random(10),
                                                'phase_id'      => $phase1->id,
                                                'level_id'      => $level2_p1->id,
                                                'personne_id'   => $person->id,
                                                'parrain'       => $mon_parrain_membre->personne_id,
                                                'position'      => 4,
                                                'etat'          => 0,
                                                'parrain_direct' => 'NON'
                                            ]);
                                            //Mise à jour du montant du parrain indirect
                                            $montant_parrain_update = $montant_mon_parrain->update([
                                                'gain_niv2' => $montant_mon_parrain->gain_niv2 + 350
                                            ]);
                                            break;
                                    }

                                    //Mon parrain - Niveau 2 plein
                                    if ($count_fieuls_parrain_indirect_l2_p1 === 4) {
                                        //Save fieul membre chez Mon parrain Niveau 4 -total fieul 16
                                        switch ($count_fieuls_parrain_indirect_l3_p1) {
                                            case 0:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 1,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv3' => $montant_mon_parrain->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 1:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        =>  $mon_parrain_membre->personne_id,
                                                    'position'       => 2,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv3' => $montant_mon_parrain->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 2:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 3,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv3' => $montant_mon_parrain->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 3:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 4,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv3' => $montant_mon_parrain->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 4:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 5,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv3' => $montant_mon_parrain->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 5:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 6,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv3' => $montant_mon_parrain->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 6:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 7,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv3' => $montant_mon_parrain->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 7:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 8,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv3' => $montant_mon_parrain->gain_niv3 + 400
                                                ]);
                                                break;
                                        }
                                    }
                                    if ($count_fieuls_parrain_indirect_l3_p1 === 8) {
                                        //Verification Fieuls Mon parrain Niveau 4
                                        switch ($count_fieuls_parrain_indirect_l4_p1) {
                                            case 0:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 1,
                                                    'etat'           => 0,
                                                    'sponsor_link' => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 1:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 2,
                                                    'etat'           => 0,
                                                    'sponsor_link' => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 2:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 3,
                                                    'etat'           => 0,
                                                    'sponsor_link' => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 3:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 4,
                                                    'etat'           => 0,
                                                    'sponsor_link' => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 4:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 5,
                                                    'etat'           => 0,
                                                    'sponsor_link' => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 5:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 6,
                                                    'etat'           => 0,
                                                    'sponsor_link' => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 6:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 7,
                                                    'etat'           => 0,
                                                    'sponsor_link' => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 7:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 8,
                                                    'etat'           => 0,
                                                    'sponsor_link' => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 8:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 9,
                                                    'etat'           => 0,
                                                    'sponsor_link' => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 9:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 10,
                                                    'etat'           => 0,
                                                    'sponsor_link' => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 10:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 11,
                                                    'etat'           => 0,
                                                    'sponsor_link' => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 11:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 12,
                                                    'etat'           => 0,
                                                    'sponsor_link' => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 12:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 13,
                                                    'etat'           => 0,
                                                    'sponsor_link' => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 13:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 14,
                                                    'etat'           => 0,
                                                    'sponsor_link' => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 14:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 15,
                                                    'etat'           => 0,
                                                    'sponsor_link' => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 15:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 16,
                                                    'etat'           => 0,
                                                    'sponsor_link' => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                        }
                                    }
                                }


                                //Si le montant parrain existe, on fait une mise à jour sur le montant
                                if (!empty($parrain_montant_exists)) {
                                    $montant_parrain_update = $parrain_montant_exists->update([
                                        'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                        'gain_niv1'       => $parrain_montant_exists->gain_niv1 + 200
                                    ]);
                                }

                                if ($person and $user) {
                                    session()->flash('message', 'Inscription Réussie!');
                                    return back();
                                } else {
                                    session()->flash('message1', 'Inscription échouée, veuillez réessayer!');
                                    return back();
                                }
                            }

                            //Parrain Simple - Total fieuls = 1
                            if ($count_fieuls_l1_p1 === 1) {
                                $user = User::create(
                                    [
                                        'identifiant' => strtolower($request->pseudo),
                                        'password'    => Hash::make($request->password),
                                        'status'      => $request->status,
                                        'etat_id'     => $etat->id
                                    ]
                                );
                                $person = Personne::create(
                                    [
                                        'nom_prenom'      => $request->name,
                                        'code_parrainage' => $request->codePar,
                                        'lien_parrainage' => $generateCodePar,
                                        'adresse'         => $request->adresse,
                                        'contact'         => $request->phone,
                                        'date_naissance'  => $request->date,
                                        'email'           => $request->email,
                                        'sexe_id'         => $request->sexe,
                                        'paiement_id'     => $valueId,
                                        //Recuperation de l'id user
                                        'user_id'         => $user->id
                                    ]
                                );
                                $membre = Membre::firstOrCreate([
                                    'ref_membre'  => Str::random(10),
                                    'phase_id'    => $phase1->id,
                                    'level_id'    => $level1_p1->id,
                                    'personne_id' => $person->id,
                                    'parrain'     => $value->parrain,
                                    'position'    => 2,
                                    'etat'        => 1,
                                    'sponsor_link' => $value->sponsor_link
                                ]);
                                $montant = Montant::firstOrCreate([
                                    'phase_id'        => $phase1->id,
                                    'personne_id'     => $person->id,
                                    'gain_parrainage' => 0,
                                    'gain_niv1'       => 0,
                                    'gain_niv2'       => 0,
                                    'gain_niv3'       => 0,
                                    'gain_niv4'       => 0,
                                    'sponsor_link'    => $membre->sponsor_link
                                ]);
                                //changement etat si deux fieuls sont déjà enregistrés
                                $actif = Etat::where('code', 'ACT')->where('libelle', 'Actif')->first();
                                $update_state = $user_parrain->update([
                                    'etat_id' => $actif->id
                                ]);

                                if ($montant_parrain_sup->personne_id != $mon_parrain_membre->personne_id) {
                                    //Save fieul membre Parrain Supreme Niveau 2
                                    switch ($count_fieuls_parrain_sup_l2_p1) {
                                        case 0:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $parrain_sup->personne_id,
                                                'position'       => 1,
                                                'etat'           => 0,
                                                'sponsor_link'   => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //MAJ montant parrain supreme
                                            $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                'gain_niv2' => $montant_parrain_sup->gain_niv2 + 350
                                            ]);
                                            break;
                                        case 1:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $parrain_sup->personne_id,
                                                'position'       => 2,
                                                'etat'           => 0,
                                                'sponsor_link'   => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //MAJ montant parrain supreme
                                            $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                'gain_niv2' => $montant_parrain_sup->gain_niv2 + 350
                                            ]);
                                            break;
                                        case 2:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $parrain_sup->personne_id,
                                                'position'       => 3,
                                                'etat'           => 0,
                                                'sponsor_link'   => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //MAJ montant parrain supreme
                                            $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                'gain_niv2' => $montant_parrain_sup->gain_niv2 + 350
                                            ]);
                                            break;
                                        case 3:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $parrain_sup->personne_id,
                                                'position'       => 4,
                                                'etat'           => 0,
                                                'sponsor_link'   => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //MAJ montant parrain supreme
                                            $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                'gain_niv2' => $montant_parrain_sup->gain_niv2 + 350
                                            ]);
                                            break;
                                    }

                                    //Niveau 2  Parrain Supreme plein
                                    if ($count_fieuls_parrain_sup_l2_p1 === 4) {
                                        //Save fieul membre Niveau 3 Parrain supreme - Total fieul 8
                                        switch ($count_fieuls_parrain_sup_l3_p1) {
                                            case 0:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 1,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv3' => $montant_parrain_sup->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 1:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 2,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv3' => $montant_parrain_sup->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 2:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 3,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv3' => $montant_parrain_sup->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 3:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 4,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv3' => $montant_parrain_sup->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 4:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 5,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv3' => $montant_parrain_sup->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 5:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 6,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv3' => $montant_parrain_sup->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 6:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 7,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv3' => $montant_parrain_sup->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 7:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 8,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv3' => $montant_parrain_sup->gain_niv3 + 400
                                                ]);
                                                break;
                                        }
                                    }
                                    //Niveau 3 Parrain supreme plein
                                    if ($count_fieuls_parrain_sup_l3_p1 === 8) {
                                        //Save Fieul membre Parrain Supreme Niveau 4 - total fieul = 16

                                        switch ($count_fieuls_parrain_sup_l4_p1) {
                                            case 0:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 1,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 1:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 2,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 2:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 3,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 3:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 4,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 4:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 5,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 5:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 6,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 6:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 7,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 7:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 8,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 8:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 9,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;

                                            case 9:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 10,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 10:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 11,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 11:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 12,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 12:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 13,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 13:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 14,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 14:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 15,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 15:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 16,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                        }
                                    }
                                    //Niveau 4 plein Parrain Supreme
                                    if ($count_fieuls_parrain_sup_l4_p1) {
                                        session()->flash('message', 'Maximum de fieuls atteints!');
                                        return back();
                                    }

                                    //Verification Fieuls Mon parrain Niveau 2
                                    switch ($count_fieuls_parrain_indirect_l2_p1) {
                                        case 0:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'  => Str::random(10),
                                                'phase_id'    => $phase1->id,
                                                'level_id'    => $level2_p1->id,
                                                'personne_id' => $person->id,
                                                'parrain'     => $mon_parrain_membre->personne_id,
                                                'position'    => 1,
                                                'etat'        => 1,
                                                'sponsor_link' => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);
                                            //Mise à jour du montant du parrain indirect
                                            $montant_parrain_update = $montant_mon_parrain->update([
                                                'gain_niv2' => $montant_mon_parrain->gain_niv2 + 350
                                            ]);
                                            break;
                                        case 1:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'  => Str::random(10),
                                                'phase_id'    => $phase1->id,
                                                'level_id'    => $level2_p1->id,
                                                'personne_id' => $person->id,
                                                'parrain'     => $mon_parrain_membre->personne_id,
                                                'position'    => 2,
                                                'etat'        => 1,
                                                'sponsor_link' => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);
                                            //Mise à jour du montant du parrain indirect
                                            $montant_parrain_update = $montant_mon_parrain->update([
                                                'gain_niv2' => $montant_mon_parrain->gain_niv2 + 350
                                            ]);
                                            break;
                                        case 2:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'  => Str::random(10),
                                                'phase_id'    => $phase1->id,
                                                'level_id'    => $level2_p1->id,
                                                'personne_id' => $person->id,
                                                'parrain'     => $mon_parrain_membre->personne_id,
                                                'position'    => 3,
                                                'etat'        => 1,
                                                'sponsor_link' => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);
                                            //Mise à jour du montant du parrain indirect
                                            $montant_parrain_update = $montant_mon_parrain->update([
                                                'gain_niv2' => $montant_mon_parrain->gain_niv2 + 350
                                            ]);
                                            break;
                                        case 3:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'  => Str::random(10),
                                                'phase_id'    => $phase1->id,
                                                'level_id'    => $level2_p1->id,
                                                'personne_id' => $person->id,
                                                'parrain'     => $mon_parrain_membre->personne_id,
                                                'position'    => 4,
                                                'etat'        => 1,
                                                'sponsor_link' => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);
                                            //Mise à jour du montant du parrain indirect
                                            $montant_parrain_update = $montant_mon_parrain->update([
                                                'gain_niv2' => $montant_mon_parrain->gain_niv2 + 350
                                            ]);
                                            break;
                                    }

                                    //Verification Fieuls Mon parrain Niveau 2 plein
                                    if ($count_fieuls_parrain_indirect_l2_p1 === 4) {
                                        //Verification Fieuls Mon parrain Niveau 3
                                        switch ($count_fieuls_parrain_indirect_l3_p1) {
                                            case 0:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 1,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv3' => $montant_mon_parrain->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 1:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 2,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv3' => $montant_mon_parrain->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 2:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 3,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv3' => $montant_mon_parrain->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 3:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 4,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv3' => $montant_mon_parrain->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 4:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 5,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv3' => $montant_mon_parrain->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 5:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 6,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv3' => $montant_mon_parrain->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 6:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 7,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv3' => $montant_mon_parrain->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 7:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 8,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv3' => $montant_mon_parrain->gain_niv3 + 400
                                                ]);
                                                break;
                                        }
                                    }

                                    //Verification Fieuls Mon parrain Niveau 3 plein
                                    if ($count_fieuls_parrain_indirect_l3_p1 === 8) {
                                        //Verification Fieuls Mon parrain Niveau 4
                                        switch ($count_fieuls_parrain_indirect_l4_p1) {
                                            case 0:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 1,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 1:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 2,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 2:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 3,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 3:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 4,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 4:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 5,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 5:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 6,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 6:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 7,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 7:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 8,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 8:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 9,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 9:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 10,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 10:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 11,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 11:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 12,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 12:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 13,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 13:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 14,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 14:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 15,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 15:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 16,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                        }
                                    }
                                }
                                //Verification SI le parrain indirect est le parrain supreme
                                else {
                                    //Verification Fieuls Mon parrain Niveau 2
                                    switch ($count_fieuls_parrain_indirect_l2_p1) {
                                        case 0:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'  => Str::random(10),
                                                'phase_id'    => $phase1->id,
                                                'level_id'    => $level2_p1->id,
                                                'personne_id' => $person->id,
                                                'parrain'     => $mon_parrain_membre->personne_id,
                                                'position'    => 1,
                                                'etat'        => 1,
                                                'sponsor_link' => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);
                                            //Mise à jour du montant du parrain indirect
                                            $montant_parrain_update = $montant_mon_parrain->update([
                                                'gain_niv2' => $montant_mon_parrain->gain_niv2 + 350
                                            ]);
                                            break;
                                        case 1:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'  => Str::random(10),
                                                'phase_id'    => $phase1->id,
                                                'level_id'    => $level2_p1->id,
                                                'personne_id' => $person->id,
                                                'parrain'     => $mon_parrain_membre->personne_id,
                                                'position'    => 2,
                                                'etat'        => 1,
                                                'sponsor_link' => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);
                                            //Mise à jour du montant du parrain indirect
                                            $montant_parrain_update = $montant_mon_parrain->update([
                                                'gain_niv2' => $montant_mon_parrain->gain_niv2 + 350
                                            ]);
                                            break;
                                        case 2:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'  => Str::random(10),
                                                'phase_id'    => $phase1->id,
                                                'level_id'    => $level2_p1->id,
                                                'personne_id' => $person->id,
                                                'parrain'     => $mon_parrain_membre->personne_id,
                                                'position'    => 3,
                                                'etat'        => 1,
                                                'sponsor_link' => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);
                                            //Mise à jour du montant du parrain indirect
                                            $montant_parrain_update = $montant_mon_parrain->update([
                                                'gain_niv2' => $montant_mon_parrain->gain_niv2 + 350
                                            ]);
                                            break;
                                        case 3:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'  => Str::random(10),
                                                'phase_id'    => $phase1->id,
                                                'level_id'    => $level2_p1->id,
                                                'personne_id' => $person->id,
                                                'parrain'     => $mon_parrain_membre->personne_id,
                                                'position'    => 4,
                                                'etat'        => 1,
                                                'sponsor_link' => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);
                                            //Mise à jour du montant du parrain indirect
                                            $montant_parrain_update = $montant_mon_parrain->update([
                                                'gain_niv2' => $montant_mon_parrain->gain_niv2 + 350
                                            ]);
                                            break;
                                    }

                                    //Verification Fieuls Mon parrain Niveau 2 plein
                                    if ($count_fieuls_parrain_indirect_l2_p1 === 4) {
                                        //Verification Fieuls Mon parrain Niveau 3
                                        switch ($count_fieuls_parrain_indirect_l3_p1) {
                                            case 0:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 1,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv3' => $montant_mon_parrain->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 1:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 2,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv3' => $montant_mon_parrain->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 2:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 3,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv3' => $montant_mon_parrain->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 3:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 4,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv3' => $montant_mon_parrain->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 4:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 5,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv3' => $montant_mon_parrain->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 5:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 6,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv3' => $montant_mon_parrain->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 6:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 7,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv3' => $montant_mon_parrain->gain_niv3 + 400
                                                ]);
                                                break;
                                            case 7:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 8,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv3' => $montant_mon_parrain->gain_niv3 + 400
                                                ]);
                                                break;
                                        }
                                    }

                                    //Verification Fieuls Mon parrain Niveau 3 plein
                                    if ($count_fieuls_parrain_indirect_l3_p1 === 8) {
                                        //Verification Fieuls Mon parrain Niveau 4
                                        switch ($count_fieuls_parrain_indirect_l4_p1) {
                                            case 0:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 1,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 1:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 2,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 2:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 3,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 3:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 4,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 4:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 5,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 5:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 6,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 6:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 7,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 7:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 8,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 8:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 9,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 9:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 10,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 10:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 11,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 11:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 12,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 12:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 13,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 13:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 14,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 14:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 15,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 15:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 16,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                        }
                                    }
                                }

                                //Si le montant parrain existe, on fait une mise à jour sur le montant
                                if (!empty($parrain_montant_exists)) {
                                    $montant_parrain_update = $parrain_montant_exists->update([
                                        'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                        'gain_niv1'       => $parrain_montant_exists->gain_niv1 + 200
                                    ]);
                                }

                                if ($person and $user) {
                                    session()->flash('message', 'Inscription Réussie!');
                                    return back();
                                } else {
                                    session()->flash('message1', 'Inscription échouée, veuillez réessayer!');
                                    return back();
                                }
                            }

                            //Parrain Simple - Niveau 1 Plein
                            if ($count_fieuls_l1_p1 === 2) {


                                #Parrain Simple - Niveau 2
                                switch ($count_fieuls_l2_p1) {
                                    case 0:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level2_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 1,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link,
                                        ]);
                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);

                                        $montant_parrain_update = $parrain_montant_exists->update([
                                            'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                            'gain_niv2'       => $parrain_montant_exists->gain_niv2 + 350
                                        ]);
                                        break;
                                    case 1:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level2_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 2,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link,
                                        ]);
                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        $montant_parrain_update = $parrain_montant_exists->update([
                                            'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                            'gain_niv2'       => $parrain_montant_exists->gain_niv2 + 350
                                        ]);
                                        break;
                                    case 2:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level2_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 3,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link,
                                        ]);
                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        $montant_parrain_update = $parrain_montant_exists->update([
                                            'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                            'gain_niv2'       => $parrain_montant_exists->gain_niv2 + 350
                                        ]);
                                        break;
                                    case 3:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level2_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 4,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link,
                                        ]);
                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        $montant_parrain_update = $parrain_montant_exists->update([
                                            'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                            'gain_niv2'       => $parrain_montant_exists->gain_niv2 + 350
                                        ]);
                                        break;
                                }

                                if ($montant_parrain_sup->personne_id != $mon_parrain_membre->personne_id) {
                                    //Save fieul membre Parrain Supreme Niveau 3
                                    switch ($count_fieuls_parrain_sup_l3_p1) {
                                        case 0:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $parrain_sup->personne_id,
                                                'position'       => 1,
                                                'etat'           => 0,
                                                'sponsor_link'   => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //MAJ montant parrain supreme
                                            $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                'gain_niv3' => $montant_parrain_sup->gain_niv3 + 400
                                            ]);
                                            break;
                                        case 1:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $parrain_sup->personne_id,
                                                'position'       => 2,
                                                'etat'           => 0,
                                                'sponsor_link'   => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //MAJ montant parrain supreme
                                            $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                'gain_niv3' => $montant_parrain_sup->gain_niv3 + 400
                                            ]);
                                            break;
                                        case 2:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $parrain_sup->personne_id,
                                                'position'       => 3,
                                                'etat'           => 0,
                                                'sponsor_link'   => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //MAJ montant parrain supreme
                                            $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                'gain_niv3' => $montant_parrain_sup->gain_niv3 + 400
                                            ]);
                                            break;
                                        case 3:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $parrain_sup->personne_id,
                                                'position'       => 4,
                                                'etat'           => 0,
                                                'sponsor_link'   => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //MAJ montant parrain supreme
                                            $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                'gain_niv3' => $montant_parrain_sup->gain_niv3 + 400
                                            ]);
                                            break;
                                        case 4:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $parrain_sup->personne_id,
                                                'position'       => 5,
                                                'etat'           => 0,
                                                'sponsor_link'   => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //MAJ montant parrain supreme
                                            $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                'gain_niv3' => $montant_parrain_sup->gain_niv3 + 400
                                            ]);
                                            break;
                                        case 5:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $parrain_sup->personne_id,
                                                'position'       => 6,
                                                'etat'           => 0,
                                                'sponsor_link'   => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //MAJ montant parrain supreme
                                            $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                'gain_niv3' => $montant_parrain_sup->gain_niv3 + 400
                                            ]);
                                            break;
                                        case 6:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $parrain_sup->personne_id,
                                                'position'       => 7,
                                                'etat'           => 0,
                                                'sponsor_link'   => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //MAJ montant parrain supreme
                                            $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                'gain_niv3' => $montant_parrain_sup->gain_niv3 + 400
                                            ]);
                                            break;
                                        case 7:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $parrain_sup->personne_id,
                                                'position'       => 8,
                                                'etat'           => 0,
                                                'sponsor_link'   => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //MAJ montant parrain supreme
                                            $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                'gain_niv3' => $montant_parrain_sup->gain_niv3 + 400
                                            ]);
                                            break;
                                    }

                                    //Niveau 3 Parrain supreme plein
                                    if ($count_fieuls_parrain_sup_l3_p1 === 8) {
                                        //Save Fieul membre Parrain Supreme Niveau 4 - total fieul = 16
                                        switch ($count_fieuls_parrain_sup_l4_p1) {
                                            case 0:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 1,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 1:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 2,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 2:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 3,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 3:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 4,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 4:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 5,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 5:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 6,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 6:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 7,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 7:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 8,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 8:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 9,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;

                                            case 9:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 10,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 10:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 11,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 11:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 12,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 12:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 13,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 13:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 14,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 14:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 15,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 15:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level2_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $parrain_sup->personne_id,
                                                    'position'       => 16,
                                                    'etat'           => 0,
                                                    'sponsor_link'   => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);

                                                //MAJ montant parrain supreme
                                                $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                    'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                                ]);
                                                break;
                                        }
                                    }
                                    //Niveau 4 plein Parrain Supreme
                                    if ($count_fieuls_parrain_sup_l4_p1) {
                                        session()->flash('message', 'Maximum de fieuls atteints!');
                                        return back();
                                    }

                                    //Verification Fieuls Mon parrain Niveau 3
                                    switch ($count_fieuls_parrain_indirect_l3_p1) {
                                        case 0:
                                            $user = User::create(
                                                [
                                                    'identifiant' => strtolower($request->pseudo),
                                                    'password'    => Hash::make($request->password),
                                                    'status'      => $request->status,
                                                    'etat_id'     => $etat->id
                                                ]
                                            );
                                            $person = Personne::create(
                                                [
                                                    'nom_prenom'      => $request->name,
                                                    'code_parrainage' => $request->codePar,
                                                    'lien_parrainage' => $generateCodePar,
                                                    'adresse'         => $request->adresse,
                                                    'contact'         => $request->phone,
                                                    'date_naissance'  => $request->date,
                                                    'email'           => $request->email,
                                                    'sexe_id'         => $request->sexe,
                                                    'paiement_id'     => $valueId,
                                                    //Recuperation de l'id user
                                                    'user_id'         => $user->id
                                                ]
                                            );
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $mon_parrain_membre->personne_id,
                                                'position'       => 1,
                                                'etat'           => 0,
                                                'parrain_direct' => 'NON'
                                            ]);
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                            ]);
                                            break;
                                        case 1:
                                            $user = User::create(
                                                [
                                                    'identifiant' => strtolower($request->pseudo),
                                                    'password'    => Hash::make($request->password),
                                                    'status'      => $request->status,
                                                    'etat_id'     => $etat->id
                                                ]
                                            );
                                            $person = Personne::create(
                                                [
                                                    'nom_prenom'      => $request->name,
                                                    'code_parrainage' => $request->codePar,
                                                    'lien_parrainage' => $generateCodePar,
                                                    'adresse'         => $request->adresse,
                                                    'contact'         => $request->phone,
                                                    'date_naissance'  => $request->date,
                                                    'email'           => $request->email,
                                                    'sexe_id'         => $request->sexe,
                                                    'paiement_id'     => $valueId,
                                                    //Recuperation de l'id user
                                                    'user_id'         => $user->id
                                                ]
                                            );
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'    => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $mon_parrain_membre->personne_id,
                                                'position'       => 2,
                                                'etat'           => 0,
                                                'parrain_direct' => 'NON'
                                            ]);
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                            ]);
                                            break;
                                        case 2:
                                            $user = User::create(
                                                [
                                                    'identifiant' => strtolower($request->pseudo),
                                                    'password'    => Hash::make($request->password),
                                                    'status'      => $request->status,
                                                    'etat_id'     => $etat->id
                                                ]
                                            );
                                            $person = Personne::create(
                                                [
                                                    'nom_prenom'      => $request->name,
                                                    'code_parrainage' => $request->codePar,
                                                    'lien_parrainage' => $generateCodePar,
                                                    'adresse'         => $request->adresse,
                                                    'contact'         => $request->phone,
                                                    'date_naissance'  => $request->date,
                                                    'email'           => $request->email,
                                                    'sexe_id'         => $request->sexe,
                                                    'paiement_id'     => $valueId,
                                                    //Recuperation de l'id user
                                                    'user_id'         => $user->id
                                                ]
                                            );
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $mon_parrain_membre->personne_id,
                                                'position'       => 3,
                                                'etat'           => 0,
                                                'parrain_direct' => 'NON'
                                            ]);
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                            ]);
                                            break;
                                        case 3:
                                            $user = User::create(
                                                [
                                                    'identifiant' => strtolower($request->pseudo),
                                                    'password'    => Hash::make($request->password),
                                                    'status'      => $request->status,
                                                    'etat_id'     => $etat->id
                                                ]
                                            );
                                            $person = Personne::create(
                                                [
                                                    'nom_prenom'      => $request->name,
                                                    'code_parrainage' => $request->codePar,
                                                    'lien_parrainage' => $generateCodePar,
                                                    'adresse'         => $request->adresse,
                                                    'contact'         => $request->phone,
                                                    'date_naissance'  => $request->date,
                                                    'email'           => $request->email,
                                                    'sexe_id'         => $request->sexe,
                                                    'paiement_id'     => $valueId,
                                                    //Recuperation de l'id user
                                                    'user_id'         => $user->id
                                                ]
                                            );
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $mon_parrain_membre->personne_id,
                                                'position'       => 4,
                                                'etat'           => 0,
                                                'parrain_direct' => 'NON'
                                            ]);
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                            ]);
                                            break;
                                        case 4:
                                            $user = User::create(
                                                [
                                                    'identifiant' => strtolower($request->pseudo),
                                                    'password'    => Hash::make($request->password),
                                                    'status'      => $request->status,
                                                    'etat_id'     => $etat->id
                                                ]
                                            );
                                            $person = Personne::create(
                                                [
                                                    'nom_prenom'      => $request->name,
                                                    'code_parrainage' => $request->codePar,
                                                    'lien_parrainage' => $generateCodePar,
                                                    'adresse'         => $request->adresse,
                                                    'contact'         => $request->phone,
                                                    'date_naissance'  => $request->date,
                                                    'email'           => $request->email,
                                                    'sexe_id'         => $request->sexe,
                                                    'paiement_id'     => $valueId,
                                                    //Recuperation de l'id user
                                                    'user_id'         => $user->id
                                                ]
                                            );
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $mon_parrain_membre->personne_id,
                                                'position'       => 5,
                                                'etat'           => 0,
                                                'parrain_direct' => 'NON'
                                            ]);
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                            ]);
                                            break;
                                        case 5:
                                            $user = User::create(
                                                [
                                                    'identifiant' => strtolower($request->pseudo),
                                                    'password'    => Hash::make($request->password),
                                                    'status'      => $request->status,
                                                    'etat_id'     => $etat->id
                                                ]
                                            );
                                            $person = Personne::create(
                                                [
                                                    'nom_prenom'      => $request->name,
                                                    'code_parrainage' => $request->codePar,
                                                    'lien_parrainage' => $generateCodePar,
                                                    'adresse'         => $request->adresse,
                                                    'contact'         => $request->phone,
                                                    'date_naissance'  => $request->date,
                                                    'email'           => $request->email,
                                                    'sexe_id'         => $request->sexe,
                                                    'paiement_id'     => $valueId,
                                                    //Recuperation de l'id user
                                                    'user_id'         => $user->id
                                                ]
                                            );
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $mon_parrain_membre->personne_id,
                                                'position'       => 6,
                                                'etat'           => 0,
                                                'parrain_direct' => 'NON'
                                            ]);
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                            ]);
                                            break;
                                        case 6:
                                            $user = User::create(
                                                [
                                                    'identifiant' => strtolower($request->pseudo),
                                                    'password'    => Hash::make($request->password),
                                                    'status'      => $request->status,
                                                    'etat_id'     => $etat->id
                                                ]
                                            );
                                            $person = Personne::create(
                                                [
                                                    'nom_prenom'      => $request->name,
                                                    'code_parrainage' => $request->codePar,
                                                    'lien_parrainage' => $generateCodePar,
                                                    'adresse'         => $request->adresse,
                                                    'contact'         => $request->phone,
                                                    'date_naissance'  => $request->date,
                                                    'email'           => $request->email,
                                                    'sexe_id'         => $request->sexe,
                                                    'paiement_id'     => $valueId,
                                                    //Recuperation de l'id user
                                                    'user_id'         => $user->id
                                                ]
                                            );
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $mon_parrain_membre->personne_id,
                                                'position'       => 7,
                                                'etat'           => 0,
                                                'parrain_direct' => 'NON'
                                            ]);
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                            ]);
                                            break;

                                        case 7:
                                            $user = User::create(
                                                [
                                                    'identifiant' => strtolower($request->pseudo),
                                                    'password'    => Hash::make($request->password),
                                                    'status'      => $request->status,
                                                    'etat_id'     => $etat->id
                                                ]
                                            );
                                            $person = Personne::create(
                                                [
                                                    'nom_prenom'      => $request->name,
                                                    'code_parrainage' => $request->codePar,
                                                    'lien_parrainage' => $generateCodePar,
                                                    'adresse'         => $request->adresse,
                                                    'contact'         => $request->phone,
                                                    'date_naissance'  => $request->date,
                                                    'email'           => $request->email,
                                                    'sexe_id'         => $request->sexe,
                                                    'paiement_id'     => $valueId,
                                                    //Recuperation de l'id user
                                                    'user_id'         => $user->id
                                                ]
                                            );
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $mon_parrain_membre->personne_id,
                                                'position'       => 8,
                                                'etat'           => 0,
                                                'parrain_direct' => 'NON'
                                            ]);
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                            ]);
                                            break;
                                    }

                                    //Verification Fieuls Mon Parrain Niveau 3 plein
                                    if ($count_fieuls_parrain_indirect_l3_p1 === 8) {
                                        //Verification Fieuls Mon Parrain Niveau 4
                                        switch ($count_fieuls_parrain_indirect_l4_p1) {
                                            case 0:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 1,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 1:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 2,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 2:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'  => Str::random(10),
                                                    'phase_id'    => $phase1->id,
                                                    'level_id'    => $level4_p1->id,
                                                    'personne_id' => $person->id,
                                                    'parrain'     => $mon_parrain_membre->personne_id,
                                                    'position'    => 3,
                                                    'etat'        => 1,
                                                    'sponsor_link' => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 3:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 4,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 4:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 5,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 5:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 6,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                break;
                                            case 6:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 7,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 7:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 8,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 8:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 9,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 9:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 10,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 10:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 11,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 11:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 12,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 12:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 13,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 13:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 14,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 14:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 15,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 15:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 16,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                        }
                                    }
                                }
                                //Verification SI parrain indirect est le parrain supreme
                                else {
                                    //Verification Fieuls Mon parrain Niveau 3
                                    switch ($count_fieuls_parrain_indirect_l3_p1) {
                                        case 0:
                                            $user = User::create(
                                                [
                                                    'identifiant' => strtolower($request->pseudo),
                                                    'password'    => Hash::make($request->password),
                                                    'status'      => $request->status,
                                                    'etat_id'     => $etat->id
                                                ]
                                            );
                                            $person = Personne::create(
                                                [
                                                    'nom_prenom'      => $request->name,
                                                    'code_parrainage' => $request->codePar,
                                                    'lien_parrainage' => $generateCodePar,
                                                    'adresse'         => $request->adresse,
                                                    'contact'         => $request->phone,
                                                    'date_naissance'  => $request->date,
                                                    'email'           => $request->email,
                                                    'sexe_id'         => $request->sexe,
                                                    'paiement_id'     => $valueId,
                                                    //Recuperation de l'id user
                                                    'user_id'         => $user->id
                                                ]
                                            );
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $mon_parrain_membre->personne_id,
                                                'position'       => 1,
                                                'etat'           => 0,
                                                'parrain_direct' => 'NON'
                                            ]);
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                            ]);
                                            break;
                                        case 1:
                                            $user = User::create(
                                                [
                                                    'identifiant' => strtolower($request->pseudo),
                                                    'password'    => Hash::make($request->password),
                                                    'status'      => $request->status,
                                                    'etat_id'     => $etat->id
                                                ]
                                            );
                                            $person = Personne::create(
                                                [
                                                    'nom_prenom'      => $request->name,
                                                    'code_parrainage' => $request->codePar,
                                                    'lien_parrainage' => $generateCodePar,
                                                    'adresse'         => $request->adresse,
                                                    'contact'         => $request->phone,
                                                    'date_naissance'  => $request->date,
                                                    'email'           => $request->email,
                                                    'sexe_id'         => $request->sexe,
                                                    'paiement_id'     => $valueId,
                                                    //Recuperation de l'id user
                                                    'user_id'         => $user->id
                                                ]
                                            );
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'    => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $mon_parrain_membre->personne_id,
                                                'position'       => 2,
                                                'etat'           => 0,
                                                'parrain_direct' => 'NON'
                                            ]);
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                            ]);
                                            break;
                                        case 2:
                                            $user = User::create(
                                                [
                                                    'identifiant' => strtolower($request->pseudo),
                                                    'password'    => Hash::make($request->password),
                                                    'status'      => $request->status,
                                                    'etat_id'     => $etat->id
                                                ]
                                            );
                                            $person = Personne::create(
                                                [
                                                    'nom_prenom'      => $request->name,
                                                    'code_parrainage' => $request->codePar,
                                                    'lien_parrainage' => $generateCodePar,
                                                    'adresse'         => $request->adresse,
                                                    'contact'         => $request->phone,
                                                    'date_naissance'  => $request->date,
                                                    'email'           => $request->email,
                                                    'sexe_id'         => $request->sexe,
                                                    'paiement_id'     => $valueId,
                                                    //Recuperation de l'id user
                                                    'user_id'         => $user->id
                                                ]
                                            );
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $mon_parrain_membre->personne_id,
                                                'position'       => 3,
                                                'etat'           => 0,
                                                'parrain_direct' => 'NON'
                                            ]);
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                            ]);
                                            break;
                                        case 3:
                                            $user = User::create(
                                                [
                                                    'identifiant' => strtolower($request->pseudo),
                                                    'password'    => Hash::make($request->password),
                                                    'status'      => $request->status,
                                                    'etat_id'     => $etat->id
                                                ]
                                            );
                                            $person = Personne::create(
                                                [
                                                    'nom_prenom'      => $request->name,
                                                    'code_parrainage' => $request->codePar,
                                                    'lien_parrainage' => $generateCodePar,
                                                    'adresse'         => $request->adresse,
                                                    'contact'         => $request->phone,
                                                    'date_naissance'  => $request->date,
                                                    'email'           => $request->email,
                                                    'sexe_id'         => $request->sexe,
                                                    'paiement_id'     => $valueId,
                                                    //Recuperation de l'id user
                                                    'user_id'         => $user->id
                                                ]
                                            );
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $mon_parrain_membre->personne_id,
                                                'position'       => 4,
                                                'etat'           => 0,
                                                'parrain_direct' => 'NON'
                                            ]);
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                            ]);
                                            break;
                                        case 4:
                                            $user = User::create(
                                                [
                                                    'identifiant' => strtolower($request->pseudo),
                                                    'password'    => Hash::make($request->password),
                                                    'status'      => $request->status,
                                                    'etat_id'     => $etat->id
                                                ]
                                            );
                                            $person = Personne::create(
                                                [
                                                    'nom_prenom'      => $request->name,
                                                    'code_parrainage' => $request->codePar,
                                                    'lien_parrainage' => $generateCodePar,
                                                    'adresse'         => $request->adresse,
                                                    'contact'         => $request->phone,
                                                    'date_naissance'  => $request->date,
                                                    'email'           => $request->email,
                                                    'sexe_id'         => $request->sexe,
                                                    'paiement_id'     => $valueId,
                                                    //Recuperation de l'id user
                                                    'user_id'         => $user->id
                                                ]
                                            );
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $mon_parrain_membre->personne_id,
                                                'position'       => 5,
                                                'etat'           => 0,
                                                'parrain_direct' => 'NON'
                                            ]);
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                            ]);
                                            break;
                                        case 5:
                                            $user = User::create(
                                                [
                                                    'identifiant' => strtolower($request->pseudo),
                                                    'password'    => Hash::make($request->password),
                                                    'status'      => $request->status,
                                                    'etat_id'     => $etat->id
                                                ]
                                            );
                                            $person = Personne::create(
                                                [
                                                    'nom_prenom'      => $request->name,
                                                    'code_parrainage' => $request->codePar,
                                                    'lien_parrainage' => $generateCodePar,
                                                    'adresse'         => $request->adresse,
                                                    'contact'         => $request->phone,
                                                    'date_naissance'  => $request->date,
                                                    'email'           => $request->email,
                                                    'sexe_id'         => $request->sexe,
                                                    'paiement_id'     => $valueId,
                                                    //Recuperation de l'id user
                                                    'user_id'         => $user->id
                                                ]
                                            );
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $mon_parrain_membre->personne_id,
                                                'position'       => 6,
                                                'etat'           => 0,
                                                'parrain_direct' => 'NON'
                                            ]);
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                            ]);
                                            break;
                                        case 6:
                                            $user = User::create(
                                                [
                                                    'identifiant' => strtolower($request->pseudo),
                                                    'password'    => Hash::make($request->password),
                                                    'status'      => $request->status,
                                                    'etat_id'     => $etat->id
                                                ]
                                            );
                                            $person = Personne::create(
                                                [
                                                    'nom_prenom'      => $request->name,
                                                    'code_parrainage' => $request->codePar,
                                                    'lien_parrainage' => $generateCodePar,
                                                    'adresse'         => $request->adresse,
                                                    'contact'         => $request->phone,
                                                    'date_naissance'  => $request->date,
                                                    'email'           => $request->email,
                                                    'sexe_id'         => $request->sexe,
                                                    'paiement_id'     => $valueId,
                                                    //Recuperation de l'id user
                                                    'user_id'         => $user->id
                                                ]
                                            );
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $mon_parrain_membre->personne_id,
                                                'position'       => 7,
                                                'etat'           => 0,
                                                'parrain_direct' => 'NON'
                                            ]);
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                            ]);
                                            break;

                                        case 7:
                                            $user = User::create(
                                                [
                                                    'identifiant' => strtolower($request->pseudo),
                                                    'password'    => Hash::make($request->password),
                                                    'status'      => $request->status,
                                                    'etat_id'     => $etat->id
                                                ]
                                            );
                                            $person = Personne::create(
                                                [
                                                    'nom_prenom'      => $request->name,
                                                    'code_parrainage' => $request->codePar,
                                                    'lien_parrainage' => $generateCodePar,
                                                    'adresse'         => $request->adresse,
                                                    'contact'         => $request->phone,
                                                    'date_naissance'  => $request->date,
                                                    'email'           => $request->email,
                                                    'sexe_id'         => $request->sexe,
                                                    'paiement_id'     => $valueId,
                                                    //Recuperation de l'id user
                                                    'user_id'         => $user->id
                                                ]
                                            );
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $mon_parrain_membre->personne_id,
                                                'position'       => 8,
                                                'etat'           => 0,
                                                'parrain_direct' => 'NON'
                                            ]);
                                            $montant_parrain_update = $parrain_montant_exists->update([
                                                'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                                'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                            ]);
                                            break;
                                    }

                                    //Verification Fieuls Mon Parrain Niveau 3 plein
                                    if ($count_fieuls_parrain_indirect_l3_p1 === 8) {
                                        //Verification Fieuls Mon Parrain Niveau 4
                                        switch ($count_fieuls_parrain_indirect_l4_p1) {
                                            case 0:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 1,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 1:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 2,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 2:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'  => Str::random(10),
                                                    'phase_id'    => $phase1->id,
                                                    'level_id'    => $level4_p1->id,
                                                    'personne_id' => $person->id,
                                                    'parrain'     => $mon_parrain_membre->personne_id,
                                                    'position'    => 3,
                                                    'etat'        => 1,
                                                    'sponsor_link' => $value->sponsor_link,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 3:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 4,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 4:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 5,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 5:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 6,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                break;
                                            case 6:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 7,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 7:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 8,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 8:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 9,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 9:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 10,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 10:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 11,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 11:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 12,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 12:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 13,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 13:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 14,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 14:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 15,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 15:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 16,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                        }
                                    }
                                }
                            }

                            #Parrain Simple Niveau 2 Plein
                            if ($count_fieuls_l2_p1 === 4) {
                                #Niveau 3
                                switch ($count_fieuls_l3_p1) {
                                    case 0:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );

                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level2_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 1,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link,
                                        ]);
                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        $montant_parrain_update = $parrain_montant_exists->update([
                                            'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                            'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                        ]);
                                        break;
                                    case 1:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level2_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 2,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link,
                                        ]);
                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        $montant_parrain_update = $parrain_montant_exists->update([
                                            'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                            'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                        ]);
                                        break;
                                    case 2:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level2_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 3,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link,
                                        ]);
                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        $montant_parrain_update = $parrain_montant_exists->update([
                                            'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                            'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                        ]);
                                        break;
                                    case 3:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level2_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 4,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link,
                                        ]);
                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        $montant_parrain_update = $parrain_montant_exists->update([
                                            'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                            'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                        ]);
                                        break;
                                    case 4:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level2_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 5,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link,
                                        ]);
                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        $montant_parrain_update = $parrain_montant_exists->update([
                                            'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                            'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                        ]);
                                        break;
                                    case 5:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level2_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 6,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link,
                                        ]);
                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        $montant_parrain_update = $parrain_montant_exists->update([
                                            'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                            'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                        ]);
                                        break;
                                    case 6:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level2_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 7,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link,
                                        ]);
                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        $montant_parrain_update = $parrain_montant_exists->update([
                                            'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                            'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                        ]);
                                        break;
                                    case 7:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level2_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 8,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link,
                                        ]);
                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        $montant_parrain_update = $parrain_montant_exists->update([
                                            'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                            'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                        ]);
                                        break;
                                }
                                if ($montant_parrain_sup->personne_id != $mon_parrain_membre->personne_id) {
                                    //Save fieul Niveau 4 Parrain Supreme
                                    switch ($count_fieuls_parrain_sup_l4_p1) {
                                        case 0:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $parrain_sup->personne_id,
                                                'position'       => 1,
                                                'etat'           => 0,
                                                'sponsor_link'   => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //MAJ montant parrain supreme
                                            $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                            ]);
                                            break;
                                        case 1:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $parrain_sup->personne_id,
                                                'position'       => 2,
                                                'etat'           => 0,
                                                'sponsor_link'   => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //MAJ montant parrain supreme
                                            $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                            ]);
                                            break;
                                        case 2:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $parrain_sup->personne_id,
                                                'position'       => 3,
                                                'etat'           => 0,
                                                'sponsor_link'   => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //MAJ montant parrain supreme
                                            $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                            ]);
                                            break;
                                        case 3:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $parrain_sup->personne_id,
                                                'position'       => 4,
                                                'etat'           => 0,
                                                'sponsor_link'   => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //MAJ montant parrain supreme
                                            $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                            ]);
                                            break;
                                        case 4:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $parrain_sup->personne_id,
                                                'position'       => 5,
                                                'etat'           => 0,
                                                'sponsor_link'   => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //MAJ montant parrain supreme
                                            $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                            ]);
                                            break;
                                        case 5:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $parrain_sup->personne_id,
                                                'position'       => 6,
                                                'etat'           => 0,
                                                'sponsor_link'   => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //MAJ montant parrain supreme
                                            $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                            ]);
                                            break;
                                        case 6:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $parrain_sup->personne_id,
                                                'position'       => 7,
                                                'etat'           => 0,
                                                'sponsor_link'   => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //MAJ montant parrain supreme
                                            $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                            ]);
                                            break;
                                        case 7:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $parrain_sup->personne_id,
                                                'position'       => 8,
                                                'etat'           => 0,
                                                'sponsor_link'   => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //MAJ montant parrain supreme
                                            $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                            ]);
                                            break;
                                        case 8:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $parrain_sup->personne_id,
                                                'position'       => 9,
                                                'etat'           => 0,
                                                'sponsor_link'   => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //MAJ montant parrain supreme
                                            $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                            ]);
                                            break;

                                        case 9:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $parrain_sup->personne_id,
                                                'position'       => 10,
                                                'etat'           => 0,
                                                'sponsor_link'   => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //MAJ montant parrain supreme
                                            $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                            ]);
                                            break;
                                        case 10:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $parrain_sup->personne_id,
                                                'position'       => 11,
                                                'etat'           => 0,
                                                'sponsor_link'   => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //MAJ montant parrain supreme
                                            $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                            ]);
                                            break;
                                        case 11:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $parrain_sup->personne_id,
                                                'position'       => 12,
                                                'etat'           => 0,
                                                'sponsor_link'   => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //MAJ montant parrain supreme
                                            $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                            ]);
                                            break;
                                        case 12:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $parrain_sup->personne_id,
                                                'position'       => 13,
                                                'etat'           => 0,
                                                'sponsor_link'   => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //MAJ montant parrain supreme
                                            $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                            ]);
                                            break;
                                        case 13:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $parrain_sup->personne_id,
                                                'position'       => 14,
                                                'etat'           => 0,
                                                'sponsor_link'   => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //MAJ montant parrain supreme
                                            $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                            ]);
                                            break;
                                        case 14:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $parrain_sup->personne_id,
                                                'position'       => 15,
                                                'etat'           => 0,
                                                'sponsor_link'   => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //MAJ montant parrain supreme
                                            $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                            ]);
                                            break;
                                        case 15:
                                            $membre = Membre::firstOrCreate([
                                                'ref_membre'     => Str::random(10),
                                                'phase_id'       => $phase1->id,
                                                'level_id'       => $level2_p1->id,
                                                'personne_id'    => $person->id,
                                                'parrain'        => $parrain_sup->personne_id,
                                                'position'       => 16,
                                                'etat'           => 0,
                                                'sponsor_link'   => $value->sponsor_link,
                                                'parrain_direct' => 'NON'
                                            ]);

                                            //MAJ montant parrain supreme
                                            $update_mt_parrain_sup = $montant_parrain_sup->update([
                                                'gain_niv4' => $montant_parrain_sup->gain_niv4 + 1250
                                            ]);
                                            break;
                                    }
                                    //Niveau 4 plein Parrain Supreme
                                    if ($count_fieuls_parrain_sup_l4_p1) {
                                        session()->flash('message', 'Maximum de fieuls atteints!');
                                        return back();
                                    }


                                    //Verification Fieuls Mon Parrain Niveau 3 plein
                                    if ($count_fieuls_parrain_indirect_l3_p1 === 8) {
                                        //Verification Fieuls Mon Parrain Niveau 4
                                        switch ($count_fieuls_parrain_indirect_l4_p1) {
                                            case 0:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 1,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 1:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 2,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 2:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 3,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;

                                            case 3:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 4,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 4:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 5,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 5:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 6,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 6:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 7,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 7:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 8,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 8:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'         => Str::random(10),
                                                    'phase_id'           => $phase1->id,
                                                    'level_id'           => $level4_p1->id,
                                                    'personne_id'        => $person->id,
                                                    'parrain'            => $mon_parrain_membre->personne_id,
                                                    'position'           => 9,
                                                    'etat'               => 0,
                                                    'parrain_direct'     => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 9:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 10,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 10:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 11,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 11:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 12,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 12:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 13,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 13:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 14,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 14:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 15,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 15:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 16,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                        }
                                    }
                                }
                                //Verification SI parrain indirect est le parrain supreme
                                else {
                                    //Verification Fieuls Mon Parrain Niveau 3 plein
                                    if ($count_fieuls_parrain_indirect_l3_p1 === 8) {
                                        //Verification Fieuls Mon Parrain Niveau 4
                                        switch ($count_fieuls_parrain_indirect_l4_p1) {
                                            case 0:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 1,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 1:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 2,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 2:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 3,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;

                                            case 3:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 4,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 4:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 5,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 5:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 6,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 6:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 7,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 7:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 8,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 8:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'         => Str::random(10),
                                                    'phase_id'           => $phase1->id,
                                                    'level_id'           => $level4_p1->id,
                                                    'personne_id'        => $person->id,
                                                    'parrain'            => $mon_parrain_membre->personne_id,
                                                    'position'           => 9,
                                                    'etat'               => 0,
                                                    'parrain_direct'     => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 9:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 10,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 10:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 11,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 11:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 12,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 12:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 13,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 13:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 14,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 14:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 15,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                            case 15:
                                                $membre = Membre::firstOrCreate([
                                                    'ref_membre'     => Str::random(10),
                                                    'phase_id'       => $phase1->id,
                                                    'level_id'       => $level4_p1->id,
                                                    'personne_id'    => $person->id,
                                                    'parrain'        => $mon_parrain_membre->personne_id,
                                                    'position'       => 16,
                                                    'etat'           => 0,
                                                    'parrain_direct' => 'NON'
                                                ]);
                                                //Mise à jour du montant du parrain indirect
                                                $montant_parrain_update = $montant_mon_parrain->update([
                                                    'gain_niv4' => $montant_mon_parrain->gain_niv4 + 1250
                                                ]);
                                                break;
                                        }
                                    }
                                }
                            }

                            #Parrain Simple - Niveau 3 Plein
                            if ($count_fieuls_l3_p1 === 8) {
                                #Niveau 4
                                switch ($count_fieuls_l4_p1) {
                                    case 0:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level4_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 1,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link,
                                        ]);

                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        $montant_parrain_update = $parrain_montant_exists->update([
                                            'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                            'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                        ]);
                                        break;
                                    case 1:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level4_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 2,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link,
                                        ]);

                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        $montant_parrain_update = $parrain_montant_exists->update([
                                            'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                            'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                        ]);
                                        break;
                                    case 2:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level4_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 3,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link,
                                        ]);

                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        $montant_parrain_update = $parrain_montant_exists->update([
                                            'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                            'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                        ]);
                                        break;
                                    case 3:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level4_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 4,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link,
                                        ]);

                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        $montant_parrain_update = $parrain_montant_exists->update([
                                            'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                            'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                        ]);
                                        break;
                                    case 4:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level4_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 5,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link,
                                        ]);

                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        $montant_parrain_update = $parrain_montant_exists->update([
                                            'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                            'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                        ]);
                                        break;
                                    case 5:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level4_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 6,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link,
                                        ]);

                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        $montant_parrain_update = $parrain_montant_exists->update([
                                            'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                            'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                        ]);
                                        break;
                                    case 6:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level4_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 7,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link,
                                        ]);

                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        $montant_parrain_update = $parrain_montant_exists->update([
                                            'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                            'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                        ]);
                                        break;
                                    case 7:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level4_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 8,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link,
                                        ]);

                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        $montant_parrain_update = $parrain_montant_exists->update([
                                            'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                            'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                        ]);
                                        break;
                                    case 8:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level4_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 9,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link,
                                        ]);

                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        $montant_parrain_update = $parrain_montant_exists->update([
                                            'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                            'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                        ]);
                                        break;
                                    case 9:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level4_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 10,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link,
                                        ]);

                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        $montant_parrain_update = $parrain_montant_exists->update([
                                            'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                            'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                        ]);
                                        break;
                                    case 10:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level4_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 11,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link,
                                        ]);

                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        $montant_parrain_update = $parrain_montant_exists->update([
                                            'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                            'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                        ]);
                                        break;
                                    case 11:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level4_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 12,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link,
                                        ]);

                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        $montant_parrain_update = $parrain_montant_exists->update([
                                            'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                            'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                        ]);
                                        break;
                                    case 12:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level4_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 13,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link,
                                        ]);

                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        $montant_parrain_update = $parrain_montant_exists->update([
                                            'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                            'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                        ]);
                                        break;
                                    case 13:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level4_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 14,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link,
                                        ]);

                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        $montant_parrain_update = $parrain_montant_exists->update([
                                            'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                            'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                        ]);
                                        break;
                                    case 14:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level4_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 15,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link,
                                        ]);

                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        $montant_parrain_update = $parrain_montant_exists->update([
                                            'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                            'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                        ]);
                                        break;

                                    case 15:
                                        $user = User::create(
                                            [
                                                'identifiant' => strtolower($request->pseudo),
                                                'password'    => Hash::make($request->password),
                                                'status'      => $request->status,
                                                'etat_id'     => $etat->id
                                            ]
                                        );
                                        $person = Personne::create(
                                            [
                                                'nom_prenom'      => $request->name,
                                                'code_parrainage' => $request->codePar,
                                                'lien_parrainage' => $generateCodePar,
                                                'adresse'         => $request->adresse,
                                                'contact'         => $request->phone,
                                                'date_naissance'  => $request->date,
                                                'email'           => $request->email,
                                                'sexe_id'         => $request->sexe,
                                                'paiement_id'     => $valueId,
                                                //Recuperation de l'id user
                                                'user_id'         => $user->id
                                            ]
                                        );
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'  => Str::random(10),
                                            'phase_id'    => $phase1->id,
                                            'level_id'    => $level4_p1->id,
                                            'personne_id' => $person->id,
                                            'parrain'     => $person_parrain->id,
                                            'position'    => 16,
                                            'etat'        => 1,
                                            'sponsor_link' => $value->sponsor_link,
                                        ]);

                                        $montant = Montant::firstOrCreate([
                                            'phase_id'        => $phase1->id,
                                            'personne_id'     => $person->id,
                                            'gain_parrainage' => 0,
                                            'gain_niv1'       => 0,
                                            'gain_niv2'       => 0,
                                            'gain_niv3'       => 0,
                                            'gain_niv4'       => 0,
                                            'sponsor_link'    => $membre->sponsor_link
                                        ]);
                                        $montant_parrain_update = $parrain_montant_exists->update([
                                            'gain_parrainage' => $parrain_montant_exists->gain_parrainage + 300,
                                            'gain_niv3'       => $parrain_montant_exists->gain_niv3 + 400
                                        ]);

                                        //Inscription vers phase II
                                        $membre = Membre::firstOrCreate([
                                            'ref_membre'   => Str::random(10),
                                            'phase_id'     => $phase2->id,
                                            'level_id'     => $level1_p2->id,
                                            'personne_id'  => $value->personne_id,
                                            'parrain'      => $value->parrain,
                                            'position'     => $value->position,
                                            'etat'         => 2,
                                            'sponsor_link' => 'SUP' . strtoupper(Str::random(30))
                                        ]);

                                        //Changement etat dans la table membre à 0
                                        $etat_p1_parrain_simple = Membre::where('personne_id', $value->personne_id)->where('parrain', $value->parrain)->first();
                                        $etat_update_to_p2 = $etat_p1_parrain_simple->update([
                                            'etat' => 2
                                        ]);
                                        break;
                                }

                                //Enregistrement a faire aun niveau suivant du parrain supreme
                                if ($count_fieuls_l3_p1 === 16) {
                                    session()->flash('message', 'Maximum de parrainage atteint');
                                    return back();
                                }
                            }
                        }
                    }
                }
            } else {
                session()->flash('message1', 'Code de parrainage non attribuer, veuillez recontacter votre parrain!');
                return back();
            }
        } else if ($exist_code_payment === false) {
            session()->flash('message1', "Ce code de paiement n'existe pas!");
            return back();
        } else if ($verif_code_pay_status->status === 1) {
            session()->flash('message1', 'Code paiement déjà utilisé, veuillez contactez le service clientèle!');
            return back();
        } else {
            session()->flash('message1', 'Code paiement invalide, veuillez réessayer!');
            return back();
        }
    }

    //Fonction page Connexion
    public function loginForm()
    {
        return view('layout.auth.login');
    }

    //Fonction action connexion
    public function loginAction(Request $request)
    {
        $data = $request->validate([
            'identifiant' => 'required|string',
            'password'    => 'required|min:8'
        ]);

        $try_connexion = Auth::attempt(['identifiant' => $data['identifiant'], 'password' => $data['password']]);
        if ($try_connexion) {
            session()->flash('message', 'Bienvenu(e)');
            return redirect()->route('dashboard');
        }
        //session()->flash('message', 'Identifiant ou mot de passe incorrect');
        session()->flash('message', 'Identifiant ou mot de passe incorrect!');
        return back();
    }

    //formulaire mot de passe oublié
    public function forgetPwdForm()
    {
        return view('layout.auth.forget_password');
    }

    //action mot de passe oublié
    public function forgetPwdAction(Request $request)
    {
        #1. Validation de la requête
        // $this->validate($request, ['message' => 'bail|required']);
        $request->validate(['email' => 'required']);

        $request->validate(
            [
                'email' => 'required|email',
            ],
            [
                'email.required' => 'Votre mail est obligatoire',
            ]
        );
        #2. Récupération des utilisateurs
        // $users = User::all();

        //Recupération du proprietaire du mail dans la table personnes


        $personne = Personne::where('email', request('email'))->first();

        //Recuperation de l'utilisateur dans la table Users
        $user = User::where('id', $personne->user_id)->first();


        //Mise à jour d'un mot de passe temporaire dans la table Users
        $user->update([
            'password' => Crypt::encrypt("none"),
        ]);

        #3. Envoi du mail
        Mail::to($personne)->bcc("philippesf3@gmail.com")
            ->queue(new MessageGoogle($personne));
        session()->flash('message', 'Un mail vous a été envoyé');

        return back()->withText("Message envoyé");
    }

    //action mise à jour de l'utilisateur
    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);

        $data = $request->validate(
            [
                'identifiant'           => 'required',
                'password'              => 'required|min:8',
                'password_confirmation' => 'required|min:8|same:password'
            ],
            [
                'password.required'     => 'Caractères minimum requis 8',
                'passwordConf.required' => 'Les mots de passe ne correspondent pas, veuiller réessayer!'
            ]
        );

        $update_user = $user->update([
            'identifiant' => $data['identifiant'],
            'password'    => Hash::make($data['password']),
        ]);

        if ($update_user) {
            session()->flash('message', 'Compte mise à jour');
            return back();
        } else {
            session()->flash('message', 'Echec de la mise à jour');
            return back();
        }
    }

    public function logout()
    {
        Auth::logout();

        session()->flash('text', 'Déconnexion');

        return redirect()->route('login_form');
    }
}
