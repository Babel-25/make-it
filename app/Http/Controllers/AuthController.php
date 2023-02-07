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

                //Mise à jour de l'etat du code de paiement
                $maj_paiement = $verif_code_pay_status->update([
                    'status' => 1
                ]);

                //Insertion dans la table Montants
                $montant = Montant::create(
                    [
                        'montant_parrain' => 0,
                        'montant_net'     => 0,
                        'montant_total'   => 0,
                        'personne_id'     => $person->id
                    ]
                );


                //Phase A
                $phase1 = Phase::where('libelle_phase', 'Phase A')->first();

                //Niveau 0
                $level0_p1 = Level::where('phase_id', $phase1->id)->where('libelle_niveau', 'Niveau 0')->first();

                //Niveau 1
                $level1_p1 = Level::where('phase_id', $phase1->id)->where('libelle_niveau', 'Niveau 1')->first();

                //Niveau 2
                $level2_p1 = Level::where('phase_id', $phase1->id)->where('libelle_niveau', 'Niveau 2')->first();

                //Niveau 3
                $level3_p1 = Level::where('phase_id', $phase1->id)->where('libelle_niveau', 'Niveau 3')->first();

                //Niveau 4
                $level4_p1 = Level::where('phase_id', $phase1->id)->where('libelle_niveau', 'Niveau 4')->first();

                //Phase B
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


                //Information parrain table Personne
                $pers_parrain = Personne::where('lien_parrainage',$request->codePar)->first();

                //Information parrain table Membre
                $membre_parrain = Membre::where('parrain',$pers_parrain)->first();

                //Nombre Fieul parrain au niveau 1 de la phase A
                $count_fieul_p1_l1 = Membre::where('phase_id', $phase1->id)
                    ->where('level_id', $level1_p1->id)->where('parrain', $pers_parrain->id)->count();

                //Nombre Fieul parrain au niveau 2 de la phase A
                $count_fieul_p1_l2 = Membre::where('phase_id', $phase1->id)
                    ->where('level_id', $level2_p1->id)->where('parrain', $pers_parrain->id)->count();

                //Nombre Fieul parrain au niveau 3 de la phase A
                $count_fieul_p1_l3 = Membre::where('phase_id', $phase1->id)
                    ->where('level_id', $level3_p1->id)->where('parrain', $pers_parrain->id)->count();

                //Nombre Fieul parrain au niveau 4 de la phase A
                $count_fieul_p1_l4 = Membre::where('phase_id', $phase1->id)
                    ->where('level_id', $level4_p1->id)->where('parrain', $pers_parrain->id)->count();


                //Nombre Fieul parrain au niveau 1 de la phase B
                $count_fieul_p2_l1 = Membre::where('phase_id', $phase2->id)
                    ->where('level_id', $level1_p1->id)->where('parrain', $pers_parrain->id)->count();

                //Nombre Fieul parrain au niveau 2 de la phase B
                $count_fieul_p2_l2 = Membre::where('phase_id', $phase2->id)
                    ->where('level_id', $level2_p1->id)->where('parrain', $pers_parrain->id)->count();

                //Nombre Fieul parrain au niveau 3 de la phase B
                $count_fieul_p2_l3 = Membre::where('phase_id', $phase2->id)
                    ->where('level_id', $level3_p1->id)->where('parrain', $pers_parrain->id)->count();

                //Nombre Fieul parrain au niveau 4 de la phase B
                $count_fieul_p2_l4 = Membre::where('phase_id', $phase2->id)
                    ->where('level_id', $level4_p1->id)->where('parrain', $pers_parrain->id)->count();


                //*********DEBUT CONDITION SUR LE parrain */
                //Verification phase 1
                if ($phase1->id === $membre_parrain->phase_id) {
                    //Preuve fieul du parrain
                    if ($person->code_parrainage === $pers_parrain->lien_parrainage) {
                        //Si nombre fieul est de 0 ou <=2
                        if ($count_fieul_p1_l1 === 0 || $count_fieul_p1_l1 < 2) {
                            $membre_1 = Membre::firstOrCreate([
                                'ref_membre'  => Str::random(10),
                                'phase_id'    => $phase1->id,
                                'level_id'    => $level1_p1->id,
                                'personne_id' => $person->id,
                                'parrain'     => $pers_parrain->id,
                            ]);
                        }
                        if ($count_fieul_p1_l1 === 2) {
                            if ($count_fieul_p1_l2 === 0 || $count_fieul_p1_l2 <= 4) {
                                $membre_2 = Membre::firstOrCreate([
                                    'ref_membre'  => Str::random(10),
                                    'phase_id'    => $phase1->id,
                                    'level_id'    => $level2_p1->id,
                                    'personne_id' => $person->id,
                                    'parrain'     => $pers_parrain->id,
                                ]);
                            }
                        }

                        if ($count_fieul_p1_l2 === 4) {
                            if ($count_fieul_p1_l3 === 0 || $count_fieul_p1_l3 <= 8) {
                                $membre_3 = Membre::firstOrCreate([
                                    'ref_membre'  => Str::random(10),
                                    'phase_id'    => $phase1->id,
                                    'level_id'    => $level3_p1->id,
                                    'personne_id' => $person->id,
                                    'parrain'     => $pers_parrain->id,
                                ]);
                            }
                        }
                        if ($count_fieul_p1_l3 === 8) {
                            if ($count_fieul_p1_l4 === 0 || $count_fieul_p1_l4 <= 16) {
                                $membre_4 = Membre::firstOrCreate([
                                    'ref_membre'  => Str::random(10),
                                    'phase_id'    => $phase1->id,
                                    'level_id'    => $level4_p1->id,
                                    'personne_id' => $person->id,
                                    'parrain'     => $pers_parrain->id,
                                ]);
                            }
                        }

                        if ($count_fieul_p1_l4 === 16) {
                            if ($count_fieul_p1_l4 === 0 || $count_fieul_p1_l4 <= 16) {
                                $membre = Membre::firstOrCreate([
                                    'ref_membre'  => Str::random(10),
                                    'phase_id'    => $phase2->id,
                                    'level_id'    => $level2_p1->id,
                                    'personne_id' => $person->id,
                                    'parrain'     => $pers_parrain->id,
                                ]);
                            }
                        }
                    }
                }
                //*********FIN CONDITION SUR LE PARRAIN */


                if ($person and $user and $montant) {
                    session()->flash('message', 'Inscription Réussie!');
                    return back();
                } else {
                    session()->flash('message1', 'Inscription échouée, veuillez réessayer!');
                    return back();
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
