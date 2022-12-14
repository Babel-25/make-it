<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/png" href="{{asset('/logo/logo.png')}}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{asset('authTemp/style.css')}}" />
    <title>Sign in Form</title>
</head>

<body>
    <div class="container">
        <div class="forms-container">
            <div class="signin-signup">
                <form action="#" class="sign-in-form">
                    <h2 class="title">Connexion</h2>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" placeholder="Username" />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" placeholder="Password" />
                    </div>
                    <input type="submit" value="Se connecter" class="btn solid" />
                    <div class="text-center p-t-15">
                        <p>
                            <span class="txt1">
                                Mot de passe
                            </span>
                            <a class="txt2" href="{{ url('/mpOublier') }} ">
                                Oublier ?</a>&nbsp;
                            <span class="txt1">
                                retour à
                            </span>
                            <a class="txt2" href="{{ url('/acceuil') }}">
                                l'acceuil !
                            </a>
                        </p>
                    </div>
                    <p class="social-text">Or Sign in with social platforms</p>
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
                            <i class="fab fa-linkedin-in"></i>
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
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Debitis,
                        ex ratione. Aliquid!
                    </p>
                    <button class="btn transparent"><a href="{{ url('/inscription') }}">
                            Creer un compte
                        </a>
                    </button>
                </div>
                <img src="{{asset('authTemp/img/log.svg')}}" class="image" alt="" />
            </div>
        </div>
    </div>

    <script src="{{asset('authTemp/app.js')}}"></script>
</body>

</html>