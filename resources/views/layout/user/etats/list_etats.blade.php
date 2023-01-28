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
                    @if (session()->exists('echec3'))
                    <div class="alert alert-danger" id="alert" align="center">
                        {{ session('echec3') }}
                    </div>
                    @endif
                </div>
                <div class="col-lg-12">
                    <h4><i class="fa fa-edit"></i> Etat Table </h4><br>
                    <table id="etatTab" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Code</th>
                                <th>Libelle</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($etats as $etat)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{$etat->code}}</td>
                                <td>{{$etat->libelle}}</td>
                                <td align="center">
                                    <a href="{{ route('edit_etat',$etat->id) }} " title=""> <button class="btn btn-primary btn-circle"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Modifier</button> </a>
                                    <form method="POST" action="{{ route('etats.destroy',$etat->id) }}" accept-charset="UTF-8" style="display:inline">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('voulez-vous supprimer cet élément?')"><i class="fa fa-trash-o" aria-hidden="true"></i> Supprimer</button>
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
