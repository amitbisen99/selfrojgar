@component('mail::message')
# Hello, {{ $user->name }}

You requested a password reset. Your new password is:

**{{ $pass }}**

Please use this password to login and change it after you log in.

Thanks,
{{ config('app.name') }}
@endcomponent
