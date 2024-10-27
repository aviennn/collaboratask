@component('mail::message')
# Hello, {{ $notifiable->name }}

You have been invited by **{{ $inviter->name }}** to join the team **{{ $team->name }}**.

@component('mail::button', ['url' => url('/teams/invitations')])
View Invitation
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
