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
                    <form class="form-horizontal style-form" method="POST" action="{{ url('Configuration') }}" id="form_user">
                        @csrf
                        <div class="form-group">
                            <div class="col-sm-10">
                                <span>
                                    <h5>Code Sexe</h5>
                                </span>
                                <select name="codeSexe" placeholder="Code" class="form-control mb-3">
                                    <option value=""> </option>
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
                                <div class="col-12">
                                    <input type="text" class="form-control" name="libelleSexe">
                                </div>

                            </div>
                        </div>

                        <button type="submit" class="btn btn-round btn-primary">
                            Enregistrer</button>
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
                    <form class="form-horizontal style-form" method="POST" action="{{ url('Liste_Etat') }}" id="form_auth">
                        @csrf
                        <div class="form-group">
                            <div class="col-sm-10">
                                <span>
                                    <h5>Code Etat</h5>
                                </span>
                                <select name="codeEtat" placeholder="Code" class="form-control mb-3">
                                    <option value=""> </option>
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
                                    <input type="text" class="form-control" name="libelleEtat">
                                </div>
                            </div>

                        </div>

                        <button type="submit" class="btn btn-round btn-primary">
                            Sauvegarder </button>
                    </form><br><br><br>
                </div>
                <div class="col-lg-12">
                    <h4><i class="fa fa-edit"></i> Sexe Table </h4><br>
                    <table id="sexeTab" class="display" style="width:100%">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Code</th>
                                <th>Libelle</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sexes as $sexe)
                            <tr>
                                <td> {{$loop->index+1}} </td>
                                <td> {{$sexe->code}} </td>
                                <td> {{$sexe->libelle}} </td>
                                <td align="center">
                                    <a href="{{ url('/Configuration/' . $sexe->id . '/edit') }}" title=""> <button class="btn btn-primary btn-circle"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Modifier</button> </a>
                                    <form method="POST" action="{{ url('/Configuration' . '/' . $sexe->id) }}" accept-charset="UTF-8" style="display:inline">
                                        {{ method_field('DELETE') }}
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete " onclick="return confirm('voulez-vous supprimer cet élément?')"><i class="fa fa-trash-o" aria-hidden="true"></i> Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table><br><br>

                </div>

            </div>

        </div>
    </div>
</div>
@endsection