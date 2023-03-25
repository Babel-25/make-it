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

                    @switch(count($fieuls_p1_l1))
                        @case(0)
                            <div class="col-md-5" style="border-color: green;border-style:solid;height:3em">
                            </div>
                            <div class="col-md-2"> </div>
                            <div class="col-md-5" style="border-color: green;border-style:solid;height:3em">
                            </div>
                        @break

                        @case(1)
                            @foreach ($fieuls_p1_l1 as $item)
                                @php
                                    $personne = \App\Models\Personne::where('id', $item->personne_id)->first();
                                @endphp
                                <div class="col-md-5" style="border-color: green;border-style:solid;height:3em">
                                    {{ $personne->nom_prenom }} </div>
                                <div class="col-md-2"> </div>
                            @endforeach
                            <div class="col-md-5" style="border-color: green;border-style:solid;height:3em">
                            </div>
                        @break

                        @case(2)
                            @foreach ($fieuls_p1_l1 as $item)
                                @php
                                    $personne = \App\Models\Personne::where('id', $item->personne_id)->first();
                                @endphp
                                <div class="col-md-5" style="border-color: green;border-style:solid;height:3em">
                                    {{ $personne->nom_prenom }} </div>
                                <div class="col-md-2"> </div>
                            @endforeach
                        @break

                        @default
                            <div class="col-md-5" style="border-color: green;border-style:solid;height:3em"> </div>
                            <div class="col-md-2"> </div>
                            <div class="col-md-5" style="border-color: green;border-style:solid;height:3em">
                            </div>
                    @endswitch

                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="row mt" id="4F">

                <div class="col-md-6">

                    @switch(count($fieuls_p1_l2))
                        @case(0)
                            <div class="col-md-5" style="border-color: green;border-style:solid;height:3em">
                            </div>
                            <div class="col-md-5 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">
                            </div>
                        @break

                        @case(1)
                            @foreach ($fieuls_p1_l2 as $item)
                                @php
                                    $personne = \App\Models\Personne::where('id', $item->personne_id)->first();
                                @endphp
                                <div class="col-md-5" style="border-color: green;border-style:solid;height:3em">
                                    {{ $personne->nom_prenom }}
                                </div>
                            @endforeach
                            <div class="col-md-5 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">
                            </div>
                        @break

                        @case(2)
                            @foreach ($fieuls_p1_l2 as $item)
                                @php
                                    $personne = \App\Models\Personne::where('id', $item->personne_id)->first();
                                @endphp
                                <div class="col-md-5" style="border-color: green;border-style:solid;height:3em">
                                    {{ $personne->nom_prenom }}
                                </div>
                            @endforeach
                        @break

                        @default
                            <div class="col-md-5" style="border-color: green;border-style:solid;height:3em">
                            </div>
                            <div class="col-md-5 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">
                            </div>
                    @endswitch
                </div>

                <div class="col-md-6 ">

                    @switch(count($fieuls_p1_l2))
                        @case(3)
                            @foreach ($fieuls_p1_l2 as $item)
                                @php
                                    $personne = \App\Models\Personne::where('id', $item->personne_id)->first();
                                @endphp
                                <div class="col-md-5" style="border-color: green;border-style:solid;height:3em">
                                    {{ $personne->nom_prenom }}
                                </div>
                            @endforeach

                            <div class="col-md-5 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">
                            </div>
                        @break

                        @case(4)
                            @foreach ($fieuls_p1_l2 as $item)
                                @php
                                    $personne = \App\Models\Personne::where('id', $item->personne_id)->first();
                                @endphp
                                <div class="col-md-5" style="border-color: green;border-style:solid;height:3em">
                                    {{ $personne->nom_prenom }}
                                </div>
                            @endforeach
                        @break

                        @default
                            <div class="col-md-5" style="border-color: green;border-style:solid;height:3em">
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

                    @switch(count($fieuls_p1_l3))
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
                            @foreach ($fieuls_p1_l3 as $item)
                                @php
                                    $personne = \App\Models\Personne::where('id', $item->personne_id)->first();
                                @endphp
                                <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">
                                    {{ $personne->nom_prenom }}
                                </div>
                            @endforeach

                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                        @break

                        @case(2)
                            @foreach ($fieuls_p1_l3 as $item)
                                @php
                                    $personne = \App\Models\Personne::where('id', $item->personne_id)->first();
                                @endphp
                                <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">
                                    {{ $personne->nom_prenom }}
                                </div>
                            @endforeach
                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                        @break

                        @case(3)
                            @foreach ($fieuls_p1_l3 as $item)
                                @php
                                    $personne = \App\Models\Personne::where('id', $item->personne_id)->first();
                                @endphp
                                <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">
                                    {{ $personne->nom_prenom }}
                                </div>
                            @endforeach
                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                        @break

                        @case(4)
                            @foreach ($fieuls_p1_l3 as $item)
                                @php
                                    $personne = \App\Models\Personne::where('id', $item->personne_id)->first();
                                @endphp
                                <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">
                                    {{ $personne->nom_prenom }}
                                </div>
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

                    @switch(count($fieuls_p1_l3))
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

                        @case(5)
                            @foreach ($fieuls_p1_l3 as $item)
                                @php
                                    $personne = \App\Models\Personne::where('id', $item->personne_id)->first();
                                @endphp
                            @endforeach
                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">
                                {{ $personne->nom_prenom }}
                            </div>
                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                        @break

                        @case(6)
                            @foreach ($fieuls_p1_l3 as $item)
                                @php
                                    $personne = \App\Models\Personne::where('id', $item->personne_id)->first();
                                @endphp
                            @endforeach
                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                        @break

                        @case(7)
                            @foreach ($fieuls_p1_l3 as $item)
                                @php
                                    $personne = \App\Models\Personne::where('id', $item->personne_id)->first();
                                @endphp
                                <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">
                                    {{ $personne->nom_prenom }}
                                </div>
                            @endforeach
                            <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                        @break

                        @case(8)
                            @foreach ($fieuls_p1_l3 as $item)
                                @php
                                    $personne = \App\Models\Personne::where('id', $item->personne_id)->first();
                                @endphp
                                <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">
                                    {{ $personne->nom_prenom }}
                                </div>
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

        <div class="col-lg-12">
            <div class="row mt" id="16F">
                <div class="col-md-5">
                    @switch(count($fieuls_p1_l4))
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
                            @foreach ($fieuls_p1_l4 as $item)
                                @php
                                    $personne = \App\Models\Personne::where('id', $item->personne_id)->first();
                                @endphp
                                <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">
                                    {{ $personne->nom_prenom }}

                                </div>
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
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                        @break

                        @case(2)
                            @foreach ($fieuls_p1_l4 as $item)
                                @php
                                    $personne = \App\Models\Personne::where('id', $item->personne_id)->first();
                                @endphp
                                <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">
                                    {{ $personne->nom_prenom }}

                                </div>
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
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                        @break

                        @case(3)
                            @foreach ($fieuls_p1_l4 as $item)
                                @php
                                    $personne = \App\Models\Personne::where('id', $item->personne_id)->first();
                                @endphp
                                <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">
                                    {{ $personne->nom_prenom }}

                                </div>
                            @endforeach

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

                        @case(4)
                            @foreach ($fieuls_p1_l4 as $item)
                                @php
                                    $personne = \App\Models\Personne::where('id', $item->personne_id)->first();
                                @endphp
                                <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">
                                    {{ $personne->nom_prenom }}

                                </div>
                            @endforeach


                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                        @break

                        @case(5)
                            @foreach ($fieuls_p1_l4 as $item)
                                @php
                                    $personne = \App\Models\Personne::where('id', $item->personne_id)->first();
                                @endphp
                                <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">
                                    {{ $personne->nom_prenom }}

                                </div>
                            @endforeach
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                        @break

                        @case(6)
                            @foreach ($fieuls_p1_l4 as $item)
                                @php
                                    $personne = \App\Models\Personne::where('id', $item->personne_id)->first();
                                @endphp
                                <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">
                                    {{ $personne->nom_prenom }}

                                </div>
                            @endforeach


                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                        @break

                        @case(7)
                            @foreach ($fieuls_p1_l4 as $item)
                                @php
                                    $personne = \App\Models\Personne::where('id', $item->personne_id)->first();
                                @endphp
                                <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">
                                    {{ $personne->nom_prenom }}

                                </div>
                            @endforeach

                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                        @break

                        @case(8)
                            @foreach ($fieuls_p1_l4 as $item)
                                @php
                                    $personne = \App\Models\Personne::where('id', $item->personne_id)->first();
                                @endphp
                                <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">
                                    {{ $personne->nom_prenom }}

                                </div>
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
                    @switch(count($fieuls_p1_l4))
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

                        @case(9)
                            @foreach ($fieuls_p1_l4 as $item)
                                @php
                                    $personne = \App\Models\Personne::where('id', $item->personne_id)->first();
                                @endphp
                                <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">
                                    {{ $personne->nom_prenom }}

                                </div>
                            @endforeach
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                        @break

                        @case(10)
                            @foreach ($fieuls_p1_l4 as $item)
                                @php
                                    $personne = \App\Models\Personne::where('id', $item->personne_id)->first();
                                @endphp
                                <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">
                                    {{ $personne->nom_prenom }}

                                </div>
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
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                        @break

                        @case(11)
                            @foreach ($fieuls_p1_l4 as $item)
                                @php
                                    $personne = \App\Models\Personne::where('id', $item->personne_id)->first();
                                @endphp
                                <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">
                                    {{ $personne->nom_prenom }}

                                </div>
                            @endforeach

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

                        @case(12)
                            @foreach ($fieuls_p1_l4 as $item)
                                @php
                                    $personne = \App\Models\Personne::where('id', $item->personne_id)->first();
                                @endphp
                                <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">
                                    {{ $personne->nom_prenom }}

                                </div>
                            @endforeach


                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                        @break

                        @case(13)
                            @foreach ($fieuls_p1_l4 as $item)
                                @php
                                    $personne = \App\Models\Personne::where('id', $item->personne_id)->first();
                                @endphp
                                <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">
                                    {{ $personne->nom_prenom }}

                                </div>
                            @endforeach
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                        @break

                        @case(14)
                            @foreach ($fieuls_p1_l4 as $item)
                                @php
                                    $personne = \App\Models\Personne::where('id', $item->personne_id)->first();
                                @endphp
                                <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">
                                    {{ $personne->nom_prenom }}

                                </div>
                            @endforeach


                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">

                            </div>
                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                        @break

                        @case(15)
                            @foreach ($fieuls_p1_l4 as $item)
                                @php
                                    $personne = \App\Models\Personne::where('id', $item->personne_id)->first();
                                @endphp
                                <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">
                                    {{ $personne->nom_prenom }}

                                </div>
                            @endforeach

                            <div class="col-md-1" style="border-color: green;border-style:solid;height:3em">

                            </div>
                        @break

                        @case(16)
                            @foreach ($fieuls_p1_l4 as $item)
                                @php
                                    $personne = \App\Models\Personne::where('id', $item->personne_id)->first();
                                @endphp
                                <div class="col-md-1" style="border-color: green;border-style:solid;height:3em;margin-right:1em">
                                    {{ $personne->nom_prenom }}

                                </div>
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
            </div>
        </div>
        </div>
        </div>

    </section>
@endsection
