@extends('layout.user.user')
@section('content')
    <div>
        @if (session()->exists('message'))
            <div class="alert alert-success" id="alert">
                {{ session('message') }}
            </div>
        @endif
    </div>
    <section class="mt container">
        <div class="col-lg-12">
            <div id="Moi">
                <div class=" text-center text-success m-3" style="border-color: green;border-style:solid;height:3em"> MOI
                </div>
            </div>
            <div id="2F" class="mt">
                <div class="row">
                    @switch(count($niv1_fieuls))
                        @case(0)
                            <div class="col-md-5" style="border-color: green;border-style:solid;height:3em"> </div>
                            <div class="col-md-2"> </div>
                            <div class="col-md-5" style="border-color: green;border-style:solid;height:3em">
                            </div>
                        @break

                        @case(1)
                            @foreach ($niv1_fieuls as $item)
                                @foreach ($personnes as $personne)
                                    @if ($item->personne_id === $personne->id)
                                        <div class="col-md-5" style="border-color: green;border-style:solid;height:3em">
                                            {{ $personne->nom_prenom }} </div>
                                    @endif
                                @endforeach
                            @endforeach
                            <div class="col-md-2"> </div>
                            <div class="col-md-5" style="border-color: green;border-style:solid;height:3em"> </div>
                        @break

                        @case(2)
                            @foreach ($niv1_fieuls as $item)
                                @foreach ($personnes as $personne)
                                    @if ($item->personne_id === $personne->id)
                                        <div class="col-md-5" style="border-color: green;border-style:solid;height:3em">
                                            {{ $personne->nom_prenom }} </div>
                                        <div class="col-md-2"> </div>
                                    @endif
                                @endforeach
                            @endforeach
                        @break

                        @default
                            <div class="col-md-5" style="border-color: green;border-style:solid;height:3em"> </div>
                            <div class="col-md-2"> </div>
                            <div class="col-md-5" style="border-color: green;border-style:solid;height:3em">
                        @endswitch

                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="row mt" id="4F">
                <div class="col-md-6">
                    @switch(count($niv2_fieuls1))
                        @case(0)
                            <div class="col-md-5" style="border-color: green;border-style:solid;height:3em">

                            </div>
                            <div class="col-md-5 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                        @break

                        @case(1)
                            @foreach ($niv2_fieuls1 as $item)
                                @foreach ($personnes as $personne)
                                    @if ($item->personne_id === $personne->id)
                                        <div class="col-md-5" style="border-color: green;border-style:solid;height:3em">
                                            {{ $personne->nom_prenom }} </div>
                                    @endif
                                @endforeach
                            @endforeach
                            <div class="col-md-5 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                        @break

                        @case(2)
                            @foreach ($niv2_fieuls1 as $item)
                                @foreach ($personnes as $personne)
                                    @if ($item->personne_id === $personne->id)
                                        <div class="col-md-5 col-md-offset-1"
                                            style="border-color: green;border-style:solid;height:3em">
                                            {{ $personne->nom_prenom }} </div>
                                    @endif
                                @endforeach
                            @endforeach
                        @break

                        @default
                            <div class="col-md-5 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                            <div class="col-md-5 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                    @endswitch

                </div>
                <div class="col-md-6 ">
                    @switch(count($niv2_fieuls2))
                        @case(0)
                            <div class="col-md-5" style="border-color: green;border-style:solid;height:3em">

                            </div>
                            <div class="col-md-5 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                        @break

                        @case(1)
                            @foreach ($niv2_fieuls2 as $item)
                                @foreach ($personnes as $personne)
                                    @if ($item->personne_id === $personne->id)
                                        <div class="col-md-5" style="border-color: green;border-style:solid;height:3em">
                                            {{ $personne->nom_prenom }} </div>
                                    @endif
                                @endforeach
                            @endforeach
                            <div class="col-md-5 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                        @break

                        @case(2)
                            @foreach ($niv2_fieuls2 as $item)
                                @foreach ($personnes as $personne)
                                    @if ($item->personne_id === $personne->id)
                                        <div class="col-md-5 col-md-offset-1"
                                            style="border-color: green;border-style:solid;height:3em">
                                            {{ $personne->nom_prenom }} </div>
                                    @endif
                                @endforeach
                            @endforeach
                        @break

                        @default
                            <div class="col-md-5 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                            <div class="col-md-5 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                    @endswitch
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="row mt" id="8F">
                <div class="col-md-4">
                    @switch(count($niv3_fieuls1))
                        @case(0)
                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                        @break

                        @case(1)
                            @foreach ($niv3_fieuls1 as $item)
                                @foreach ($personnes as $personne)
                                    @if ($item->personne_id === $personne->id)
                                        <div class="col-md-2 col-md-offset-1"
                                            style="border-color: green;border-style:solid;height:3em">
                                            {{ $personne->nom_prenom }}
                                        </div>
                                    @endif
                                @endforeach
                            @endforeach
                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                        @break

                        @case(2)
                            @foreach ($niv3_fieuls1 as $item)
                                @foreach ($personnes as $personne)
                                    @if ($item->personne_id === $personne->id)
                                        <div class="col-md-2 col-md-offset-1"
                                            style="border-color: green;border-style:solid;height:3em">
                                            {{ $personne->nom_prenom }}
                                        </div>
                                    @endif
                                @endforeach
                            @endforeach
                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                        @break

                        @case(3)
                            @foreach ($niv3_fieuls1 as $item)
                                @foreach ($personnes as $personne)
                                    @if ($item->personne_id === $personne->id)
                                        <div class="col-md-2 col-md-offset-1"
                                            style="border-color: green;border-style:solid;height:3em">
                                            {{ $personne->nom_prenom }}
                                        </div>
                                    @endif
                                @endforeach
                            @endforeach
                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                        @break

                        @case(4)
                            @foreach ($niv3_fieuls1 as $item)
                                @foreach ($personnes as $personne)
                                    @if ($item->personne_id === $personne->id)
                                        <div class="col-md-2 col-md-offset-1"
                                            style="border-color: green;border-style:solid;height:3em">
                                            {{ $personne->nom_prenom }}
                                        </div>
                                    @endif
                                @endforeach
                            @endforeach
                        @break

                        @default
                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                    @endswitch

                </div>
                <div class="col-md-4 col-md-offset-4">
                    @switch(count($niv3_fieuls2))
                        @case(0)
                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                        @break

                        @case(1)
                            @foreach ($niv3_fieuls2 as $item)
                                @foreach ($personnes as $personne)
                                    @if ($item->personne_id === $personne->id)
                                        <div class="col-md-2 col-md-offset-1"
                                            style="border-color: green;border-style:solid;height:3em">
                                            {{ $personne->nom_prenom }}
                                        </div>
                                    @endif
                                @endforeach
                            @endforeach
                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                        @break

                        @case(2)
                            @foreach ($niv3_fieuls2 as $item)
                                @foreach ($personnes as $personne)
                                    @if ($item->personne_id === $personne->id)
                                        <div class="col-md-2 col-md-offset-1"
                                            style="border-color: green;border-style:solid;height:3em">
                                            {{ $personne->nom_prenom }}
                                        </div>
                                    @endif
                                @endforeach
                            @endforeach
                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                        @break

                        @case(3)
                            @foreach ($niv3_fieuls2 as $item)
                                @foreach ($personnes as $personne)
                                    @if ($item->personne_id === $personne->id)
                                        <div class="col-md-2 col-md-offset-1"
                                            style="border-color: green;border-style:solid;height:3em">
                                            {{ $personne->nom_prenom }}
                                        </div>
                                    @endif
                                @endforeach
                            @endforeach
                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                        @break

                        @case(4)
                            @foreach ($niv3_fieuls2 as $item)
                                @foreach ($personnes as $personne)
                                    @if ($item->personne_id === $personne->id)
                                        <div class="col-md-2 col-md-offset-1"
                                            style="border-color: green;border-style:solid;height:3em">
                                            {{ $personne->nom_prenom }}
                                        </div>
                                    @endif
                                @endforeach
                            @endforeach
                        @break

                        @default
                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                    @endswitch
                </div>
            </div>
        </div>

        {{-- <div class="col-lg-12">
            <div class="row mt" id="16F">

                <div class="col-md-5">
                    <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                    </div>
                    <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                    </div>
                    <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                    </div>
                    <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                    </div>
                    <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                    </div>
                    <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                    </div>
                    <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                    </div>
                    <div class="col-md-1" style="border-color: green;border-style:solid;height:3em">

                    </div>
                </div>
                <div class="col-md-5 col-md-offset-2">
                    <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                    </div>
                    <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                    </div>
                    <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                    </div>
                    <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                    </div>
                    <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                    </div>
                    <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                    </div>
                    <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                    </div>
                    <div class="col-md-1" style="border-color: green;border-style:solid;height:3em">

                    </div>
                </div>
            </div>
        </div> --}}


        <div class="col-lg-12">
            <div class="row mt" id="16F">

                <div class="col-md-5">
                    @switch(count($niv4_fieuls1))
                        @case(0)
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                        @break

                        @case(1)
                            @foreach ($niv4_fieuls1 as $item)
                                @foreach ($personnes as $personne)
                                    @if ($item->personne_id === $personne->id)
                                        <div class="col-md-1"
                                            style="border-color: green;border-style:solid;height:3em;margin-right:1em">
                                            {{ $personne->nom_prenom }}
                                        </div>
                                    @endif
                                @endforeach
                            @endforeach
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                        @break

                        @case(2)
                            @foreach ($niv4_fieuls1 as $item)
                                @foreach ($personnes as $personne)
                                    @if ($item->personne_id === $personne->id)
                                        <div class="col-md-1"
                                            style="border-color: green;border-style:solid;height:3em ;margin-right:1em">
                                            {{ $personne->nom_prenom }}
                                        </div>
                                    @endif
                                @endforeach
                            @endforeach
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">
                            </div>
                        @break

                        @case(3)
                            @foreach ($niv4_fieuls1 as $item)
                                @foreach ($personnes as $personne)
                                    @if ($item->personne_id === $personne->id)
                                        <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">
                                            {{ $personne->nom_prenom }}
                                        </div>
                                    @endif
                                @endforeach
                            @endforeach
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                        @break

                        @case(4)
                            @foreach ($niv4_fieuls1 as $item)
                                @foreach ($personnes as $personne)
                                    @if ($item->personne_id === $personne->id)
                                        <div class="col-md-1"
                                            style="border-color: green;border-style:solid;height:3em;margin-right:1em">
                                            {{ $personne->nom_prenom }}
                                        </div>
                                    @endif
                                @endforeach
                            @endforeach
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                        @break

                        @case(5)
                            @foreach ($niv4_fieuls1 as $item)
                                @foreach ($personnes as $personne)
                                    @if ($item->personne_id === $personne->id)
                                        <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">
                                            {{ $personne->nom_prenom }}
                                        </div>
                                    @endif
                                @endforeach
                            @endforeach
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                        @break

                        @case(6)
                            @foreach ($niv4_fieuls1 as $item)
                                @foreach ($personnes as $personne)
                                    @if ($item->personne_id === $personne->id)
                                        <div class="col-md-1"
                                            style="border-color: green;border-style:solid;height:3em;margin-right:1em">
                                            {{ $personne->nom_prenom }}
                                        </div>
                                    @endif
                                @endforeach
                            @endforeach
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                        @break

                        @case(7)
                            @foreach ($niv4_fieuls1 as $item)
                                @foreach ($personnes as $personne)
                                    @if ($item->personne_id === $personne->id)
                                        <div class="col-md-1"
                                            style="border-color: green;border-style:solid;height:3em;margin-right:1em">
                                            {{ $personne->nom_prenom }}
                                        </div>
                                    @endif
                                @endforeach
                            @endforeach
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                        @break

                        @case(8)
                            @foreach ($niv4_fieuls1 as $item)
                                @foreach ($personnes as $personne)
                                    @if ($item->personne_id === $personne->id)
                                        <div class="col-md-1"
                                            style="border-color: green;border-style:solid;height:3em;margin-right:1em">
                                            {{ $personne->nom_prenom }}
                                        </div>
                                    @endif
                                @endforeach
                            @endforeach
                        @break

                        @default
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                    @endswitch

                </div>

                <div class="col-md-5 col-md-offset-2">
                    @switch(count($niv4_fieuls2))
                        @case(0)
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                        @break

                        @case(1)
                            @foreach ($niv4_fieuls2 as $item)
                                @foreach ($personnes as $personne)
                                    @if ($item->personne_id === $personne->id)
                                        <div class="col-md-1"
                                            style="border-color: green;border-style:solid;height:3em;margin-right:1em">
                                            {{ $personne->nom_prenom }}
                                        </div>
                                    @endif
                                @endforeach
                            @endforeach
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">
                            @break

                            @case(2)
                                @foreach ($niv4_fieuls2 as $item)
                                    @foreach ($personnes as $personne)
                                        @if ($item->personne_id === $personne->id)
                                            <div class="col-md-1"
                                                style="border-color: green;border-style:solid;height:3em;margin-right:1em">
                                                {{ substr($personne->nom_prenom,0,4) }}
                                            </div>
                                        @endif
                                    @endforeach
                                @endforeach
                                <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                                </div>
                                <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                                </div>
                                <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                                </div>
                                <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                                </div>
                                <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                                </div>
                                <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">
                                @break

                                @case(3)
                                    @foreach ($niv4_fieuls2 as $item)
                                        @foreach ($personnes as $personne)
                                            @if ($item->personne_id === $personne->id)
                                                <div class="col-md-1"
                                                    style="border-color: green;border-style:solid;height:3em;margin-right:1em">
                                                    {{ substr($personne->nom_prenom,0,4) }}
                                                </div>
                                            @endif
                                        @endforeach
                                    @endforeach
                                    <div class="col-md-1"
                                        style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                                    </div>
                                    <div class="col-md-1"
                                        style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                                    </div>
                                    <div class="col-md-1"
                                        style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                                    </div>
                                    <div class="col-md-1"
                                        style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                                    </div>
                                    <div class="col-md-1"
                                        style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                                    </div>
                                @break

                                @case(4)
                                    @foreach ($niv4_fieuls2 as $item)
                                        @foreach ($personnes as $personne)
                                            @if ($item->personne_id === $personne->id)
                                                <div class="col-md-2"
                                                    style="border-color: green;border-style:solid;height:3em;margin-right:1em">
                                                    {{ substr($personne->nom_prenom,0,4) }}
                                                </div>
                                            @endif
                                        @endforeach
                                    @endforeach
                                    <div class="col-md-1"
                                        style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                                    </div>
                                    <div class="col-md-1"
                                        style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                                    </div>
                                    <div class="col-md-1"
                                        style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                                    </div>
                                    <div class="col-md-1"
                                        style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                                    </div>
                                @break

                                @case(5)
                                    @foreach ($niv4_fieuls2 as $item)
                                        @foreach ($personnes as $personne)
                                            @if ($item->personne_id === $personne->id)
                                                <div class="col-md-1"
                                                    style="border-color: green;border-style:solid;height:3em;margin-right:1em">
                                                    {{ substr($personne->nom_prenom,0,4) }}
                                                </div>
                                            @endif
                                        @endforeach
                                    @endforeach
                                    <div class="col-md-1"
                                        style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                                    </div>
                                    <div class="col-md-1"
                                        style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                                    </div>
                                    <div class="col-md-1"
                                        style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                                    </div>
                                @break

                                @case(6)
                                    @foreach ($niv4_fieuls2 as $item)
                                        @foreach ($personnes as $personne)
                                            @if ($item->personne_id === $personne->id)
                                                <div class="col-md-1"
                                                    style="border-color: green;border-style:solid;height:3em;margin-right:1em">
                                                    {{ substr($personne->nom_prenom,0,4) }}
                                                </div>
                                            @endif
                                        @endforeach
                                    @endforeach
                                    <div class="col-md-1"
                                        style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                                    </div>
                                    <div class="col-md-1"
                                        style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                                    </div>
                                @break

                                @case(7)
                                    @foreach ($niv4_fieuls2 as $item)
                                        @foreach ($personnes as $personne)
                                            @if ($item->personne_id === $personne->id)
                                                <div class="col-md-1"
                                                    style="border-color: green;border-style:solid;height:3em;margin-right:1em">
                                                    {{ substr($personne->nom_prenom,0,4) }}
                                                </div>
                                            @endif
                                        @endforeach
                                    @endforeach
                                    <div class="col-md-1"
                                        style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                                    </div>
                                @break

                                @case(8)
                                    @foreach ($niv4_fieuls2 as $item)
                                        @foreach ($personnes as $personne)
                                            @if ($item->personne_id === $personne->id)
                                                <div class="col-md-1"
                                                    style="border-color: green;border-style:solid;height:3em;margin-right:1em">
                                                    {{ substr($personne->nom_prenom,0,4) }}
                                                </div>
                                            @endif
                                        @endforeach
                                    @endforeach
                                @break

                                @default
                                    <div class="col-md-1"
                                        style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                                    </div>
                                    <div class="col-md-1"
                                        style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                                    </div>
                                    <div class="col-md-1"
                                        style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                                    </div>
                                    <div class="col-md-1"
                                        style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                                    </div>
                                    <div class="col-md-1"
                                        style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                                    </div>
                                    <div class="col-md-1"
                                        style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                                    </div>
                                    <div class="col-md-1"
                                        style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                                    </div>
                                    <div class="col-md-1" style="border-color: green;border-style:solid;height:3em">

                                    </div>
                            @endswitch
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection
