@extends('layout.auth.index')
@section('content')
@section('title', 'Connexion')

<div class="container">
    <div class="forms-container">
        <div class="signin-signup">

            <form action="{{ route('login_action') }}" class="sign-in-form" method="POST">
                @csrf
                <h2 class="title">Connectez-vous</h2>
                <div>
<<<<<<< HEAD
                    @if (session()->exists('test'))
                    <div class="alert alert-success" id="alert" align="center">
                        {{ session('test') }}
                    </div>
=======
                    @if (session()->exists('message'))
                        <div class="alert alert-success" id="alert">
                            {{ session('message') }}
                        </div>
>>>>>>> 65cba0dece17992afe2185bc0b3f0737d7df8e34
                    @endif
                </div>
                <div class="input-field">
                    <i class="fas fa-user"></i>
<<<<<<< HEAD
                    <input type="text" placeholder="Identifiant"  value="{{ old('identifiant') }}" name="identifiant" required />
                </div>
                <span>
                    @error('Identifiant')
                    <div class="text-danger"> {{ $message }} </div>
                    @enderror
                </span>
=======
                    <input type="text" placeholder="Identifiant" name="identifiant" required />
                    <span>
                        @error('Identifiant')
                            <div class="text-danger"> {{ $message }} </div>
                        @enderror
                    </span>
                </div>

>>>>>>> 65cba0dece17992afe2185bc0b3f0737d7df8e34
                <div class="input-field">
                    <i class="fas fa-lock"></i>
                    <input type="password" placeholder="Mot de passe" name="password" required />
                    <span>
                        @error('password')
                            <div class="text-danger"> {{ $message }} </div>
                        @enderror
                    </span>
                </div>
<<<<<<< HEAD
                <span>
                    @error('password')
                    <div class="text-danger"> {{ $message }} </div>
                    @enderror
                </span>
=======

>>>>>>> 65cba0dece17992afe2185bc0b3f0737d7df8e34
                <input type="submit" value="Se connecter" class="btn solid" />
                <div class="text-center p-t-15">
                    <p>
                        <a class="txt0" style="text-decoration:none" href="{{ route('forget_pwd_form') }} ">
                            Mot de passe oublié ?</a>
                        </span>
                        &nbsp;
                        <a class="txt0" style="text-decoration:none" href="{{ route('home') }}">
                            Acceuil
                        </a>

                    </p>
                </div>
                <p> ---------- OU ----------</p>
                <p class="social-text"> Visiter nos réseaux sociaux</p>
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
                <button class="btn transparent" id="sign-up-btn"><a href="{{ route('register_form') }}" style="text-decoration: none;">
                        Créer un compte
                    </a>
                </button>
            </div>
            <img src="{{ asset('Auth/img/register.svg') }}" class="image" alt="" />
        </div>
    </div>
</div>

@endsection