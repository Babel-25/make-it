<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Inscription</title>
    <meta name="viewport" content="width=device-width,
      initial-scale=1.0" />
    <link rel="stylesheet" href="{{ asset('formsTemp/style.css') }}" />
</head>

<body>
    <div class="container">
        <h1 class="form-title">Formulaire d'inscription</h1>
        <form action="{{ url('/Monreseau') }}">
            @csrf
            <div class="main-user-info">
                {{-- <div class="user-input-box">
					<label for="codePaie">Code paiemment</label>
					<input type="text" id="codePaie" name="codePaie" placeholder="Entrer le Code de paiement" />
				</div> --}}
                <div class="user-input-box">
                    <label for="codeId">Code de parrainage</label>
                    <input type="number" id="codeId" name="codeId" placeholder="Saisir code de parrainage" />
                </div>
                <div class="user-input-box">
                    <label for="nom">Nom</label>
                    <input type="nom" id="nom" name="nom" placeholder="Entrer votre nom" />
                </div>
                <div class="user-input-box">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" placeholder="Entrer votre prénom" />
                </div>
                <div class="user-input-box">
                    <label for="tel">Contact</label>
                    <input type="text" id="tel" name="tel" placeholder="Saisire votre contact" />
                </div>
                <div class="user-input-box">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Saisir votre adresse email" />
                </div>
                <div class="user-input-box">
                    <label for="adresse">Adresse</label>
                    <input type="text" id="adresse" name="adresse" placeholder="Saisir votre adresse" />
                </div>
                <div class="user-input-box">
                    <label for="username">Identifiant</label>
                    <input type="text" id="username" name="username" placeholder="Saisir votre identifiant" />
                </div>
                <div class="user-input-box">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" placeholder="Entrer votre mot de passe" />
                </div>
                <div class="user-input-box">
                    <label for="confirmPassword">Confirmer mot de passe</label>
                    <input type="password" id="confirmPassword" name="confirmPassword"
                        placeholder="Confirmer votre mot de passe" />
                </div>
            </div>
            <div class="gender-details-box">
                <span class="gender-title">Sexe </span>
                <div class="gender-category">
                    <input type="radio" name="gender" id="male">
                    <label for="male">Homme</label>
                    <input type="radio" name="gender" id="female">
                    <label for="female">Femme</label>
                </div>
            </div>
            <div class="form-submit-btn">
                <input type="submit" value="Continuer">
            </div>
            <div class="text-center p-t-3 ">
                <p>
                    <a class="txt2" href="{{ url('/connexion') }} " style="text-decoration: none;color:white">
                        Déjà un compte ?</a>
                    </span>
                    &nbsp;
                    <span class="txt1">
                        <a class="txt2" href="{{ url('/acceuil') }}" style="text-decoration: none;color:white">
                            Acceuil !
                        </a>
                    </span>

                </p>
            </div>
        </form>


    </div>
</body>

</html>
