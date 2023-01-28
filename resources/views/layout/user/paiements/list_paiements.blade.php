@extends('layout.user.user')
@section('content')
    <div class="row mt">
        <div class="col-lg-12">
            <div class="form-panel">
                <h1 class="mb"><i class="fa fa-angle-right"></i> Configuration Paiement </h1>
                <div class="row">
                    <div>
                        @if (session()->exists('message4'))
                            <div class="alert alert-success" id="alert" align="center">
                                {{ session('message4') }}
                            </div>
                        @endif
                    </div>
                    <div>
                        @if (session()->exists('echec4'))
                            <div class="alert alert-danger" id="alert" align="center">
                                {{ session('echec4') }}
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary btn-md" data-toggle="modal"
                            data-target="#addPaieModal"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;
                            Ajouter
                        </button><br><br><br>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="addPaieModal" tabindex="-1" aria-labelledby="addPaieModal"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addPaieModal">Enregistrer code paiement</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <form class="form-horizontal style-form" method="POST"
                                                action="{{ route('paiements.store') }}" id="form_user">
                                                @csrf
                                                <div class="form-group">
                                                    <div class="col-sm-10">
                                                        <span>
                                                            <h5>Code Paiement</h5>
                                                        </span>
                                                        <div class="col-12">
                                                            <input type="text" class="form-control" name="codePaie">
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-10">
                                                        <span>
                                                            <h5>Libelle Paiement</h5>
                                                        </span>
                                                        <div class="col-12">
                                                            <input type="text" class="form-control" name="libellePaie">
                                                        </div>

                                                    </div>
                                                </div>
                                                <button type="submit"
                                                    class="btn btn-round btn-success">Enregistrer</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-round btn-danger"
                                        data-dismiss="modal">Fermer</button>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <table id="paiementListe" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Code Paiement</th>
                                    <th>Libelle Paiement</th>
                                    <th>Etat</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($paiements as $paie)
                                    @php
                                        $verify_code_pay = App\Models\Personne::where('paiement_id', $paie->id)->exists();
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $paie->code_paiement }} </td>
                                        <td>{{ $paie->libelle_paiement }} </td>
                                        <td>

                                            @if ($verify_code_pay === true)
                                                <p class="text-warning"> Code utilisé </p>
                                            @else
                                                <p class="text-success"> Disponible </p>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($verify_code_pay === true)
                                                <p> - </p>
                                            @else
                                                <a href="{{ route('edit_paiement', $paie->id) }}" title=""> <button
                                                        class="btn btn-primary btn-circle" data-toggle="modal"
                                                        data-target="#modifPaieModal"><i class="fa fa-pencil-square-o"
                                                            aria-hidden="true"></i>Modifier</button> </a>
                                                <form method="POST" action="{{ route('personnes.destroy', $paie->id) }}"
                                                    accept-charset="UTF-8" style="display:inline">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete "
                                                        onclick="return confirm('voulez-vous supprimer cet élément?')"><i
                                                            class="fa fa-trash-o" aria-hidden="true"></i> Supprimer</button>
                                                </form>
                                            @endif


                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
