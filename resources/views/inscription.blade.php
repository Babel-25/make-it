<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8" />
	<title>Responsive Registration Form</title>
	<meta name="viewport" content="width=device-width,
      initial-scale=1.0" />
	<link rel="stylesheet" href="{{asset('formsTemp/style.css')}}" />
</head>

<body>
	<div class="container">
		<h1 class="form-title">Registration</h1>
		<form action="#">
			<div class="main-user-info">
				<div class="user-input-box">
					<label for="codePaie">Code paiemment</label>
					<input type="text" id="codePaie" name="codePaie" placeholder="Entrer le Code de paiement" />
				</div>
				<div class="user-input-box">
					<label for="codeId">Identifiant du parrain</label>
					<input type="text" id="codeId" name="codeId" placeholder="Entrer l'Id du parrain" />
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
					<label for="tel">Télephone</label>
					<input type="text" id="tel" name="tel" placeholder="Entrer votre numéro" />
				</div>
				<div class="user-input-box">
					<label for="email">Email</label>
					<input type="email" id="email" name="email" placeholder="Entrer votre mail" />
				</div>
				<div class="user-input-box">
					<label for="username">Nom d'utilisateur</label>
					<input type="text" id="username" name="username" placeholder="Entrer un nom d'utilisateur" />
				</div>
				<div class="user-input-box">
					<label for="password">P</label>
					<input type="password" id="password" name="password" placeholder="Entrer votre mots de passe" />
				</div>
				<div class="user-input-box">
					<label for="confirmPassword">Confirm Password</label>
					<input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirmez" />
				</div>
			</div>
			<div class="gender-details-box">
				<span class="gender-title">sexe</span>
				<div class="gender-category">
					<input type="radio" name="gender" id="male">
					<label for="male">Fommme</label>
					<input type="radio" name="gender" id="female">
					<label for="female">Femme</label>
				</div>
			</div>
			<div class="form-submit-btn">
				<input type="submit" value="Register">
			</div>
		</form>
	</div>
</body>

</html>