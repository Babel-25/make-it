@extends('layout.user.user')
@section('content')
<div class="row mt">
    <div class="col-lg-12">
        <div class="form-panel">
            <h1 class="mb"><i class="fa fa-angle-right"></i> Configuration de base </h1>
            <div class="row">
                <div class="col-md-6">
                    <h3> Configuration sexe </h3>
                    <div>
                        @if (session()->exists('message1'))
                        <div class="alert alert-success" id="alert" align="center">
                            {{ session('message1') }}
                        </div>
                        @endif
                    </div>
                    <div>
                        @if (session()->exists('echec1'))
                        <div class="alert alert-danger" id="alert" align="center">
                            {{ session('echec1') }}
                        </div>
                        @endif
                    </div>
                    <form class="form-horizontal style-form" method="POST" action="{{ url('Configuration/' .$sexes->id) }}" id="form_user">
                        @csrf
                        @method("PATCH")
                        <div class="form-group">
                            <input type="hidden" name="id" id="id" value="{{$sexes->id}}" />
                            <div class="col-sm-10">
                                <span>
                                    <h5>Code Sexe</h5>
                                </span>
                                <select name="codeSexes" id="codeSexes" class="form-control mb-3" readonly="True">
                                    <option value="">{{$sexes->code}} </option>
                                    <option value="M"> M </option>
                                    <option value="F"> F</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10">
                                <span>
                                    <h5>Libelle Sexe</h5>
                                </span>
                                <div c lass="col-12">
                                    <input type="text" value="{{$sexes->libelle}} " class="form-control" name="libelleSexes" id="libelleSexes">
                                </div>

                            </div>
                        </div>

                        <button type="submit" value="Update" class="btn btn-round btn-success">
                            Modifier</button>
                    </form>
                </div>
                <div class="col-md-6">
                    <h3> Etat Utilisateur </h3>
                    <div>
                        @if (session()->exists('message2'))
                        <div class="alert alert-success" id="alert" align="center">
                            {{ session('message2') }}
                        </div>
                        @endif
                    </div>
                    <div>
                        @if (session()->exists('echec2'))
                        <div class="alert alert-danger" id="alert" align="center">
                            {{ session('echec2') }}
                        </div>
                        @endif
                    </div>
                    <form class="form-horizontal style-form" method="POST" action="{{ url('Liste_Etat/' .$etat->id) }}" id="form_auth">
                        @csrf
                        <div class="form-group">
                            <input type="hidden" name="id" id="id" value="{{$etat->id}}" />
                            <div class="col-sm-10">
                                <span>
                                    <h5>Code Etat</h5>
                                </span>
                                <select name="codeEtat" placeholder="Code" class="form-control mb-3" readonly="true">
                                    <option value=""> {{$etat->code}}</option>
                                    <option value="ACT"> ACT </option>
                                    <option value="INA"> INA</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10">
                                <span>
                                    <h5>Libelle Etat</h5>
                                </span>
                                <div class="col-12">
                                    <input type="text" value="{{$etat->libelle}} " class="form-control" name="libelleEtat">
                                </div>
                            </div>

                        </div>

                        <button type="submit" class="btn btn-round btn-danger">
                            Sauvegarder </button>
                    </form><br><br><br>
                </div>

            </div>

        </div>
    </div>
</div>

@endsection