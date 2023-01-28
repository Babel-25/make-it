<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function walletForm()
    {
        return view('layout.user.wallets.portefeuille');
    }

}
