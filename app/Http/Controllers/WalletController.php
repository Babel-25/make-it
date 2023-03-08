<?php

namespace App\Http\Controllers;

use App\Models\Montant;
use App\Models\User;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function walletForm()
    {
        $user = User::find(auth()->user()->id)->personne;
        $montant_user = Montant::where('personne_id', $user->id)->first();
        $montant_parrainage =  $montant_user->gain_parrainage;
        $montant_phase1 =  $montant_user->gain_niv1 +  $montant_user->gain_niv2 +
            $montant_user->gain_niv3 +  $montant_user->gain_niv4;
        $montant_net = $montant_parrainage + $montant_phase1;
        return view('layout.user.wallets.portefeuille', compact('montant_user', 'montant_parrainage', 'montant_phase1', 'montant_net'));
    }
}
