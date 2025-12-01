@component('mail::message')
# Halo, {{ $user->username }}

Terima kasih telah mendaftar di platform kami.  
Silakan verifikasi email Anda dengan mengklik tombol di bawah:

@component('mail::button', ['url' => $verificationUrl])
Verifikasi Email
@endcomponent

Jika tombol tidak berfungsi, salin dan tempel link berikut:

{{ $verificationUrl }}

Terima kasih,<br>
{{ config('app.name') }}
@endcomponent
