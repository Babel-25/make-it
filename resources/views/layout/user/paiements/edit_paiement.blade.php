@extends('layout.user.user')
@section('content')
    <div class="row mt">
        <div class="col-lg-12">
            <div class="form-panel">
                <h1 class="mb"><i class="fa fa-angle-right"></i> Configuration de base </h1>
                <div class="row">
                    <div class="col-md-6">
                        <h3> Modifier Paiement information </h3>
                        <form class="form-horizontal style-form" method="POST"
                            action="{{ route('paiements.update', $paiement->id) }}" id="form_user">
                            @csrf
                            @method('PATCH')
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <span>
                                        <h5>Code Paiement</h5>
                                    </span>
                                    <div c lass="col-12">
                                        <input type="text" value="{{ $paiement->code_paiement }} " class="form-control"
                                            name="codePaie" id="codePaie" readonly>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <span>
                                        <h5>Libelle Sexe</h5>
                                    </span>
                                    <div c lass="col-12">
                                        <input type="text" value="{{ $paiement->libelle_paiement }} "
                                            class="form-control" name="libellePaie" id="libellePaie">
                                    </div>

                                </div>
                            </div>

                            <button type="submit" value="Update" class="btn btn-round btn-success"><i class="fa fa-check"
                                    aria-hidden="true"></i>
                                Modifier</button><br><br>

                        </form>
                        <a href="{{ route('list_paiements') }}" title=""> <button class="btn btn-round btn-danger"><i
                                    class="fa fa-close" aria-hidden="true"></i> Annuler</button> </a>
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
