@component('mail::message')
{{ __('Você foi convidado para se juntar à equipe :team!', ['team' => $invitation->team->name]) }}

@if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::registration()))
{{ __('Se você não tiver uma conta, você pode criar uma clicando no botão abaixo. Após criar uma conta, você pode clicar no botão de aceitação de convite neste e-mail para aceitar o convite da equipe:') }}

@component('mail::button', ['url' => route('register')])
{{ __('Criar uma conta') }}
@endcomponent

{{ __('Se você já possui uma conta, pode aceitar este convite clicando no botão abaixo:') }}

@else
{{ __('Você pode aceitar este convite clicando no botão abaixo:') }}
@endif


@component('mail::button', ['url' => $acceptUrl])
{{ __('Aceitar convite') }}
@endcomponent

{{ __('Se você não esperava receber um convite para esta equipe, você pode descartar este e-mail.') }}
@endcomponent
