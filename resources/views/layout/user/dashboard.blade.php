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
        <div id="DeuxFieuls" class="mt">

            <div class="row">
                <div class="col-md-6" style="border-color: green;border-style:solid;height:3em"> </div>
                <div class="col-md-6 " style="border-color: green;border-style:solid;height:3em">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="row mt" id="2Fieuls">
            <div class="col-md-6">
                <div class="col-md-5" style="border-color: green;border-style:solid;height:3em">

                </div>
                <div class="col-md-5 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                </div>
            </div>
            <div class="col-md-6 ">
                <div class="col-md-5 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                </div>
                <div class="col-md-5 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="row mt" id="4Fieuls">
            <div class="col-md-4">
                <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                </div>
                <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                </div>
                <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                </div>
                <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                </div>
            </div>
            <div class="col-md-4 col-md-offset-4">
                <div class="col-md-2 " style="border-color: green;border-style:solid;height:3em">

                </div>
                <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                </div>
                <div class="col-md-2 col-md-offset-2" style="border-color: green;border-style:solid;height:3em">

                </div>
                <div class="col-md-2 col-md-offset-1" style="border-color: green;border-style:solid;height:3em">

                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="row mt" id="8Fieuls">

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
    </div>
</section>
<!--<section class="container mt">
        <div id="PhaseI">
            <div class="bg-theme01 row text-center text-success " style="border-color: green;border-style:solid;height:3em">
                <div class="col-md-6 text-white"> Gain Brut Phase A :</div>
                <div class="col-md-6 text-white"> 10 000 F CFA</div>
            </div>
        </div>

        <div class="bg-theme01 row mt" style="border-color: green;border-style:solid;height:3em">
            <div class="bg-theme01 col-md-4 "> Gain Parrainage direct </div>
            <div class="bg-white col-md-8 text-theme01 text-center"> 600 F CFA</div>
        </div>
        <div class="bg-theme01 row mt" style="border-color: green;border-style:solid;height:3em">
            <div class="bg-theme01 col-md-6 "> Gain net total de la phase I </div>
            <div class="bg-white col-md-6 text-theme01 text-center"> 9 540 F CFA</div>
        </div>
    </section>-->
@endsection