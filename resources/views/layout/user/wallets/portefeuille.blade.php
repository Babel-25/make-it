@extends('layout.user.user')
@section('content')
@section('title', 'Mon portefeuille')
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
            <h1 class="mb"><i class="fa fa-angle-right"></i> Mon Portefeuille
            </h1>
            <div class="row">
                <div align="center">
                    <div class="col-md-12">
                        <h3> Informations personnelles</h3>
                        <form class="form-horizontal style-form" name="form_wallet" method="POST" action="#"
                            id="form_wallet">
                            @csrf
                            <div class="form-group">
                                <label class="col-sm-3 col-sm-3 control-label"> Identifiant </label>
                                <div class="col-sm-9" id="">
                                    <input type="text" class="form-control" value="{{ auth()->user()->identifiant }}"
                                        readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-sm-3 control-label"> Gain Brut Phase A : </label>
                                <div class="col-sm-9" id="">
                                    <input type="text" class="form-control" value="{{ $montant_phase1 }}" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-sm-3 control-label"> Gain Parrainage direct : </label>
                                <div class="col-sm-9" id="">
                                    <input type="text" class="form-control" value="{{ $montant_parrainage }}"
                                        readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-sm-3 control-label"> Gain net total : </label>
                                <div class="col-sm-9" id="">
                                    <input type="text" class="form-control"
                                        value="{{ $montant_net }}" readonly>
                                </div>
                            </div>
                        </form>
                        <div align="right">
                            <a href="{{ route('dashboard') }}" title=""> <button
                                    class="btn btn-round btn-success">
                                    <i class="fa fa-check" aria-hidden="true"></i> Verifier </button> </a>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>



@endsection
