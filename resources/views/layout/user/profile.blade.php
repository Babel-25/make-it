@extends('layout.user.user')
@section('content')
@section('title', 'Mon profil')
<div>
    @if (session()->exists('message'))
        <div class="alert alert-success" id="alert">
            {{ session('message') }}
        </div>
    @endif
</div>
<div class="row mt">
    <div class="col-lg-12">
        <div class="form-panel">
            <h1 class="mb"><i class="fa fa-angle-right"></i> Mon Profil <button id="switch_state" onclick="onClik()"
                    class="btn btn-success"> Modifier </button>
            </h1>
            <div>
                <p> Votre code de parrainage :
                    @if (auth()->user()->status === 'Admin')
                        <b class="text-success"> {{ $personne->code_parrainage  }} </b>
                    @else
                        <b class="text-success"> {{ $personne->lien_parrainage }} </b>
                    @endif
                </p>
            </div>
            <div align="right">
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    @if ($etat->libelle === 'Inactif')
                        <label class="btn btn-danger">
                            <input type="radio" name="options" id="option1" disabled> Inactif
                        </label>
                        <label class="btn btn-secondary">
                            <input type="radio" name="options" id="option2" checked> Actif &nbsp; &nbsp;
                        </label>
                    @else
                        <label class="btn btn-secondary">
                            <input type="radio" name="options" id="option1" disabled> Inactif
                        </label>
                        <label class="btn btn-success">
                            <input type="radio" name="options" id="option2" checked> Actif &nbsp; &nbsp;
                        </label>
                    @endif

                </div>
            </div>
            <div class="row">

                <div class="col-md-6">
                    <h3> Informations personnelles </h3>
                    <form class="form-horizontal style-form" name="form_person" method="POST"
                        action="{{ route('personnes.update', $personne->id) }}" id="form_user">
                        @csrf
                        @method('PUT')
                        {{-- <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"> Code de Parrainage </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" disabled="true" name="name"
                                    value="{{ $personnes->code_parrainage }}">
                            </div>
                        </div> --}}
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"> Nom & Prénom(s) </label>
                            <div class="col-sm-10" id="name_on" style="display: none">
                                <input type="text" class="form-control" name="nom_prenom"
                                    value="{{ $personne->nom_prenom }}">
                                @error('nom_prenom')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>
                            <div class="col-sm-10" id="name_off">
                                <input type="text" class="form-control" value="{{ $personne->nom_prenom }}" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"> Contact </label>
                            <div class="col-sm-10" id="contact_on" style="display: none">
                                <input type="text" class="form-control" name="contact"
                                    value="{{ $personne->contact }}">
                                @error('contact')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>
                            <div class="col-sm-10" id="contact_off">
                                <input type="text" class="form-control" value="{{ $personne->contact }}" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"> Email</label>
                            <div class="col-sm-10" id="email_on" style="display: none">
                                <input type="email" class="form-control" name="email"
                                    value="{{ $personne->email }}">
                                @error('email')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>
                            <div class="col-sm-10" id="email_off">
                                <input type="email" class="form-control" value="{{ $personne->email }}" readonly>

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"> Adresse</label>
                            <div class="col-sm-10" id="adresse_on" style="display: none">
                                <input type="text" class="form-control" name="adresse"
                                    value="{{ $personne->adresse }}">
                                @error('adresse')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>
                            <div class="col-sm-10" id="adresse_off">
                                <input type="text" class="form-control" value="{{ $personne->adresse }}" readonly>
                            </div>
                        </div>

                        <button id="btn_person" type="submit" class="btn btn-round btn-success btn-block" disabled>
                            Mettre à jour</button>
                    </form>
                </div>
                <div class="col-md-6">
                    <h3> Informations de connexion</h3>
                    <form class="form-horizontal style-form" name="form_connection" method="POST"
                        action="{{ route('user_update', auth()->user()->id) }}" id="form_auth">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"> Identifiant </label>
                            <div class="col-sm-10" id="identifiant_on" style="display: none">
                                <input type="text" class="form-control" name="identifiant"
                                    value="{{ auth()->user()->identifiant }}">
                                @error('identifiant')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>
                            <div class="col-sm-10" id="identifiant_off">
                                <input type="text" class="form-control" value="{{ auth()->user()->identifiant }}"
                                    readonly>

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"> Nouveau mot de passe </label>
                            <div class="col-sm-10" id="pwd_on" style="display: none">
                                <input type="password" class="form-control" name="password">
                                @error('password')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>
                            <div class="col-sm-10" id="pwd_off">
                                <input type="password" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Confirmer nouveau mot de passe </label>
                            <div class="col-sm-10" id="pwd_conf_on" style="display: none">
                                <input type="password" class="form-control" name="password_confirmation">
                                @error('password_confirmation')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>
                            <div class="col-sm-10" id="pwd_conf_off">
                                <input type="password" class="form-control" readonly>
                            </div>
                        </div>
                        <button id="btn_connection" type="submit" class="btn btn-round btn-success btn-block"
                            disabled>
                            Modifier</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function onClik() {
        document.getElementById('name_on').style.display = 'block';
        document.getElementById('name_off').style.display = 'none';
        document.getElementById('contact_on').style.display = 'block';
        document.getElementById('contact_off').style.display = 'none';
        document.getElementById('email_on').style.display = 'block';
        document.getElementById('email_off').style.display = 'none';
        document.getElementById('adresse_on').style.display = 'block';
        document.getElementById('adresse_off').style.display = 'none';
        document.getElementById('identifiant_on').style.display = 'block';
        document.getElementById('identifiant_off').style.display = 'none';
        document.getElementById('pwd_on').style.display = 'block';
        document.getElementById('pwd_off').style.display = 'none';
        document.getElementById('pwd_conf_on').style.display = 'block';
        document.getElementById('pwd_conf_off').style.display = 'none';
        document.getElementById('btn_person').disabled = false;
        document.getElementById('btn_connection').disabled = false;
    }
</script>

@endsection
