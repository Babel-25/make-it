@extends('layout.user.user')
@section('content')
    <div class="row mt">
        <div class="col-lg-12">
            <div class="form-panel">
                <h1 class="mb"><i class="fa fa-angle-right"></i> Configuration de base </h1>
                <div class="row">
                    <div>
                        @if (session()->exists('message3'))
                            <div class="alert alert-success" id="alert" align="center">
                                {{ session('message3') }}
                            </div>
                        @endif
                    </div>
                    <div>
                        @if (session()->exists('echec6'))
                            <div class="alert alert-danger" id="alert" align="center">
                                {{ session('echec6') }}
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-12">
                        <h4><i class="fa fa-edit"></i> Liste des utilisateurs sur la plateforme </h4><br>
                        <table id="etatTab" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Numéro</th>
                                    <th>Nom Prénom(s)</th>
                                    <th>Contact</th>
                                    <th>Adresse</th>
                                    <th>date de naissance</th>
                                    <th>Sexe</th>
                                    <th>email</th>
                                    <th>Code Parrainage</th>
                                    <th>Pseudo</th>
                                    <th>Code Paiement</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($personnes as $personne)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $personne->nom_prenom }}</td>
                                        <td>{{ $personne->contact }}</td>
                                        <td>{{ $personne->adresse }}</td>
                                        <td>{{ $personne->date_naissance }}</td>
                                        <td>{{ $personne->libelle }}</td>
                                        <td>{{ $personne->email }}</td>
                                        <td>{{ $personne->code_parrainage }}</td>
                                        <td>{{ $personne->identifiant }}</td>
                                        <td>{{ $personne->code_paiement }}</td>
                                        <td align="center">
                                            <form method="POST" action="{{ route('personnes.destroy', $personne->id) }}"
                                                accept-charset="UTF-8" style="display:inline">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete"
                                                    onclick="return confirm('voulez-vous supprimer cet élément?')"><i
                                                        class="fa fa-trash-o" aria-hidden="true"></i> Supprimer</button>
                                            </form>

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
