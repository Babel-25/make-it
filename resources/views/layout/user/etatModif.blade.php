@extends('layout.user.user')
@section('content')
<div class="row mt">
    <div class="col-lg-12">
        <div class="form-panel">
            <h1 class="mb"><i class="fa fa-angle-right"></i> Configuration de base </h1>
            <div class="row">
                <div class="col-md-6">
                    <h3> Modifier Etat Utilisateur </h3>
                    <form class="form-horizontal style-form" method="POST" action="{{ url('Liste_Etat/' .$etats->id) }}" id="form_auth">
                        @csrf
                        @method("PATCH")
                        <div class="form-group">
                            <input type="hidden" name="id" id="id" value="{{$etats->id}}" />
                            <div class="col-sm-10">
                                <span>
                                    <h5>Code Etat</h5>
                                </span>
                                <select name="codeEtat" placeholder="Code" class="form-control mb-3" readonly="true">
                                    <option value="{{$etats->code}}"> {{$etats->code}}</option>
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
                                    <input type="text" value="{{$etats->libelle}} " class="form-control" name="libelleEtat">
                                </div>
                            </div>

                        </div>

                        <button type="submit" class="btn btn-round btn-success"><i class="fa fa-check" aria-hidden="true"></i>
                            Modifier </button><br><br>
                    </form>
                    <a href="{{ url('Liste_Etat') }}" title=""> <button class="btn btn-round btn-danger">
                            <i class="fa fa-close" aria-hidden="true"></i> Annuler</button> </a>
                </div>
                <div class="col-md-6" align="center">
                    <div class="wrap-login100">
                        <div class="login100-pic js-tilt" data-tilt>
                            <img src="{{ asset('User/img/img-01.png') }}" alt="IMG">
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection