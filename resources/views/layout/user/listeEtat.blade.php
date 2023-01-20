@extends('layout.user.user')
@section('content')
<div class="row mt">
    <div class="col-lg-12">
        <div class="form-panel">
            <h1 class="mb"><i class="fa fa-angle-right"></i> Configuration de base </h1>
            <div class="row">
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
                                    <a href="url('/Liste_Etat/' . $etat->id . '/edit')" title=""> <button class="btn btn-primary btn-circle"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Modifier</button> </a>
                                    <form method="POST" action="{{ url('/Liste_Etat' . '/' . $etat->id) }}" accept-charset="UTF-8" style="display:inline">
                                        {{ method_field('DELETE') }}
                                        {{ csrf_field() }}
                                        <!--<a href="/supprimerEtat{{$etat->id}}" onclick="Action()" class="btn btn-danger btn-circle"><i class="fa fa-trash-o" aria-hidden="true"></i> Supprimer</a>-->
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Confirm delete?')"><i class="fa fa-trash-o" aria-hidden="true"></i> Supprimer</button>
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