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
                    <h2 class="title">Récupération</h2>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" placeholder="Email" />
                    </div>

                    <input type="submit" value="Renitialiser" class="btn solid" />
                    <div class="text-center p-t-15">
                        <p>
                            <span class="txt1">
                                se
                            </span>
                            <a class="txt2" href="{{ url('/connexion') }} ">
                                connecter !</a>
                        </p>
                    </div>

                </form>
            </div>
        </div>

        <div class="panels-container">
            <div class="panel left-panel">
                <div class="content">
                    <h3>Mot de passe perdu ?</h3>
                    <p>
                        veillez saisir votre mail puis valider et consulter vos messegaries soit votre spams!
                    </p>

                </div>
                <img src="{{asset('authTemp/img/register.svg')}}" class="image" alt="" />
            </div>
        </div>
    </div>

    <script src="{{asset('authTemp/app.js')}}"></script>
</body>

</html>