@extends('layout.user.user')
@section('content')
<div class="row mt">
    <div class="col-lg-12">
        <div class="form-panel">
            <h1 class="mb"><i class="fa fa-angle-right"></i> Configuration de base </h1>
            <div class="row">
                <div class="col-md-6">
                    <h3> Modifier sexe Informations</h3>

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
                                    <option value="{{$sexes->code}}">{{$sexes->code}} </option>
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

                        <button type="submit" value="Update" class="btn btn-round btn-success"><i class="fa fa-check" aria-hidden="true"></i>
                            Modifier</button><br><br>
                    </form>
                    <a href="{{ url('Configuration') }}" title=""> <button class="btn btn-round btn-danger"><i class="fa fa-close" aria-hidden="true"></i> Annuler</button> </a>
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