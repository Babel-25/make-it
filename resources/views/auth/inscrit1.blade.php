<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/png" href="{{ asset('/logo/logo.png') }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('authTemp/style.css') }}" />
    <title> Page d'inscription </title>
</head>

<body>
    <div class="container">
        <div class="forms-container">
            <div class="signin-signup">
                <form action="#" class="sign-in-form">
                    @csrf
                    <h2 class="title">Inscription</h2>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" placeholder="Code paiemment" name="Code paiemment" />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="text" placeholder="Code de parrainage" name="Code de parrainage" />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="text" placeholder="Nom" name="Nom" />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="text" placeholder="Prénom" name="Prénom" />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="text" placeholder="Contact" name="Contact" />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="mail" placeholder="Email" name="Email" />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="text" placeholder="Adresse" name="Adresse" />
                    </div>
                    <input type="submit" value="Suivant" class="btn solid" />
                </form>
            </div>
        </div>
        <div class="panels-container">
            <div class="panel left-panel">
                <div class="content">
                    <h3>ETAPE 1</h3>
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Quam quas vel rerum. Veniam quisquam
                        facere, cumque, voluptas ratione doloribus laudantium ipsum dicta optio ut rerum rem nulla qui
                        consequuntur voluptatibus!
                    </p>
                    <button class="btn"><a href="{{ route('login_form') }}"
                            style="text-decoration: none; color:white;">
                            Se connecter
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
