@component('mail::message')
Nouveau mot de passe
<br>
Copiez : {{ $data }}
<br>
{{-- Connectez-vous ici <a href="{{ route('login_form') }}"> --}}

@component('mail::button', ['url' => route('login_form')])
Connectez-vous ici
@endcomponent
<br>
>>
{{ config('app.name') }}
@endcomponent

