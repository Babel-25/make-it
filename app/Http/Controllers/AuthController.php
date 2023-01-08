<?php

namespace App\Http\Controllers;

use App\Mail\MessageGoogle;
use App\Models\Paiement;
use App\Models\Personne;
use App\Models\User;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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
            'field'  => 'code_parrainage',
            'length' => 10,
            'prefix' => 'MAK-'
        ]);

        //Vérification de l'existence du code de paiement
        $verif = Paiement::where('code_paiement', $request->codePai)->get();
        $recupInfo = json_decode($verif, true);

        if ($recupInfo != null && $recupInfo != 'undefined' && $recupInfo[0] != 0) {
            $valueId = $recupInfo[0]['id'];

            // Insertion dans la table users
            $user = User::create(
                [
                    'identifiant' => strtoupper($request->pseudo),
                    'password'    => Hash::make($request->password),
                    'status'      => $request->status,
                    'etat_id'     => $request->etat,
                ]
            );

            // Insertion dans la table Personnes
            $person = Personne::create(
                [
                    'nom_prenom'      => strtoupper($request->name),
                    'code_parrainage' => $generateCodePar,
                    'adresse'         => $request->adresse,
                    'contact'         => $request->phone,
                    'date_naissance'  => $request->date,
                    'email'           => $request->email,
                    'sexe_id'         => $request->sexe,
                    'user_id'         => $user->id,
                    'paiement_id'     => $valueId,
                    //Recuperation de l'id user
                    'user_id'         => $user->id
                ]
            );

            if ($person and $user) {
                session()->flash('message', 'Inscription Réussie!');
                return back();
            } else {
                session()->flash('message', 'Inscription échouée, veuiller réessayer!');
                return back();
            }
        } else {
            session()->flash('message', 'Le code paiement est invalide, veuillez réessayer!');
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
        session()->flash('message', 'Identifiant ou mot de passe incorrect');
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
