@extends('layout.auth.index')
@section('content')
@section('title','Inscription')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

<link rel="stylesheet" href="{{ asset('Auth/build/css/intlTelInput.css') }}">
<link rel="stylesheet" href="{{ asset('Auth/build/css/demo.css') }}">

<div class="container">
    <div class="forms-container">
        <div class="signin-signup">
            <div class="row justify-content-md-center">
                <div class="col-md-12 ">
                    <div class=" px-5 py-3 mt-5 ">
                        <h1 class="text-center mt-3 mb-4">Inscription</h1>
                        <div>
                            @if (session()->exists('message'))
                            <div class="alert alert-success" id="alert" align="center">
                                {{ session('message') }}
                            </div>
                            @endif
                        </div>
                        <div>
                            @if (session()->exists('message1'))
                            <div class="alert alert-danger" id="alert" align="center">
                                {{ session('message1') }}
                            </div>
                            @endif
                        </div>
                        <div class="nav nav-fill my-3">
                            <label class="nav-link shadow-sm step0 border ml-2 ">Première étape </label>
                            <label class="nav-link shadow-sm step1 border ml-2 ">Seconde étape</label>
                            <label class="nav-link shadow-sm step2 border ml-2 ">Dernière étape</label>
                        </div>

                        <form action="{{ route('register_action') }}" method="POST" class="sign-in-form">
                            @csrf
                            <div class="form-section">
                                <label for="">Code paiement:</label>
                                <input type="text" class="form-control mb-3" value="{{ old('codePai') }}" name="codePai">
                                <span style="color:red">
                                    @error('codePai')
                                    <div class="text-danger"> {{ $message }} </div>
                                    @enderror
                                </span>
                                <label for="">Code parrainage:</label>
                                <input type="text" class="form-control mb-3" value="{{ old('codePar') }}" name="codePar">
                                <span style="color:red">
                                    @error('codePar')
                                    <div class="text-danger"> {{ $message }} </div>
                                    @enderror
                                </span>
                                <label for="">Nom et Prénom(s):</label>
                                <input type="text" class="form-control mb-3" value="{{ old('name') }}" name="name">
                                <span style="color:red">
                                    @error('name')
                                    <div class="text-danger"> {{ $message }} </div>
                                    @enderror
                                </span>
                                <label for="">Adresse:</label>
                                <input type="text" class="form-control mb-3" value="{{ old('adresse') }}" name="adresse">
                                <span style="color:red">
                                    @error('adresse')
                                    <div class="text-danger"> {{ $message }} </div>
                                    @enderror
                                </span>

                            </div>
                            <div class="form-section">
                                <label for="">Sexe:</label>
                                <select name="sexe" class="form-control mb-3">
                                    @php
                                    $gendre = \App\Models\Sexe::all();
                                    @endphp
                                    <option> Selectionnez---</option>
                                    @foreach ($gendre as $sexe)
                                    <option value=" {{ $sexe->id }} ">{{ $sexe->libelle }}</option>
                                    @endforeach
                                </select>
                                <span style="color:red">
                                    @error('sexe')
                                    <div class="text-danger"> {{ $message }} </div>
                                    @enderror
                                </span>

                                <label for="">Date de naissance:</label>
                                <input type="date" class="form-control mb-3" id="dateValidate" value="{{ old('date') }}" name="date">
                                <span style="color:red">
                                    @error('date')
                                    <div class="text-danger"> {{ $message }} </div>
                                    @enderror
                                </span>
                                <label for="">E-mail:</label>
                                <input type="email" class="form-control mb-3" value="{{ old('email') }}" name="email">
                                <span style="color:red">
                                    @error('email')
                                    <div class="text-danger"> {{ $message }} </div>
                                    @enderror
                                </span>
                                <label for="">Contact:</label><br>
                                <input type="tel" class="form-control mb-8" value="{{ old('phone') }}" name="phone" id="phone">
                                <span style="color:red">
                                    @error('phone')
                                    <div class="text-danger"> {{ $message }} </div>
                                    @enderror
                                </span>
                            </div>
                            <div class="form-section">
                                <label for="">Identifiant:</label>
                                <input type="text" class="form-control mb-3" value="{{ old('pseudo') }}" name="pseudo">
                                <span style="color:red">
                                    @error('pseudo')
                                    <div class="text-danger"> {{ $message }} </div>
                                    @enderror
                                </span>
                                <label for="password">Mot de passe:</label>
                                <input type="password" class="form-control mb-3" name="password">
                                <span style="color:red">
                                    @error('password')
                                    <div class="text-danger"> {{ $message }} </div>
                                    @enderror
                                </span>
                                <label for="passwordConf">Confirmer mot de passe:</label>
                                <input type="password" class="form-control mb-3" name="passwordConf">
                                <span style="color:red">
                                    @error('passwordConf')
                                    <div class="text-danger"> {{ $message }} </div>
                                    @enderror
                                </span>
                                <p id="message"></p>
                                <input type="text" class="form-control mb-3" name="status" value="Client" hidden>
                                <!-- <input type="text" class="form-control mb-3" name="mt1" value="0" hidden>
                                <input type="text" class="form-control mb-3" name="mt2" value="0" hidden>
                                <input type="text" class="form-control mb-3" name="mt3" value="0" hidden>
                                <input type="text" class="form-control mb-3" name="etat" value="2" hidden>-->
                            </div>
                            <div class="form-navigation mt-3">
                                <button type="button" class="previous btn btn-primary float-left">&lt;
                                    Précédent</button>
                                &nbsp;&nbsp;
                                <button type="button" class="next btn btn-primary float-right">Suivant
                                    &gt;</button>

                                <button type="submit" class="btn btn-success float-right">Terminer</button>
                            </div>

                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panels-container">
        <div class="panel left-panel">
            <div class="content">
                <h3>A SAVOIR</h3>
                <p>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Quam quas vel rerum. Veniam quisquam
                    facere, cumque, voluptas ratione doloribus laudantium ipsum dicta optio ut rerum rem nulla qui
                    consequuntur voluptatibus!
                </p>
                <button class="btn transparent" id="sign-in-btn"><a href="{{ route('login_form') }}" style="text-decoration: none;">
                        Se connecter
                    </a>
                </button>
            </div>

            <img src="{{ asset('Auth/img/log.svg') }}" class="image" alt="" />
        </div>
    </div>
</div>

@endsection