@extends('layout.user.user')
@section('content')
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
                <h1 class="mb"><i class="fa fa-angle-right"></i> Mon Profil</h1>
                <div class="row">
                    <div class="col-md-6">
                        <h3> Informations personnelles</h3>
                        <form class="form-horizontal style-form" method="POST"
                            action="{{ route('personnes.update', $user->id) }}" id="form_user">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label"> Nom & Prénom(s) </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name"
                                        value="{{ $user->nom_prenom }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label"> Contact </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="contact" value="{{ $user->contact }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label"> Email</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" name="email" value="{{ $user->email }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label"> Adresse</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="adresse" value="{{ $user->adresse }}">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-round btn-success btn-block">
                                Mettre à jour</button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <h3> Informations de connexion</h3>
                        <form class="form-horizontal style-form" method="POST"
                            action="{{ route('user_update', auth()->user()->id) }}" id="form_auth">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label"> Identifiant </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="identifiant"
                                        value="{{ auth()->user()->identifiant }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label"> Nouveau mot de passe </label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" name="password">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">Confirmer nouveau mot de passe </label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" name="password_confirmation">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-round btn-success btn-block">
                                Modifier</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
        <!-- col-lg-12-->
    </div>
@endsection
