<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="icon" type="image/png" href="{{ asset('/logo/logo.png') }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="{{ asset('acceuilTemp/styles.css') }}" />
    <title>MAKE-IT Accueil</title>
</head>

<body>
    <div class="container">
        <nav>
            <img src="{{ asset('/logo/logo.png') }}" alt="" class="logo" />
        </nav>

        <section class="site-container">
            <p>Bienvenu sur</p>
            <h1>MAKE-IT</h1>
            <h4>PARTENAIRE, TU VEUX TE FAIRE UNE MAX D'ARGENT ?</h4>

            <div class="row">
                <a href="{{ url('/vitrine') }}">Me renseignez</a>
                <a href="{{ url('/connexion') }}">Oui allons-y<span>&#x27f6</span></a>
            </div>
        </section>
       
    </div>
</body>

</html>
