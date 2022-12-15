<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/png" href="{{ asset('/logo/logo.png') }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('authTemp/style.css') }}" />
    <title> Page de connexion </title>
</head>

<body>
    <div class="container">
        <div class="forms-container">
            <div class="signin-signup">
                <form action="{{ url('/Monreseau') }}" class="sign-in-form">
                    <h2 class="title">Connexion</h2>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" placeholder="Identifiant" name="username" />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" placeholder="Mot de passe" name="password" />
                    </div>
                    <input type="submit" value="Se connecter" class="btn solid" />
                    <div class="text-center p-t-15">
                        <p>
                            <a class="txt2" href="{{ url('/mpOublier') }} ">
                                Mot de passe oublié ?</a>
                            </span>
                            &nbsp;
                            <a class="txt2" href="{{ url('/acceuil') }}">
                                Acceuil !
                            </a>

                        </p>
                    </div>
                    <p> ---------- OU ------------ </p>
                    <p class="social-text"> Se connecter avec les réseaux sociaux</p>
                    <div class="social-media">
                        <a href="#" class="social-icon">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-google"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class='fab fa-linkedin-in'></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="panels-container">
            <div class="panel left-panel">
                <div class="content">
                    <h3>Nouveau ?</h3>
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Quam quas vel rerum. Veniam quisquam
                        facere, cumque, voluptas ratione doloribus laudantium ipsum dicta optio ut rerum rem nulla qui
                        consequuntur voluptatibus!
                    </p>
                    <button class="btn"><a href="{{ url('/inscription') }}"
                            style="text-decoration: none; color:white;">
                            Créer un compte
                        </a>
                    </button>
                </div>
                <img src="{{ asset('authTemp/img/log.svg') }}" class="image" alt="" />
            </div>
        </div>
    </div>

    <script src="{{ asset('authTemp/app.js') }}"></script>
</body>

</html>
