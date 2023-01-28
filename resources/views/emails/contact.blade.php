<p>
    <!-- Salut, <br>
    Vous avez un nouveau mail de votre site internet. <br><br>

    <strong> Détails: </strong><br>
    <strong> Utilisateur: </strong> {{$data['name']}} <br>
    <strong> Email: </strong> {{$data['email']}} <br>
    <strong> Message: </strong> <em>{{$data['message']}} </em> <br>-->
<div class="col-md-6">
    <div class="card mb-3" style="max-width: 540px;">
        <div class="row no-gutters">
            <div class="col-md-4" align="center">
                <img src="{{ asset('logo/logo.png') }}" style="width: 100px; height: 100px; margin-top: 1cm;" class="card-img" alt="...">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title">{{$data['name']}}</h5>
                    <p class="card-text">{{$data['message']}}</p>
                    <p class="card-text"><small class="text-muted"> Envoyé par : {{$data['email']}}</small></p>
                </div>
            </div>
        </div>
    </div>
</div>
</p>