<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    //Premiere fonction page d'inscription
    public function registerFormOne()
    {
        return view('auth.inscrit1');
    }

    //Deuxieme fonction page d'inscription
    public function registerFormTwo()
    {
        return view('auth.inscrit2');
    }

    public function registerAction(Request $request)
    {
        //
    }

    //Fonction page Connexion
    public function loginForm()
    {
        return view('auth.login');
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
            return redirect()->route('dashboard');
        }
        dd('Identifiant ou mot de passe incorrect');
        // return response()->json([
        //     'message' => 'Identifiant ou mot de passe incorrect',
        // ], 403);
    }

    public function forgetPwdForm()
    {
        return view('auth.forget_password');
    }

    public function forgetPwdAction()
    {
        //
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login_form');
    }
}
