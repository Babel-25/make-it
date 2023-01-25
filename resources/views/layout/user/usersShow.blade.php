@extends('layout.user.user')
@section('content')
@section('title', ' Utilisateurs ')
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
            <h1 class="mb"><i class="fa fa-angle-right"></i> Urilisateurs <button id="switch_state" onclick="onClik()" class="btn btn-success"> Modifier </button>
            </h1>
            <div class="row">
                <div class="col-md-6">
                    <h3> Informations personnelles</h3>
                    <form class="form-horizontal style-form" name="form_person" method="POST" action="{{ url('Liste_User/' .$utilisateur->id) }}" id="form_user">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="id" id="id" value="{{$utilisateur->id}}" />
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"> Code de Parrainage </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" disabled="true" name="name" value="{{ $utilisateur->code_parrainage }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"> Nom & Prénom(s) </label>
                            <div class="col-sm-10" id="name_on" style="display: none">
                                <input type="text" class="form-control" name="nom_prenom"
                                    value="{{ $utilisateur->nom_prenom }}">
                                @error('nom_prenom')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>
                            <div class="col-sm-10" id="name_off">
                                <input type="text" class="form-control" value="{{ $utilisateur->nom_prenom }}" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"> Contact </label>
                            <div class="col-sm-10" id="contact_on" style="display: none">
                                <input type="text" class="form-control" name="contact" value="{{ $utilisateur->contact }}">
                                @error('contact')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>
                            <div class="col-sm-10" id="contact_off">
                                <input type="text" class="form-control" value="{{ $utilisateur->contact }}" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"> Email</label>
                            <div class="col-sm-10" id="email_on" style="display: none">
                                <input type="email" class="form-control" name="email" value="{{ $utilisateur->email }}">
                                @error('email')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>
                            <div class="col-sm-10" id="email_off">
                                <input type="email" class="form-control" value="{{ $utilisateur->email }}" readonly>

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"> Adresse</label>
                            <div class="col-sm-10" id="adresse_on" style="display: none">
                                <input type="text" class="form-control" name="adresse" value="{{ $utilisateur->adresse }}">
                                @error('adresse')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>
                            <div class="col-sm-10" id="adresse_off">
                                <input type="text" class="form-control" value="{{ $utilisateur->adresse }}" readonly>
                            </div>
                        </div>

                        <button id="btn_person" type="submit" class="btn btn-round btn-success btn-block" disabled>
                            Modifier  </button>
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