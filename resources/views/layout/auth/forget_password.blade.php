@extends('layout.auth.index')
@section('content')
@section('title', 'Mot de passe oublié')
<div>
    @if (session()->exists('message'))
        <div class="alert alert-success" id="alert">
            {{ session('message') }}
        </div>
    @endif
</div>
<div class="container">
    <div class="forms-container">
        <div class="signin-signup">
            <form action="{{ route('forget_pwd_action') }}" method="POST" class="sign-in-form">
                @csrf
                <h2 class="title">Récupération</h2>
                <div class="input-field">
                    <i class="fas fa-envelope"></i>
                    <input type="text" name="email" placeholder="Email" required />
                    @error('email')
                        <div class="text-danger"> {{ $message }} </div>
                    @enderror
                </div>

                <input type="submit" value="reinitialiser" class="btn solid" />
                <div class="text-center p-t-15">
                    <p>
                        {{-- <span class="txt0" style="text-decoration: none">
                                Je me connecte
                            </span> --}}
                        <a class="txt0" style="text-decoration: none" href="{{ route('login_form') }}"
                            style="text-decoration: none">
                            Je me connecte ici</a>
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
                    Veuillez saisir votre adresse email puis valider.
                    <br>
                    Consultez votre boîte mail sans oublier les spams ; vous devez reçevoir un message de
                    réinitialisation de compte.
                </p>

            </div>
            <img src="{{ asset('Auth/img/register.svg') }}" class="image" alt="" />
        </div>
    </div>
</div>


@endsection
