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
                <h1 class="mb"><i class="fa fa-angle-right"></i> Administration </h1>
                <div class="row">
                    <div class="col-md-6">
                        <h3> Configuration sexe </h3>
                        <form class="form-horizontal style-form" method="POST" action="#" id="form_user">
                            @csrf
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <select name="libelle" class="form-control mb-3">
                                        <option value="M"> Masculin </option>
                                        <option value="F"> Féminin </option>
                                    </select>
                                </div>


                            </div>


                            <button type="submit" class="btn btn-round btn-primary btn-block">
                                Enregistrer</button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <h3> Etat Utilisateur </h3>
                        <form class="form-horizontal style-form" method="POST" action="#" id="form_auth">
                            @csrf
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label"> Libellé </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="libelle">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-round btn-primary btn-block">
                                Sauvegarder </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
