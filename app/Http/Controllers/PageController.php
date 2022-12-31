<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function accueil()
    {
        return view('screens.acceuil');
    }

    //fonction page vitrine
    public function vitrine()
    {
        return view('screens.vitrine');
    }

    //Page index utilisateur
    public function indexUser()
    {
        return view('screens.user.index');
    }

    //fonction page mon reseau
    public function dashboard()
    {
        return view('screens.user.dashboard');
    }
}
